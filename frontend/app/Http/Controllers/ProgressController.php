<?php

namespace App\Http\Controllers;

use App\Models\WorkoutLog;
use App\Models\BmiRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil data berat badan dari BmiRecord untuk chart
        $weightRecords = BmiRecord::where('user_id', $user->id)->orderBy('recorded_at', 'asc')->get();
        $weightLabels = $weightRecords->pluck('recorded_at')->map(fn($date) => \Carbon\Carbon::parse($date)->format('d M'))->values()->toArray();
        $weightData = $weightRecords->pluck('weight_kg')->values()->toArray();

        // Ambil data log latihan
        $workoutLogs = WorkoutLog::where('user_id', $user->id)->orderBy('log_date', 'desc')->get();
        $totalWorkouts = $workoutLogs->count();

        // Data Weekly Plan
        $weeklyPlans = \App\Models\WeeklyWorkoutPlan::where('user_id', $user->id)
                            ->get()->keyBy('day_of_week');

        // Hitung konsistensi bulan ini (dummy for now, let's say >= 4 is consistent)
        $thisMonthWorkouts = $workoutLogs->where('log_date', '>=', now()->startOfMonth())->count();

        $badges = [
            ['icon'=>'bi-fire', 'name'=>'10 Sesi Pertama', 'done' => $totalWorkouts >= 10, 'current' => $totalWorkouts, 'target' => 10],
            ['icon'=>'bi-trophy', 'name'=>'1 Bulan Konsisten', 'done' => $thisMonthWorkouts >= 30, 'current' => $thisMonthWorkouts, 'target' => 30],
            ['icon'=>'bi-star-fill', 'name'=>'50 Sesi', 'done' => $totalWorkouts >= 50, 'current' => $totalWorkouts, 'target' => 50],
            ['icon'=>'bi-award-fill', 'name'=>'100 Sesi', 'done' => $totalWorkouts >= 100, 'current' => $totalWorkouts, 'target' => 100],
        ];

        return view('dashboard.progress', compact('weightLabels', 'weightData', 'workoutLogs', 'badges', 'weeklyPlans', 'totalWorkouts', 'thisMonthWorkouts'));
    }

    public function storeLog(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'focus_area' => 'required|string',
            'notes' => 'nullable|string',
            'log_date' => 'required|date',
            // Kita bisa menambahkan validasi exercises jika di-pass sebagai array
        ]);

        // GO-BACKEND API CALL for Workout Log
        try {
            $response = \Illuminate\Support\Facades\Http::withToken($this->getGoJwtToken())
                ->post('http://localhost:8080/api/workout', [
                    'focus_area' => $request->focus_area,
                    'notes' => $request->notes,
                    'log_date' => $request->log_date,
                ]);

            if ($response->successful()) {
                $goLog = $response->json('data');
                // Fetch the created log via Eloquent so we can attach exercises
                $log = WorkoutLog::find($goLog['id']);
            } else {
                throw new \Exception('Golang returned error');
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mencatat log latihan ke Golang: ' . $e->getMessage());
            // Fallback
            $log = WorkoutLog::create([
                'user_id' => $user->id,
                'focus_area' => $request->focus_area,
                'log_date' => $request->log_date,
                'notes' => $request->notes,
            ]);
        }

        // Simpan exercises (asumsi request format: exercises => [['name' => '', 'sets' => '', 'reps' => '']])
        if ($log && $request->has('exercises') && is_array($request->exercises)) {
            foreach ($request->exercises as $ex) {
                if (!empty($ex['name'])) {
                    $log->exercises()->create([
                        'exercise_name' => $ex['name'],
                        'sets' => isset($ex['duration']) ? 0 : ($ex['sets'] ?? 0),
                        'reps' => isset($ex['duration']) ? 0 : ($ex['reps'] ?? 0),
                        'duration_minutes' => isset($ex['duration']) ? $ex['duration'] : null,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Log Latihan berhasil dicatat!');
    }

    public function storePlan(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'plan' => 'required|array', // ['0' => 'Rest', '1' => 'Chest']
        ]);

        foreach ($request->plan as $day => $target) {
            \App\Models\WeeklyWorkoutPlan::updateOrCreate(
                ['user_id' => $user->id, 'day_of_week' => $day],
                ['target_muscle_group' => $target ?? 'Rest Day']
            );
        }

        return redirect()->back()->with('success', 'Rencana latihan mingguan berhasil disimpan!');
    }
}
