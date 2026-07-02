<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin Account
        User::factory()->create([
            'name' => 'System Administrator',
            'email' => 'admin@fitnessvals.com',
            'password' => \Hash::make('admin123'),
            'role' => 'admin',
        ]);

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'member',
        ]);

        // Create some gym classes
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

        $availableTimes = [7, 8, 9, 10, 16, 17, 18];
        $trainers = ['Coach Budi', 'Coach Rina', 'Coach Andi', 'Coach Sarah'];

        foreach ($classes as $index => $classData) {
            $gymClass = \App\Models\GymClass::create($classData);

            // Jadwalkan setiap kelas 3 kali seminggu, untuk minggu lalu, minggu ini, dan minggu depan
            $weekOffsets = [-1, 0, 1];
            foreach ($weekOffsets as $wOffset) {
                for ($i = 0; $i < 3; $i++) {
                    $dayOffset = ($index + $i * 2) % 7; 
                    $timeHour = $availableTimes[($index + $i) % count($availableTimes)];
                    
                    \App\Models\ClassSchedule::create([
                        'gym_class_id' => $gymClass->id,
                        'trainer_name' => $trainers[array_rand($trainers)],
                        'start_time' => now()->startOfWeek()->addWeeks($wOffset)->addDays($dayOffset)->setTime($timeHour, 0),
                        'current_bookings' => rand(2, $gymClass['max_capacity'] - 2),
                    ]);
                }
            }
        }
    }
}
