@extends('layouts.public')
@section('title', 'Gym Terbaik Kota Malang | Fitness Vals')

@section('content')
@section('styles')
<style>
.hero-slider {
    position: relative;
    width: 100%;
    height: 100vh;
    min-height: 600px;
    margin-top: calc(var(--navbar-height) * -1); /* Full screen including behind navbar */
}
.hero-slider .swiper-slide {
    position: relative;
    display: flex;
    align-items: center;
    background-size: cover;
    background-position: center;
    padding-top: var(--navbar-height);
}
.hero-slider .swiper-slide::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to right, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.5) 40%, rgba(0,0,0,0.1) 100%);
}
.hero-slider .container {
    position: relative;
    z-index: 2;
    padding: 0 var(--space-6);
}
.hero-slider h1 {
    font-size: clamp(3rem, 5vw, 5.5rem);
    font-weight: 900;
    line-height: 1.1;
    color: #fff;
    text-transform: uppercase;
    margin-bottom: var(--space-4);
    letter-spacing: -1px;
}
.hero-slider h1 .text-highlight {
    color: var(--color-primary);
}
.hero-slider p {
    color: rgba(255,255,255,0.75);
    font-size: var(--fs-lg);
    max-width: 550px;
    margin-bottom: var(--space-6);
    line-height: 1.6;
}
.hero-slider .stats {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    color: #fff;
}
.hero-slider .stats strong {
    font-size: var(--fs-2xl);
    color: var(--color-primary);
    font-weight: 900;
}
.swiper-button-next, .swiper-button-prev {
    color: #fff;
    background: rgba(255,255,255,0.1);
    width: 50px;
    height: 50px;
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,0.2);
}
.swiper-button-next::after, .swiper-button-prev::after {
    font-size: 20px;
}
/* Transparent Navbar Styles */
.navbar.navbar-dark .nav-logo { color: #fff !important; }
.navbar.navbar-dark .nav-links a { color: rgba(255,255,255,0.8) !important; }
.navbar.navbar-dark .nav-links a:hover,
.navbar.navbar-dark .nav-links a.active { color: #fff !important; background: rgba(255,255,255,0.15) !important; }
.navbar.navbar-dark .nav-actions .btn-ghost { color: #fff !important; border-color: rgba(255,255,255,0.3); }
.navbar.navbar-dark .nav-mobile-toggle span { background: #fff !important; }
</style>
@endsection

{{-- ═══════════════ HERO ═══════════════ --}}
<section class="hero-slider swiper">
    <div class="swiper-wrapper">
        <!-- Slide 1 -->
        <div class="swiper-slide" style="background-image: url('{{ \App\Models\Setting::getVal('home_hero_image', 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=1920&h=1080&fit=crop') }}');">
            <div class="container" data-aos="fade-right">
                <div style="margin-bottom: 16px; color: #fff; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; font-size: 13px;">SELAMAT DATANG DI FITNESS VALS GYM</div>
                <h1>{!! nl2br(e(\App\Models\Setting::getVal('home_hero_title', "BENTUK TUBUH \nIMPIANMU DIMULAI DI SINI"))) !!}</h1>
                <p>{{ \App\Models\Setting::getVal('home_hero_subtitle', 'Latihan fisik yang terencana di pusat kebugaran modern, didukung dengan peralatan lengkap dan pelatih profesional untuk mencapai versi terbaik dari dirimu.') }}</p>
                <div style="display: flex; align-items: center; gap: 30px; flex-wrap: wrap;">
                    <div style="display: flex; gap: 15px;">
                        <a href="{{ route('register') }}" class="btn btn-cta btn-lg" style="border-radius: 2px;">KLAIM FREE TRIAL</a>
                        <a href="{{ route('classes') }}" class="btn btn-outline-white btn-lg" style="border-radius: 2px; color: #fff; border-color: rgba(255,255,255,0.5);">Lihat Kelas</a>
                    </div>
                    <div class="stats">
                        <strong>{{ $userCount ?? '350' }}+</strong> 
                        <span style="font-size: 14px; font-weight: 500; line-height: 1.2;">Member<br>Aktif</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Slide 2 -->
        <div class="swiper-slide" style="background-image: url('https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=1920&h=1080&fit=crop');">
            <div class="container">
                <div style="margin-bottom: 16px; color: #fff; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; font-size: 13px;">MULAI PERJALANANMU HARI INI</div>
                <h1>DOBRAK <br><span class="text-highlight">BATASMU</span></h1>
                <p>Bergabunglah dengan gym terbaik di Kota Malang dan wujudkan target kebugaranmu bersama komunitas yang suportif dan fasilitas premium.</p>
                <div style="display: flex; align-items: center; gap: 30px; flex-wrap: wrap;">
                    <a href="{{ route('register') }}" class="btn btn-cta btn-lg" style="border-radius: 2px;">KLAIM FREE TRIAL</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Navigation -->
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</section>

{{-- ═══════════════ STATS BAR ═══════════════ --}}
<section class="stats-bar">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>Kenapa Fitness Vals Gym Terbaik buat Kamu?</h2>
        </div>
        <div class="stats-grid">
            @foreach([
                ['num'=>($userCount ?? '350').'+','title'=>'Member Aktif','desc'=>'Dipercaya oleh ribuan orang untuk mendukung fitness journey mereka.'],
                ['num'=>($classCount ?? '30').'+','title'=>'Variasi Kelas','desc'=>'Ikuti kelas fitness gratis setiap hari dengan instruktur berpengalaman.'],
                ['num'=>($trainerCount ?? '15').'+','title'=>'Trainer Bersertifikat','desc'=>'Pelatih profesional yang siap membantu mencapai targetmu.'],
                ['num'=>'200+','title'=>'Alat Premium','desc'=>'Peralatan gym lengkap dan modern dari brand internasional.'],
            ] as $i => $s)
            <div class="stats-card" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                <div class="number">{{ $s['num'] }}</div>
                <div class="title">{{ $s['title'] }}</div>
                <div class="desc">{{ $s['desc'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════ CLASSES SHOWCASE ═══════════════ --}}
<section class="section" style="background:var(--color-bg-alt);">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>Ikuti 8 variasi kelas sepuasnya</h2>
            <p>Dibimbing oleh instruktur berpengalaman dan bersertifikasi internasional.</p>
        </div>
        <div class="flex-center" style="gap:var(--space-2);margin-bottom:var(--space-8);flex-wrap:wrap;" data-aos="fade-up">
            <button class="btn btn-primary btn-sm filter-btn" data-filter="all">Semua Kelas</button>
            <button class="btn btn-ghost btn-sm filter-btn" data-filter="cardio">Cardio</button>
            <button class="btn btn-ghost btn-sm filter-btn" data-filter="yoga">Yoga</button>
            <button class="btn btn-ghost btn-sm filter-btn" data-filter="hiit">HIIT</button>
            <button class="btn btn-ghost btn-sm filter-btn" data-filter="strength">Strength</button>
        </div>
        <div class="class-showcase" data-aos="fade-up" data-aos-delay="100">
            @foreach($gymClasses ?? [] as $c)
            @php
                $catclass = strtolower($c->type);
            @endphp
            <div class="class-card class-item" data-category="{{ $catclass }}">
                <img src="{{ $c->image }}" onerror="this.src='/images/placeholder.jpg'" alt="{{ $c->name }}">
                <div class="card-overlay">
                    <div class="card-badge"><span class="{{ $catclass }}"><i class="bi bi-fire"></i> {{ $c->type }}</span></div>
                    <div class="card-info">
                        <p class="meta">{{ $c->duration_minutes }} Min · Kapasitas {{ $c->max_capacity }}</p>
                        <h4>{{ $c->name }}</h4>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center" style="margin-top:var(--space-10);" data-aos="fade-up">
            <a href="{{ route('classes') }}" class="btn btn-primary">Lihat Semua Kelas →</a>
        </div>
    </div>
</section>

{{-- ═══════════════ BENTO FEATURES ═══════════════ --}}
<section class="section">
    <div class="container">
        <div class="membership-bento">
            <div class="bento-images" data-aos="fade-right">
                <div class="bento-img"><img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=400&h=280&fit=crop" alt=""><div class="img-label"><i class="bi bi-geo-alt"></i> 1 klub di Kota Malang</div></div>
                <div class="bento-img"><img src="https://images.unsplash.com/photo-1518611012118-696072aa579a?w=400&h=280&fit=crop" alt=""><div class="img-label"><i class="bi bi-people"></i> 8 variasi kelas</div></div>
                <div class="bento-img"><img src="https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=400&h=280&fit=crop" alt=""><div class="img-label"><i class="bi bi-award-fill"></i> Personal Trainer bersertifikasi</div></div>
                <div class="bento-img"><img src="https://images.unsplash.com/photo-1540497077202-7c8a3999166f?w=400&h=280&fit=crop" alt=""><div class="img-label"><i class="bi bi-phone"></i> Kemudahan dalam 1 aplikasi</div></div>
            </div>
            <div class="bento-info" data-aos="fade-left">
                <div class="section-tag"><i class="bi bi-stars"></i> MEMBERSHIP</div>
                <h3>Membership mulai dari<br>Rp299K/bulan</h3>
                <p class="text-muted" style="margin-bottom:var(--space-6);line-height:1.7;">Bebas olahraga di klub kami dengan fasilitas premium dan akses kelas Fitness Vals sepuasnya. Nikmati pengalaman fitness tanpa batas.</p>
                <a href="{{ route('membership') }}" class="btn btn-primary">Lihat Paket Membership →</a>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════ TRAINERS ═══════════════ --}}
<section class="section" style="background:var(--color-bg-alt);">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <div class="section-tag"><i class="bi bi-person-badge"></i> PERSONAL TRAINER</div>
            <h2>Latihan lebih efektif dengan Personal Trainer</h2>
            <p>Pelatih bersertifikasi internasional siap membantu kamu mencapai target fitness.</p>
        </div>
        <div class="grid-3" data-aos="fade-up">
            @foreach([
                ['name'=>'Coach Rina Hartanto','spec'=>'Strength & Conditioning','img'=>'photo-1594381898411-846e7d193883'],
                ['name'=>'Coach Budi Santoso','spec'=>'HIIT & Fat Loss','img'=>'photo-1567013127542-490d757e51fc'],
                ['name'=>'Coach Maya Sari','spec'=>'Yoga & Mobility','img'=>'photo-1544367567-0f2fcb009e0b'],
            ] as $t)
            <div class="story-card" style="height:380px;">
                <img src="https://images.unsplash.com/{{ $t['img'] }}?w=500&h=500&fit=crop" alt="{{ $t['name'] }}">
                <div class="story-overlay">
                    <h4>{{ $t['name'] }}</h4>
                    <p>{{ $t['spec'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center" style="margin-top:var(--space-10);" data-aos="fade-up">
            <a href="{{ route('trainers') }}" class="btn btn-primary">Lihat Semua Trainer →</a>
        </div>
    </div>
</section>

{{-- ═══════════════ TESTIMONIALS ═══════════════ --}}
<section class="section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <div class="section-tag"><i class="bi bi-trophy-fill"></i> FIT STORY</div>
            <h2>Kisah sukses di Fitness Vals <i class="bi bi-lightning-charge-fill"></i></h2>
            <p>Tonton perjalanan Member dan Personal Trainer Fitness Vals berhasil dalam mencapai goals mereka.</p>
        </div>
        <div class="grid-3" data-aos="fade-up">
            @foreach([
                ['name'=>'Andi Pratama','story'=>'Berhasil menurunkan 20 Kg dalam 8 bulan!','quote'=>'Awalnya saya ragu bisa konsisten, tapi komunitas dan fasilitas di Fitness Vals luar biasa suportif. Coach-nya juga benar-benar mengarahkan pola latihan dan makan saya.','img'=>'photo-1583454110551-21f2fa2afe61'],
                ['name'=>'Sari Dewi','story'=>'Lebih bugar dan berenergi setiap hari','quote'=>'Kelas Zumba dan Yoganya seru banget, instrukturnya asik dan gak bikin bosen. Sekarang malah merasa aneh kalau sehari aja nggak nge-gym di sini.','img'=>'photo-1571019613454-1cb2f99b2d8b'],
                ['name'=>'Rizky Aditya','story'=>'Massa otot naik 12 Kg dalam setahun','quote'=>'Alat-alatnya sangat lengkap dan selalu dalam kondisi prima. Vibes tempatnya bikin kita otomatis pengen latihan berat. Sangat direkomendasikan untuk powerlifter!','img'=>'photo-1581009146145-b5ef050c2e1e'],
            ] as $s)
            <div class="card" data-aos="fade-up" style="display:flex; flex-direction:column; padding:var(--space-5);">
                <div style="color:#F59E0B; font-size:18px; margin-bottom:var(--space-3);">★★★★★</div>
                <p style="font-style:italic; font-size:var(--fs-md); color:var(--color-text); margin-bottom:var(--space-5); flex:1; line-height:1.6;">"{{ $s['quote'] }}"</p>
                <div style="background:var(--color-bg-alt); padding:var(--space-3); border-radius:var(--radius-md); margin-bottom:var(--space-4); font-size:var(--fs-sm); font-weight:600; color:var(--color-primary); display:flex; align-items:center; gap:8px;">
                    <i class="bi bi-graph-up-arrow"></i> {{ $s['story'] }}
                </div>
                <div style="display:flex; align-items:center; gap:var(--space-3);">
                    <img src="https://images.unsplash.com/{{ $s['img'] }}?w=100&h=100&fit=crop" onerror="this.src='/images/placeholder.jpg'" alt="{{ $s['name'] }}" style="width:50px; height:50px; border-radius:50%; object-fit:cover;">
                    <div>
                        <h4 style="margin:0; font-size:var(--fs-sm);">{{ $s['name'] }}</h4>
                        <span style="font-size:12px; color:var(--color-success);"><i class="bi bi-check-circle-fill"></i> Verified Member</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════ PRICING ═══════════════ --}}
<section class="section" style="background:var(--color-bg-alt);">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <div class="section-tag">👑 MEMBERSHIP</div>
            <h2>Pilih Paket Terbaikmu</h2>
            <p>Investasi terbaik untuk kesehatan dan kebugaran jangka panjangmu.</p>
        </div>
        <div class="grid-3" data-aos="fade-up">
            <div class="pricing-card">
                <div style="font-size:32px;margin-bottom:var(--space-3);"><i class="bi bi-activity"></i></div>
                <h4>Basic</h4>
                <p class="text-muted" style="font-size:var(--fs-sm);margin-bottom:var(--space-4);">Untuk pemula yang ingin mulai</p>
                <div class="price">Rp299K<span>/bulan</span></div>
                <ul class="pricing-features">
                    <li><span class="check"><i class="bi bi-check-lg"></i></span> Akses gym area utama</li>
                    <li><span class="check"><i class="bi bi-check-lg"></i></span> Locker harian</li>
                    <li><span class="check"><i class="bi bi-check-lg"></i></span> Cek BMI & tracking dasar</li>
                    <li class="disabled"><span>—</span> Akses kelas grup</li>
                    <li class="disabled"><span>—</span> Nutrisi tracking</li>
                </ul>
                <a href="{{ route('register') }}" class="btn btn-outline btn-block">Pilih Basic</a>
            </div>
            <div class="pricing-card popular">
                <div style="font-size:32px;margin-bottom:var(--space-3);"><i class="bi bi-lightning-charge-fill"></i></div>
                <h4>Pro</h4>
                <p class="text-muted" style="font-size:var(--fs-sm);margin-bottom:var(--space-4);">Paling diminati member</p>
                <div class="price">Rp549K<span style="color:rgba(255,255,255,0.5);">/bulan</span></div>
                <ul class="pricing-features" style="border-color:rgba(255,255,255,0.1);">
                    <li style="border-color:rgba(255,255,255,0.1);color:rgba(255,255,255,0.85);"><span class="check"><i class="bi bi-check-lg"></i></span> Semua fitur Basic</li>
                    <li style="border-color:rgba(255,255,255,0.1);color:rgba(255,255,255,0.85);"><span class="check"><i class="bi bi-check-lg"></i></span> 8 kelas grup unlimited</li>
                    <li style="border-color:rgba(255,255,255,0.1);color:rgba(255,255,255,0.85);"><span class="check"><i class="bi bi-check-lg"></i></span> AI meal recommendation</li>
                    <li style="border-color:rgba(255,255,255,0.1);color:rgba(255,255,255,0.85);"><span class="check"><i class="bi bi-check-lg"></i></span> Full progress dashboard</li>
                    <li style="border-color:rgba(255,255,255,0.1);color:rgba(255,255,255,0.85);"><span class="check"><i class="bi bi-check-lg"></i></span> Locker permanen</li>
                </ul>
                <a href="{{ route('register') }}" class="btn btn-cta btn-block">Pilih Pro</a>
            </div>
            <div class="pricing-card">
                <div style="font-size:32px;margin-bottom:var(--space-3);">👑</div>
                <h4>Elite</h4>
                <p class="text-muted" style="font-size:var(--fs-sm);margin-bottom:var(--space-4);">Premium tanpa batas</p>
                <div class="price">Rp899K<span>/bulan</span></div>
                <ul class="pricing-features">
                    <li><span class="check"><i class="bi bi-check-lg"></i></span> Semua fitur Pro</li>
                    <li><span class="check"><i class="bi bi-check-lg"></i></span> 4x Personal Trainer/bulan</li>
                    <li><span class="check"><i class="bi bi-check-lg"></i></span> Recovery zone access</li>
                    <li><span class="check"><i class="bi bi-check-lg"></i></span> Guest pass 2x/bulan</li>
                    <li><span class="check"><i class="bi bi-check-lg"></i></span> Konsultasi nutrisi 1-on-1</li>
                </ul>
                <a href="{{ route('register') }}" class="btn btn-dark btn-block">Pilih Elite</a>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════ CTA ═══════════════ --}}
<section class="cta-section">
    <div class="container" data-aos="fade-up">
        <div style="display:inline-block; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.3); color:#fff; padding:6px 12px; border-radius:20px; font-size:13px; font-weight:600; margin-bottom:var(--space-4);"><i class="bi bi-fire" style="color:#F59E0B;"></i> Sisa 12 Slot Free Trial Bulan Ini</div>
        <h2>Klaim Free Trial Gym Premium</h2>
        <p>Bergabunglah dengan 150 member yang sudah merasakan transformasi bersama Fitness Vals.</p>
        <div style="display:flex;gap:var(--space-4);justify-content:center;flex-wrap:wrap;margin-bottom:var(--space-3);">
            <a href="{{ route('register') }}" class="btn btn-cta btn-lg">Klaim Free Trial →</a>
            <a href="{{ route('contact') }}" class="btn btn-outline-white btn-lg"><i class="bi bi-geo-alt"></i> Lihat Lokasi Kami</a>
        </div>
        <p style="font-size:13px; color:rgba(255,255,255,0.6);">*Gratis, tidak perlu kartu kredit. Batalkan kapan saja.</p>
    </div>
</section>
@endsection

@section('scripts')
<script>
window.addEventListener('scroll', function() {
    var ctaBar = document.getElementById('ctaBar');
    if (ctaBar) ctaBar.classList.toggle('visible', window.scrollY > 600);
    
    // Transparent navbar on top for slider
    var navbar = document.getElementById('navbar');
    if (window.scrollY < 50) {
        navbar.style.background = 'transparent';
        navbar.style.borderBottomColor = 'transparent';
        navbar.classList.add('navbar-dark'); // assuming you want white text on top
    } else {
        navbar.style.background = '';
        navbar.style.borderBottomColor = '';
        navbar.classList.remove('navbar-dark');
    }
});

// Trigger scroll initially
window.dispatchEvent(new Event('scroll'));

// Initialize Hero Slider
new Swiper('.hero-slider', {
    loop: true,
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
    }
});

// Filter Logic
const filterBtns = document.querySelectorAll('.filter-btn');
const classItems = document.querySelectorAll('.class-item');

filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        filterBtns.forEach(b => {
            b.classList.remove('btn-primary');
            b.classList.add('btn-ghost');
        });
        btn.classList.remove('btn-ghost');
        btn.classList.add('btn-primary');
        
        const filterValue = btn.getAttribute('data-filter');
        classItems.forEach(item => {
            if(filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});
</script>
@endsection
