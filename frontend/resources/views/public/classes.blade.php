@extends('layouts.public')
@section('title', 'Classes & Programs')

@section('content')
<section class="page-hero">
    <div class="container" data-aos="fade-up">
        <div class="section-tag" style="color:var(--color-cta);"><i class="bi bi-fire"></i> KELAS FITNESS</div>
        <h1>Ikuti 8 variasi kelas<br>sepuasnya</h1>
        <p>Dibimbing oleh instruktur berpengalaman dan bersertifikasi internasional.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        {{-- CATEGORY TABS --}}
        <div class="flex-center" style="gap:var(--space-2);margin-bottom:var(--space-10);flex-wrap:wrap;" id="class-filters" data-aos="fade-up">
            <button class="btn btn-primary btn-sm filter-btn" data-filter="all">Semua Kelas</button>
            <button class="btn btn-ghost btn-sm filter-btn" data-filter="cardio">Cardio</button>
            <button class="btn btn-ghost btn-sm filter-btn" data-filter="dance">Dance</button>
            <button class="btn btn-ghost btn-sm filter-btn" data-filter="yoga">Yoga</button>
            <button class="btn btn-ghost btn-sm filter-btn" data-filter="strength">Strength</button>
            <button class="btn btn-ghost btn-sm filter-btn" data-filter="hiit">HIIT</button>
        </div>

        {{-- CLASS CARDS --}}
        <div class="class-showcase" id="class-grid" data-aos="fade-up">
            @foreach($classes as $c)
            <a href="{{ route('class.detail', $c->id) }}" class="class-card class-item" data-type="{{ strtolower($c->type) }}" style="text-decoration:none; color:inherit; display:block;">
                <img src="{{ $c->image }}" alt="{{ $c->name }}">
                <div class="card-overlay">
                    <div class="card-badge"><span class="{{ $c->css_class }}"><i class="bi bi-fire"></i> {{ $c->type }}</span></div>
                    <div class="card-info" style="display:flex; flex-direction:column; gap:4px;">
                        <p class="meta">{{ $c->difficulty }} · {{ $c->duration_minutes }} Min</p>
                        <h4 style="margin:0;">{{ $c->name }}</h4>
                        <span class="btn btn-primary btn-sm" style="margin-top:8px; padding:4px 12px; font-size:12px; width:fit-content;">Lihat Detail →</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- INFO SECTION --}}
<section class="section" style="background:var(--color-bg-alt);">
    <div class="container">
        <div class="grid-2" style="gap:var(--space-12);align-items:center;">
            <div data-aos="fade-right">
                <div class="section-tag"><i class="bi bi-stars"></i> KENAPA IKUT KELAS?</div>
                <h2 style="font-size:var(--fs-3xl);margin-bottom:var(--space-5);">Lebih Seru, Lebih Efektif</h2>
                <div style="margin-bottom:var(--space-4);">
                    @foreach([
                        ['icon'=>'<i class="bi bi-people" style="font-size:20px;color:var(--color-primary);"></i>','title'=>'Motivasi dari Grup','desc'=>'Latihan bersama teman-teman membuat kamu lebih termotivasi.'],
                        ['icon'=>'<span style="font-size:20px;">🏅</span>','title'=>'Instruktur Bersertifikat','desc'=>'Semua instruktur kami bersertifikasi internasional.'],
                        ['icon'=>'<span style="font-size:20px;">🎵</span>','title'=>'Musik & Energi','desc'=>'Sound system premium dan playlist yang bikin semangat.'],
                        ['icon'=>'<i class="bi bi-heart-fill" style="font-size:20px;color:var(--color-primary);"></i>','title'=>'Gratis untuk Member','desc'=>'Semua kelas grup gratis untuk member Pro & Elite.'],
                    ] as $b)
                    <div style="display:flex;gap:var(--space-4);margin-bottom:var(--space-5);">
                        <div style="width:48px;height:48px;border-radius:var(--radius-lg);background:var(--color-accent-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;">{!! $b['icon'] !!}</div>
                        <div><h5 style="margin-bottom:2px;">{{ $b['title'] }}</h5><p style="font-size:var(--fs-sm);color:var(--color-text-muted);">{{ $b['desc'] }}</p></div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div data-aos="fade-left">
                <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=600&h=500&fit=crop" alt="Kelas Fitness" style="width:100%;border-radius:var(--radius-2xl);box-shadow:var(--shadow-xl);">
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container" data-aos="fade-up">
        <h2>Mau Coba Kelas Gratis?</h2>
        <p>Daftar sekarang dan coba semua kelas fitness tanpa batas.</p>
        <a href="{{ route('register') }}" class="btn btn-cta btn-lg">Klaim Free Trial →</a>
    </div>
</section>
@endsection

@section('scripts')
<script>
    const filterBtns = document.querySelectorAll('.filter-btn');
    const classItems = document.querySelectorAll('.class-item');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.replace('btn-primary', 'btn-ghost'));
            btn.classList.replace('btn-ghost', 'btn-primary');
            
            const filterValue = btn.getAttribute('data-filter');
            classItems.forEach(item => {
                if(filterValue === 'all' || item.getAttribute('data-type') === filterValue) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection
