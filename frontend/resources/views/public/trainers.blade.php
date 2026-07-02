@extends('layouts.public')
@section('title', 'Personal Trainer')

@section('content')
<section class="page-hero">
    <div class="container" data-aos="fade-up">
        <div class="section-tag" style="color:var(--color-cta);"><i class="bi bi-star-fill"></i> PERSONAL TRAINER</div>
        <h1>Latihan lebih efektif<br>dengan Personal Trainer</h1>
        <p>Pelatih bersertifikasi internasional siap membantu kamu mencapai target fitness.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="flex-center" style="gap:var(--space-2);margin-bottom:var(--space-10);flex-wrap:wrap;" data-aos="fade-up">
            <button class="btn btn-primary btn-sm filter-btn" data-filter="all">Semua</button>
            <button class="btn btn-ghost btn-sm filter-btn" data-filter="strength">Strength</button>
            <button class="btn btn-ghost btn-sm filter-btn" data-filter="fat">Fat Loss</button>
            <button class="btn btn-ghost btn-sm filter-btn" data-filter="yoga">Yoga</button>
            <button class="btn btn-ghost btn-sm filter-btn" data-filter="athletic">Athletic</button>
        </div>
        <div class="grid-3" data-aos="fade-up">
            @foreach($trainers ?? [] as $t)
            <div class="card trainer-item" data-specialty="{{ strtolower($t->specialty) }}" style="text-align:center;padding:var(--space-8);">
                <div style="width:100px;height:100px;border-radius:50%;overflow:hidden;margin:0 auto var(--space-4);border:3px solid var(--color-accent-light);">
                    <img src="{{ Str::startsWith($t->photo_url, 'http') ? $t->photo_url : asset('storage/' . $t->photo_url) }}" onerror="this.src='/images/placeholder.jpg'" alt="{{ $t->name }}" style="width:100%;height:100%;object-fit:cover;">
                </div>
                <h4 style="margin-bottom:var(--space-1);">{{ $t->name }}</h4>
                <p style="color:var(--color-primary);font-size:var(--fs-sm);font-weight:var(--fw-semibold);margin-bottom:var(--space-3);">{{ $t->specialty }}</p>
                <div style="display:flex;justify-content:center;gap:var(--space-4);margin-bottom:var(--space-4);font-size:var(--fs-xs);">
                    <span><i class="bi bi-star-fill text-warning"></i> {{ $t->rating }}</span>
                    <span style="color:var(--color-primary);font-weight:var(--fw-bold);">Rp{{ number_format($t->price_per_session, 0, ',', '.') }}/sesi</span>
                </div>
                <div style="display:flex;gap:var(--space-2);">
                    <a href="{{ route('trainer.detail', $t->id) }}" class="btn btn-outline btn-sm btn-block" style="font-size:var(--fs-xs);">Lihat Profil</a>
                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $t->whatsapp ?? '')) }}" target="_blank" class="btn btn-primary btn-sm btn-block" style="font-size:var(--fs-xs);"><i class="bi bi-whatsapp"></i> WhatsApp</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- WHY PT --}}
<section class="section" style="background:var(--color-bg-alt);">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>Kenapa Butuh Personal Trainer?</h2>
        </div>
        <div class="grid-4" data-aos="fade-up">
            @foreach([
                ['icon'=>'bi-bullseye','title'=>'Program Personal','desc'=>'Program latihan disesuaikan dengan goal kamu.'],
                ['icon'=>'bi-shield-check','title'=>'Aman & Efektif','desc'=>'Form & teknik yang benar, hindari cedera.'],
                ['icon'=>'bi-graph-up-arrow','title'=>'Hasil Lebih Cepat','desc'=>'3x lebih cepat dibanding latihan sendiri.'],
                ['icon'=>'bi-heart-fill','title'=>'Motivasi Konsisten','desc'=>'Accountability partner untuk konsistensi.'],
            ] as $w)
            <div class="feature-card">
                <div class="feature-icon"><i class="{{ $w['icon'] }}" style="font-size:24px;color:var(--color-primary);"></i></div>
                <h4>{{ $w['title'] }}</h4>
                <p>{{ $w['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container" data-aos="fade-up">
        <h2>Konsultasi Gratis dengan Trainer</h2>
        <p>Daftar sekarang dan dapatkan sesi konsultasi gratis dengan personal trainer kami.</p>
        <a href="{{ route('register') }}" class="btn btn-cta btn-lg">Klaim Free Trial →</a>
    </div>
</section>
@endsection

@section('scripts')
<script>
    const filterBtns = document.querySelectorAll('.filter-btn');
    const trainerItems = document.querySelectorAll('.trainer-item');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.replace('btn-primary', 'btn-ghost'));
            btn.classList.replace('btn-ghost', 'btn-primary');
            
            const filterValue = btn.getAttribute('data-filter');
            trainerItems.forEach(item => {
                if(filterValue === 'all' || item.getAttribute('data-specialty').includes(filterValue)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection
