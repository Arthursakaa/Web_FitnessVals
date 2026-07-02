<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Trainer;

Trainer::truncate();

$trainers = [
    [
        'name' => 'Coach Rina',
        'specialty' => 'Strength & Conditioning',
        'bio' => 'Mantan atlet nasional dengan pengalaman 8 tahun di bidang angkat berat dan strength conditioning. Bersertifikat ISSA.',
        'whatsapp' => '6281234567890',
        'rating' => 4.9,
        'price_per_session' => 350000,
        'availability' => 'Sen, Rab, Jum',
        'photo_url' => 'https://ui-avatars.com/api/?name=Coach+Rina&size=200&background=FF6B2C&color=fff'
    ],
    [
        'name' => 'Coach Budi',
        'specialty' => 'HIIT & Fat Loss',
        'bio' => 'Spesialis penurunan berat badan ekstrim dan HIIT. Sangat enerjik dan siap mendorong batas kemampuanmu.',
        'whatsapp' => '6281234567891',
        'rating' => 4.8,
        'price_per_session' => 300000,
        'availability' => 'Sel, Kam, Sab',
        'photo_url' => 'https://ui-avatars.com/api/?name=Coach+Budi&size=200&background=FF6B2C&color=fff'
    ],
    [
        'name' => 'Coach Maya',
        'specialty' => 'Yoga & Mobility',
        'bio' => 'Instruktur Yoga bersertifikat Yoga Alliance 500-RYT. Ahli dalam pemulihan cedera dan mobilitas fleksibilitas.',
        'whatsapp' => '6281234567892',
        'rating' => 5.0,
        'price_per_session' => 275000,
        'availability' => 'Sen - Sab',
        'photo_url' => 'https://ui-avatars.com/api/?name=Coach+Maya&size=200&background=FF6B2C&color=fff'
    ],
    [
        'name' => 'Coach Deni',
        'specialty' => 'Athletic Performance',
        'bio' => 'Pelatih fisik untuk atlit profesional. Membantumu meningkatkan kelincahan, kecepatan, dan explosiveness.',
        'whatsapp' => '6281234567893',
        'rating' => 4.9,
        'price_per_session' => 400000,
        'availability' => 'Sen, Rab, Jum, Sab',
        'photo_url' => 'https://ui-avatars.com/api/?name=Coach+Deni&size=200&background=FF6B2C&color=fff'
    ]
];

foreach ($trainers as $data) {
    Trainer::create($data);
}

echo "Trainers seeded successfully!\n";
