<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BmiRecord;
use App\Models\UserProfile;
use App\Models\IntakeLog;
use App\Models\WorkoutLog;
use App\Models\Meal;

class NutritionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = UserProfile::where('user_id', $user->id)->first();
        
        // 1. Calculate Targets
        $targetCalories = 2000;
        $targetProtein = 120;
        $targetCarbs = 200;
        $targetFat = 60;

        if ($profile && $profile->weight_kg && $profile->height_cm && $profile->age) {
            $bmr = $profile->gender === 'male' ? 
                10 * $profile->weight_kg + 6.25 * $profile->height_cm - 5 * $profile->age + 5 : 
                10 * $profile->weight_kg + 6.25 * $profile->height_cm - 5 * $profile->age - 161;
            
            $tdee = $bmr * ($profile->activity_level_multiplier ?? 1.2);
            $targetCalories = round($profile->target_weight_kg < $profile->weight_kg ? $tdee - 400 : ($profile->target_weight_kg > $profile->weight_kg ? $tdee + 400 : $tdee));
            
            $targetProtein = round($profile->weight_kg * 2.2);
            $targetFat = round(($targetCalories * 0.25) / 9);
            $targetCarbs = round(($targetCalories - ($targetProtein * 4) - ($targetFat * 9)) / 4);
        }

        // 2. Fetch Today's Intake
        $todayIntake = IntakeLog::where('user_id', $user->id)
                                ->whereDate('log_date', today())
                                ->get();
                                
        $consumedCalories = $todayIntake->sum('calories');
        $consumedProtein = $todayIntake->sum('protein_g');
        $consumedCarbs = $todayIntake->sum('carbs_g');
        $consumedFat = $todayIntake->sum('fat_g');

        // Fetch Intake History grouped by date
        $intakeHistory = IntakeLog::where('user_id', $user->id)
            ->orderBy('log_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($item) {
                return \Carbon\Carbon::parse($item->log_date)->format('Y-m-d');
            });

        // 3. AI Rule-Based Logic based on Today's Workout
        $todayWorkouts = WorkoutLog::where('user_id', $user->id)->whereDate('log_date', today())->get();
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
                $workoutIntensity = 'hiit'; // Mixed / High Intensity
            } elseif ($hasCardio) {
                $workoutIntensity = 'cardio';
            } else {
                $workoutIntensity = 'strength'; // Default for most gym workouts
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
                    // Because Go includes MatchRate in the object
                    if(isset($m['match_rate'])) {
                        $obj->match_rate = $m['match_rate'];
                    }
                    return $obj;
                });
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengambil recommendations dari Golang: ' . $e->getMessage());
        }

        // Determine auto meal type based on current time
        $hour = date('H');
        $autoMealType = 'Makan Malam';
        if ($hour >= 4 && $hour < 11) $autoMealType = 'Sarapan';
        elseif ($hour >= 11 && $hour < 15) $autoMealType = 'Makan Siang';
        elseif ($hour >= 15 && $hour < 18) $autoMealType = 'Snack';

        return view('dashboard.nutrition', compact(
            'targetCalories', 'targetProtein', 'targetCarbs', 'targetFat',
            'consumedCalories', 'consumedProtein', 'consumedCarbs', 'consumedFat',
            'todayIntake', 'recommendedMeals', 'workoutIntensity', 'autoMealType', 'intakeHistory'
        ));
    }

    public function storeLog(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'meal_type' => 'required|string',
            'calories' => 'required|numeric',
            'protein_g' => 'required|numeric',
            'carbs_g' => 'required|numeric',
            'fat_g' => 'required|numeric',
        ]);

        // GO-BACKEND API CALL for Intake Log
        try {
            \Illuminate\Support\Facades\Http::withToken($this->getGoJwtToken())
                ->post('http://localhost:8080/api/intake', [
                    'name' => $request->name,
                    'meal_type' => $request->meal_type,
                    'calories' => (int)$request->calories,
                    'protein_g' => (int)$request->protein_g,
                    'carbs_g' => (int)$request->carbs_g,
                    'fat_g' => (int)$request->fat_g,
                ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mencatat log makanan ke Golang: ' . $e->getMessage());
            // Fallback (opsional, jika ingin tetap save di Laravel kalau Go mati)
            // IntakeLog::create([...]);
        }

        return redirect()->back()->with('success', 'Makanan berhasil dicatat!');
    }
    
    public function meals()
    {
        $user = Auth::user();
        $profile = UserProfile::where('user_id', $user->id)->first();
        
        // Calculate basic target for display
        $targetCalories = 2000;
        if ($profile && $profile->weight_kg && $profile->height_cm && $profile->age) {
            $bmr = $profile->gender === 'male' ? 
                10 * $profile->weight_kg + 6.25 * $profile->height_cm - 5 * $profile->age + 5 : 
                10 * $profile->weight_kg + 6.25 * $profile->height_cm - 5 * $profile->age - 161;
            
            $tdee = $bmr * ($profile->activity_level_multiplier ?? 1.2);
            $targetCalories = round($profile->target_weight_kg < $profile->weight_kg ? $tdee - 400 : ($profile->target_weight_kg > $profile->weight_kg ? $tdee + 400 : $tdee));
        }

        // GO-BACKEND API CALL (Algoritma Filter AI dipindah ke Golang)
        $breakfastMeals = collect();
        $lunchMeals = collect();
        $dinnerMeals = collect();

        try {
            $response = \Illuminate\Support\Facades\Http::withToken($this->getGoJwtToken())
                            ->get('http://localhost:8080/api/meals');
                            
            if ($response->successful()) {
                $data = $response->json();
                
                // Map the JSON response arrays to Laravel objects so the Blade view works
                $breakfastMeals = collect($data['breakfast'] ?? [])->map(fn($m) => (object)$m);
                $lunchMeals = collect($data['lunch'] ?? [])->map(fn($m) => (object)$m);
                $dinnerMeals = collect($data['dinner'] ?? [])->map(fn($m) => (object)$m);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengambil data meals dari Golang: ' . $e->getMessage());
        }

        return view('dashboard.meals', compact('profile', 'targetCalories', 'breakfastMeals', 'lunchMeals', 'dinnerMeals'));
    }
}
