<?php

namespace App\Http\Controllers;

use App\Models\BmiRecord;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BmiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Fetch BMI history for chart
        $bmiRecords = BmiRecord::where('user_id', $user->id)
                                ->orderBy('recorded_at', 'asc')
                                ->get();
                                
        // Fetch latest profile
        $profile = UserProfile::where('user_id', $user->id)->first();

        // Format data for Chart.js
        $chartLabels = $bmiRecords->pluck('recorded_at')->map(fn($date) => \Carbon\Carbon::parse($date)->format('d M'))->values()->toArray();
        $chartData = $bmiRecords->pluck('bmi_value')->values()->toArray();
        $weightData = $bmiRecords->pluck('weight_kg')->values()->toArray();
        $targetWeight = $profile ? $profile->target_weight_kg : null;

        return view('dashboard.bmi', compact('bmiRecords', 'profile', 'chartLabels', 'chartData', 'weightData', 'targetWeight'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'weight' => 'required|numeric',
            'height' => 'required|numeric',
            'age' => 'required|integer',
            'gender' => 'required|in:male,female',
            'activity' => 'required|numeric',
            'bmi' => 'required|numeric',
            'category' => 'required|string',
            'calories' => 'required|integer',
        ]);

        $user = Auth::user();

        // Check if user already submitted today
        $todayRecord = BmiRecord::where('user_id', $user->id)
            ->whereDate('recorded_at', now()->toDateString())
            ->first();

        if ($todayRecord) {
            return response()->json([
                'success' => false, 
                'message' => 'Anda sudah merekam BMI hari ini. Silakan coba lagi besok untuk menjaga kualitas grafik Anda.'
            ]);
        }

        // Update or create UserProfile
        UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'weight_kg' => $request->weight,
                'height_cm' => $request->height,
                'age' => $request->age,
                'gender' => $request->gender,
                'activity_level_multiplier' => $request->activity,
            ]
        );

        // Save BMI Record via GO-BACKEND API
        try {
            $response = \Illuminate\Support\Facades\Http::withToken($this->getGoJwtToken())
                ->post('http://localhost:8080/api/bmi', [
                    'weight_kg' => (float)$request->weight,
                    'height_cm' => (float)$request->height,
                ]);

            if (!$response->successful()) {
                throw new \Exception('Golang returned error');
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal menyimpan BMI via Golang: ' . $e->getMessage());
            // Fallback
            BmiRecord::create([
                'user_id' => $user->id,
                'weight_kg' => $request->weight,
                'height_cm' => $request->height,
                'bmi_value' => $request->bmi,
                'category' => $request->category,
                'recommended_calories' => $request->calories,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Data BMI berhasil disimpan melalui Mesin Go!']);
    }
}
