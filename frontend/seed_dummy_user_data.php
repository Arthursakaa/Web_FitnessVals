<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'test@example.com')->first();
if ($user) {
    \App\Models\UserProfile::updateOrCreate(
        ['user_id' => $user->id],
        [
            'age' => 28,
            'gender' => 'male',
            'height_cm' => 175,
            'weight_kg' => 75,
            'target_weight_kg' => 70,
            'activity_level_multiplier' => 1.55,
            'dietary_preference' => 'Normal',
            'medical_history' => []
        ]
    );

    \App\Models\BmiRecord::create([
        'user_id' => $user->id,
        'height_cm' => 175,
        'weight_kg' => 75,
        'bmi_value' => 24.5,
        'category' => 'Normal',
        'recorded_at' => now(),
    ]);

    \App\Models\WorkoutLog::create([
        'user_id' => $user->id,
        'log_date' => today(),
        'focus_area' => 'Strength',
        'notes' => 'Latihan yang sangat baik hari ini.'
    ]);

    \App\Models\IntakeLog::create([
        'user_id' => $user->id,
        'log_date' => today(),
        'meal_type' => 'Sarapan',
        'name' => 'Oatmeal & Protein Shake',
        'calories' => 450,
        'protein_g' => 30,
        'carbs_g' => 50,
        'fat_g' => 10
    ]);
    
    echo "Dummy user data seeded successfully!\n";
} else {
    echo "User test@example.com not found.\n";
}
