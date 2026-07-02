@extends('layouts.public')
@section('title', 'Tentang Kami')

@section('content')
{{-- HERO --}}
<section class="page-hero">
    <div class="container">
        <div class="grid-2" style="align-items:center;gap:var(--space-12);">
            <div data-aos="fade-right">
                <div class="section-tag" style="color:var(--color-cta);"><i class="bi bi-info-circle"></i> TENTANG KAMI</div>
                <h1>Kenalan lebih dekat<br>dengan <span style="color:var(--color-cta);">Fitness Vals</span></h1>
                <p>Gym premium & terjangkau di Kota Malang. Kami hadir untuk memberikan kemudahan akses ke hidup sehat bagi lebih banyak orang.</p>
                <div style="margin-top:var(--space-6);display:flex;gap:var(--space-4);">
                    <a href="#cerita" class="btn btn-outline" style="color:#fff;border-color:rgba(255,255,255,0.2);">Cerita Kami <i class="bi bi-arrow-down" style="margin-left:4px;"></i></a>
                </div>
            </div>
            <div data-aos="fade-left" style="position:relative;">
                <img src="https://images.unsplash.com/photo-1540497077202-7c8a3999166f?w=600&h=450&fit=crop" alt="Gym" style="border-radius:var(--radius-2xl);box-shadow:var(--shadow-xl);transform:rotate(2deg);">
                <div style="position:absolute;bottom:-20px;left:-20px;background:var(--glass-bg);backdrop-filter:var(--glass-blur);padding:var(--space-4);border-radius:var(--radius-xl);border:1px solid var(--glass-border);color:var(--color-text);font-weight:var(--fw-bold);display:flex;align-items:center;gap:var(--space-3);">
                    <div style="width:40px;height:40px;border-radius:50%;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:20px;"><i class="bi bi-star-fill"></i></div>
                    Premium Facility
                </div>
            </div>
        </div>
    </div>
</section>

{{-- STORY --}}
<section class="section" id="cerita">
    <div class="container">
        <div class="grid-2" style="gap:var(--space-12);align-items:center;">
            <div data-aos="fade-right">
                <div class="section-tag"><i class="bi bi-heart-fill"></i> CERITA KAMI</div>
                <h2 style="font-size:var(--fs-3xl);margin-bottom:var(--space-5);">Hi, kenalin kami Fitness Vals!</h2>
                <p style="color:var(--color-text-secondary);line-height:1.8;margin-bottom:var(--space-4);">{!! nl2br(e(\App\Models\Setting::getVal('about_history', 'Kami adalah gym premium & terjangkau di Kota Malang. Sekarang ini, kami ada di 1 lokasi tersebar di Kota Malang dan akan terus bertambah agar lebih dekat dengan kamu.'))) !!}</p>
                <p style="color:var(--color-primary);font-weight:var(--fw-bold);font-size:var(--fs-lg);">Let's #GetFitWithUs! <i class="bi bi-trophy-fill"></i></p>
            </div>
            <div data-aos="fade-left">
                <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=600&h=500&fit=crop" alt="Fitness Vals Gym" style="width:100%;border-radius:var(--radius-2xl);box-shadow:var(--shadow-xl);">
            </div>
        </div>
    </div>
</section>

