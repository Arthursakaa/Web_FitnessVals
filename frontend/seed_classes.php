<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$classes = [
    ['name' => 'BODYPUMP', 'type' => 'Strength', 'duration_minutes' => 60, 'max_capacity' => 20, 'description' => 'Latihan barbel yang melatih seluruh kelompok otot utama. (Hard)'],
    ['name' => 'ZUMBA', 'type' => 'Dance', 'duration_minutes' => 60, 'max_capacity' => 30, 'description' => 'Kombinasi tarian Latin dan gerakan fitness yang menyenangkan. (Easy)'],
    ['name' => 'BODYCOMBAT', 'type' => 'Cardio', 'duration_minutes' => 60, 'max_capacity' => 25, 'description' => 'Campuran gerakan martial arts yang intens dan penuh energi. (Hard)'],
    ['name' => 'HATHA YOGA', 'type' => 'Yoga', 'duration_minutes' => 60, 'max_capacity' => 15, 'description' => 'Yoga dasar yang fokus pada keseimbangan postur dan pernapasan. (Medium)'],
    ['name' => 'POWER SCULPT', 'type' => 'Strength', 'duration_minutes' => 45, 'max_capacity' => 20, 'description' => 'Latihan kekuatan intensif untuk membentuk otot. (Medium)'],
    ['name' => 'HIIT DYNAMICS', 'type' => 'HIIT', 'duration_minutes' => 30, 'max_capacity' => 15, 'description' => 'Latihan interval intensitas tinggi untuk membakar lemak dengan cepat. (Hard)'],
    ['name' => 'SPINNING', 'type' => 'Cardio', 'duration_minutes' => 45, 'max_capacity' => 25, 'description' => 'Kelas sepeda statis dengan musik energik. (Medium)'],
    ['name' => 'PILATES FLOW', 'type' => 'Yoga', 'duration_minutes' => 50, 'max_capacity' => 15, 'description' => 'Latihan yang berfokus pada kekuatan inti tubuh dan fleksibilitas. (Easy)'],
];

$trainers = ['Coach Budi', 'Coach Rina', 'Coach Maya', 'Coach Andi'];
$times = ['07:00', '08:00', '09:00', '10:00', '16:00', '17:00', '18:00'];

foreach ($classes as $classData) {
    $gymClass = \App\Models\GymClass::updateOrCreate(
        ['name' => $classData['name']],
        $classData
    );

    for ($i = 0; $i < 6; $i++) {
        $daysToAdd = rand(0, 13); // distribute over 2 weeks
        $timeStr = $times[array_rand($times)];
        $startTime = now()->startOfWeek()->addDays($daysToAdd)->setTimeFromTimeString($timeStr . ':00');

        $exists = \App\Models\ClassSchedule::where('gym_class_id', $gymClass->id)
                    ->where('start_time', $startTime)->exists();
                    
        if (!$exists) {
            \App\Models\ClassSchedule::create([
                'gym_class_id' => $gymClass->id,
                'trainer_name' => $trainers[array_rand($trainers)],
                'start_time' => $startTime,
                'current_bookings' => rand(0, $gymClass->max_capacity - 2),
            ]);
        }
    }
}
echo "Seeding completed!\n";
