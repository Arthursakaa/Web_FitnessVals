<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\IntakeLog;
use App\Models\ClassBooking;
use App\Models\GymClass;
use Carbon\Carbon;

class MemberDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Hitung "Hari ke-X"
        $daysSinceJoined = intval(Carbon::parse($user->created_at)->diffInDays(now())) + 1;
        
        // Hitung kalori hari ini
        $todayCalories = IntakeLog::where('user_id', $user->id)
                                  ->whereDate('log_date', today())
                                  ->sum('calories');
                                  
        // Target kalori (dummy logic for now, should ideally be from BMI)
        $profile = \App\Models\UserProfile::where('user_id', $user->id)->first();
        $targetCalories = 2000; // Default
        if ($profile && $profile->weight_kg) {
            // Rough BMR * Activity multiplier
            $bmr = $profile->gender === 'male' ? 
                10 * $profile->weight_kg + 6.25 * $profile->height_cm - 5 * $profile->age + 5 : 
                10 * $profile->weight_kg + 6.25 * $profile->height_cm - 5 * $profile->age - 161;
            $targetCalories = round($bmr * ($profile->activity_level_multiplier ?? 1.2));
        }

        // Kelas Berikutnya
        $nextClass = \App\Models\ClassSchedule::whereHas('bookings', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('start_time', '>=', now())
            ->orderBy('start_time', 'asc')
            ->with('gymClass')
            ->first();

        // Jumlah kelas aktif bulan ini
        $activeClassesCount = ClassBooking::where('user_id', $user->id)
            ->whereMonth('created_at', now()->month)
            ->count();

        // AI Recommendation Logic
        $todayWorkouts = \App\Models\WorkoutLog::where('user_id', $user->id)->whereDate('log_date', today())->get();
        $workoutIntensity = 'rest';
        
        if ($todayWorkouts->count() > 0) {
            $hasCardio = false;
            $hasStrength = false;
            
            foreach ($todayWorkouts as $workout) {
                $focus = strtolower($workout->focus_area);
                if (str_contains($focus, 'cardio') || str_contains($focus, 'hiit')) {
                    $hasCardio = true;
                } else {
                    $hasStrength = true;
                }
            }
            
            if ($hasStrength && $hasCardio) {
                $workoutIntensity = 'hiit'; 
            } elseif ($hasCardio) {
                $workoutIntensity = 'cardio';
            } else {
                $workoutIntensity = 'strength'; 
            }
        }

        // GO-BACKEND API CALL for Recommendations
        $recommendedMeals = collect();
        try {
            $response = \Illuminate\Support\Facades\Http::withToken($this->getGoJwtToken())
                            ->get('http://localhost:8080/api/meals/recommendations', [
                                'workout_intensity' => $workoutIntensity
                            ]);
                            
            if ($response->successful()) {
                $recommendedMeals = collect($response->json())->map(function($m) {
                    $obj = (object)$m;
                    if(isset($m['match_rate'])) {
                        $obj->match_rate = $m['match_rate'];
                    }
                    return $obj;
                })->sortByDesc('match_rate')->take(3);
            }
        } catch (\Exception $e) {}

        // Tambahan data statistik untuk card Target Bulanan
        $workoutLogsCount = \App\Models\WorkoutLog::where('user_id', $user->id)->whereMonth('log_date', now()->month)->count();

        // Data Berat Badan
        $weightRecords = \App\Models\BmiRecord::where('user_id', $user->id)->orderBy('recorded_at', 'asc')->get();
        $weightLabels = $weightRecords->pluck('recorded_at')->map(fn($date) => \Carbon\Carbon::parse($date)->format('d M'))->values()->toArray();
        $weightData = $weightRecords->pluck('weight_kg')->values()->toArray();
        $currentWeight = count($weightData) > 0 ? end($weightData) : 0;
        $targetWeight = $profile->target_weight_kg ?? 0;

        return view('dashboard.home', compact('user', 'daysSinceJoined', 'todayCalories', 'targetCalories', 'nextClass', 'activeClassesCount', 'recommendedMeals', 'workoutLogsCount', 'weightLabels', 'weightData', 'currentWeight', 'targetWeight'));
    }
}
