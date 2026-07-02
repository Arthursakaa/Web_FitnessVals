<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\GymClass;
use App\Models\ClassSchedule;
use App\Models\ClassBooking;

// Clear existing schedules and bookings
ClassBooking::truncate();
ClassSchedule::truncate();

// Define a logical weekly timetable mapping
$timetable = [
    'Monday' => [
        '07:00' => ['HATHA YOGA', 'Coach Maya'],
        '08:00' => ['SPINNING', 'Coach Budi'],
        '16:00' => ['BODYPUMP', 'Coach Andi'],
        '17:00' => ['ZUMBA', 'Coach Rina'],
        '18:00' => ['BODYCOMBAT', 'Coach Budi']
    ],
    'Tuesday' => [
        '07:00' => ['PILATES FLOW', 'Coach Maya'],
        '09:00' => ['POWER SCULPT', 'Coach Andi'],
        '16:00' => ['HIIT DYNAMICS', 'Coach Rina'],
        '17:00' => ['BODYPUMP', 'Coach Andi'],
        '18:00' => ['SPINNING', 'Coach Budi']
    ],
    'Wednesday' => [
        '07:00' => ['HATHA YOGA', 'Coach Maya'],
        '08:00' => ['ZUMBA', 'Coach Rina'],
        '16:00' => ['BODYCOMBAT', 'Coach Budi'],
        '17:00' => ['POWER SCULPT', 'Coach Andi'],
        '18:00' => ['HIIT DYNAMICS', 'Coach Rina']
    ],
    'Thursday' => [
        '07:00' => ['PILATES FLOW', 'Coach Maya'],
        '09:00' => ['SPINNING', 'Coach Budi'],
        '16:00' => ['BODYPUMP', 'Coach Andi'],
        '17:00' => ['ZUMBA', 'Coach Rina'],
        '18:00' => ['BODYCOMBAT', 'Coach Budi']
    ],
    'Friday' => [
        '07:00' => ['HATHA YOGA', 'Coach Maya'],
        '08:00' => ['POWER SCULPT', 'Coach Andi'],
        '16:00' => ['HIIT DYNAMICS', 'Coach Rina'],
        '17:00' => ['SPINNING', 'Coach Budi'],
        '18:00' => ['ZUMBA', 'Coach Rina']
    ],
    'Saturday' => [
        '07:00' => ['SPINNING', 'Coach Budi'],
        '08:00' => ['BODYPUMP', 'Coach Andi'],
        '09:00' => ['ZUMBA', 'Coach Rina'],
        '10:00' => ['HATHA YOGA', 'Coach Maya']
    ],
    'Sunday' => [
        '07:00' => ['PILATES FLOW', 'Coach Maya'],
        '08:00' => ['POWER SCULPT', 'Coach Andi'],
        '09:00' => ['HIIT DYNAMICS', 'Coach Rina'],
        '10:00' => ['BODYCOMBAT', 'Coach Budi']
    ]
];

$classesByName = GymClass::all()->keyBy('name');

// Seed for this week and next week
$startDates = [now()->startOfWeek(), now()->startOfWeek()->addWeek()];

foreach ($startDates as $weekStart) {
    foreach ($timetable as $dayName => $slots) {
        $date = $weekStart->copy()->modify($dayName);
        
        foreach ($slots as $time => $info) {
            $className = $info[0];
            $trainer = $info[1];
            
            $gymClass = $classesByName->get($className);
            if ($gymClass) {
                ClassSchedule::create([
                    'gym_class_id' => $gymClass->id,
                    'trainer_name' => $trainer,
                    'start_time' => $date->copy()->setTimeFromTimeString($time . ':00'),
                    'current_bookings' => rand(2, max(2, $gymClass->max_capacity - 5)),
                ]);
            }
        }
    }
}

echo "Logical schedule seeding completed!\n";