{{-- STATS --}}
<section class="section" style="background:var(--color-bg-alt);">
    <div class="container">
        <div class="stats-grid">
            @foreach([
                ['num'=>'150','title'=>'Member Aktif','desc'=>'Bergabung di Kota Malang'],
                ['num'=>'1','title'=>'Lokasi Premium','desc'=>'Di Kota Malang'],
                ['num'=>'8','title'=>'Variasi Kelas','desc'=>'Gratis untuk member Pro & Elite'],
                ['num'=>'15+','title'=>'Trainer Pro','desc'=>'Bersertifikasi profesional'],
            ] as $i => $s)
            <div class="stats-card" data-aos="fade-up" data-aos-delay="{{ $i*100 }}">
                <div class="number">{{ $s['num'] }}</div>
                <div class="title">{{ $s['title'] }}</div>
                <div class="desc">{{ $s['desc'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- VISION MISSION --}}
<section class="section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <div class="section-tag"><i class="bi bi-compass"></i> VISI & MISI</div>
            <h2>Yang Mendorong Kami Maju</h2>
        </div>
        <div class="grid-3" data-aos="fade-up">
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-eye-fill" style="font-size:24px;color:var(--color-primary);"></i></div>
                <h4>Visi</h4>
                <p>{{ \App\Models\Setting::getVal('about_vision', 'Menjadi platform kebugaran terdepan dan paling terjangkau di Indonesia, menjadikan olahraga sebagai gaya hidup bagi seluruh masyarakat.') }}</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-bullseye" style="font-size:24px;color:var(--color-primary);"></i></div>
                <h4>Misi</h4>
                <p>{{ \App\Models\Setting::getVal('about_mission', 'Menyediakan fasilitas kelas dunia, teknologi canggih, dan pendampingan personal trainer berkualitas dengan harga yang terjangkau.') }}</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="bi bi-heart-fill" style="font-size:24px;color:var(--color-primary);"></i></div>
                <h4>Nilai</h4>
                <p>Inklusivitas, inovasi, dan kesehatan. Kami percaya setiap orang berhak mendapatkan akses ke fasilitas kebugaran premium.</p>
            </div>
        </div>
    </div>
</section>

{{-- FACILITIES BENTO --}}
<section class="section" style="background:var(--color-bg-alt);">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <div class="section-tag"><i class="bi bi-buildings"></i> FASILITAS</div>
            <h2>Fasilitas Premium, Harga Terjangkau</h2>
        </div>
        <div class="bento-grid" data-aos="fade-up">
            <div class="bento-item span-2">
                <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=800&h=400&fit=crop" alt="">
                <div class="overlay"><h4>Main Gym Floor</h4><p>200+ alat premium dari brand internasional</p></div>
            </div>
            <div class="bento-item">
                <img src="https://images.unsplash.com/photo-1540497077202-7c8a3999166f?w=400&h=400&fit=crop" alt="">
                <div class="overlay"><h4>Cardio Zone</h4><p>Area cardio dengan TV & WiFi</p></div>
            </div>
            <div class="bento-item">
                <img src="https://images.unsplash.com/photo-1571902943202-507ec2618e8f?w=400&h=400&fit=crop" alt="">
                <div class="overlay"><h4>Group Class Studio</h4><p>Studio kelas dengan sound system premium</p></div>
            </div>
            <div class="bento-item">
                <img src="https://images.unsplash.com/photo-1558618666-fcd25c85f82e?w=400&h=400&fit=crop" alt="">
                <div class="overlay"><h4>Locker & Shower</h4><p>Fasilitas lengkap dan bersih</p></div>
            </div>
            <div class="bento-item">
                <img src="https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=400&h=400&fit=crop" alt="">
                <div class="overlay"><h4>Free Weight Area</h4><p>Lengkap dari 2kg - 50kg</p></div>
            </div>
            <div class="bento-item span-2">
                <img src="https://images.unsplash.com/photo-1518611012118-696072aa579a?w=800&h=400&fit=crop" alt="">
                <div class="overlay"><h4>Yoga & Pilates Studio</h4><p>Ruangan khusus dengan pencahayaan alami</p></div>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="cta-section">
    <div class="container" data-aos="fade-up">
        <h2>Siap Memulai Perjalanan Fitnessmu?</h2>
        <p>Bergabung dengan 150 member yang sudah merasakan transformasi.</p>
        <a href="{{ route('register') }}" class="btn btn-cta btn-lg">Klaim Free Trial →</a>
    </div>
</section>
@endsection
