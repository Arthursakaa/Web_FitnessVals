@extends('layouts.dashboard')
@section('title', 'Personal Trainer')
@section('page_title', 'Personal Trainer')

@section('content')
<div class="flex-gap" id="tour-step-1" style="margin-bottom:var(--space-6);flex-wrap:wrap;">
    <button class="btn btn-primary btn-sm filter-btn" data-filter="all">Semua</button>
    <button class="btn btn-ghost btn-sm filter-btn" data-filter="strength">Strength</button>
    <button class="btn btn-ghost btn-sm filter-btn" data-filter="fat loss">Fat Loss</button>
    <button class="btn btn-ghost btn-sm filter-btn" data-filter="yoga">Yoga</button>
    <button class="btn btn-ghost btn-sm filter-btn" data-filter="athletic">Athletic</button>
</div>

<div class="grid-2" id="trainerGrid" style="gap:var(--space-5);margin-bottom:var(--space-6);">
    @foreach($trainers as $t)
    <div class="card trainer-card" data-specialty="{{ strtolower($t->specialty) }}" style="display:flex;gap:var(--space-5);">
        <div class="avatar avatar-xl" style="flex-shrink:0;border:3px solid var(--color-accent-light);">
            <img src="{{ $t->photo_url }}" alt="{{ $t->name }}">
        </div>
        <div style="flex:1;">
            <h4>{{ $t->name }}</h4>
            <p style="color:var(--color-primary);font-size:var(--fs-sm);font-weight:600;">{{ $t->specialty }}</p>
            <p class="text-muted" style="font-size:var(--fs-xs);margin-bottom:var(--space-2); line-height: 1.4;">{{ $t->bio }}</p>
            <div style="font-size:var(--fs-xs);color:var(--color-text-muted);margin:var(--space-2) 0;">
                <i class="bi bi-calendar3"></i> {{ $t->availability }} &nbsp; 
                <i class="bi bi-star-fill" style="color:#FFD700;"></i> {{ number_format($t->rating, 1) }} &nbsp; 
                <i class="bi bi-cash-stack"></i> Rp{{ number_format($t->price_per_session, 0, ',', '.') }}/sesi
            </div>
            <div style="display:flex;gap:var(--space-2);margin-top:var(--space-3);">
                <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $t->whatsapp ?? '')) }}" target="_blank" class="btn btn-primary btn-sm" style="font-size:var(--fs-xs); width:100%;"><i class="bi bi-whatsapp"></i> Hubungi via WhatsApp</a>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- WHATSAPP BOOKING GUIDE --}}
<div class="card" id="tour-step-3" style="border-left:4px solid var(--color-primary); background:var(--color-bg-alt);">
    <h4 style="margin-bottom:var(--space-3); display:flex; align-items:center; gap:var(--space-2);">
        <i class="bi bi-info-circle-fill" style="color:var(--color-primary);"></i> Panduan Booking Trainer
    </h4>
    <p class="text-muted" style="font-size:var(--fs-sm); margin-bottom:var(--space-4);">Untuk memberikan pengalaman yang lebih personal dan fleksibel, sistem booking kini dilakukan langsung dengan pelatih Anda.</p>
    
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:var(--space-4);">
        <div style="background:#fff; padding:var(--space-3); border-radius:var(--radius-md); box-shadow:var(--shadow-sm);">
            <strong style="color:var(--color-primary); font-size:var(--fs-lg);">1</strong>
            <h5 style="margin:var(--space-1) 0; font-size:var(--fs-sm);">Pilih Trainer</h5>
            <p class="text-muted" style="font-size:var(--fs-xs); margin:0;">Cari trainer yang memiliki spesialisasi sesuai dengan target *fitness* Anda (misal: Fat Loss, Muscle Gain).</p>
        </div>
        <div style="background:#fff; padding:var(--space-3); border-radius:var(--radius-md); box-shadow:var(--shadow-sm);">
            <strong style="color:var(--color-primary); font-size:var(--fs-lg);">2</strong>
            <h5 style="margin:var(--space-1) 0; font-size:var(--fs-sm);">Hubungi via WhatsApp</h5>
            <p class="text-muted" style="font-size:var(--fs-xs); margin:0;">Klik tombol WhatsApp pada profil trainer untuk mulai mengobrol secara langsung.</p>
        </div>
        <div style="background:#fff; padding:var(--space-3); border-radius:var(--radius-md); box-shadow:var(--shadow-sm);">
            <strong style="color:var(--color-primary); font-size:var(--fs-lg);">3</strong>
            <h5 style="margin:var(--space-1) 0; font-size:var(--fs-sm);">Diskusi & Sepakat</h5>
            <p class="text-muted" style="font-size:var(--fs-xs); margin:0;">Tentukan jadwal latihan yang cocok untuk kedua belah pihak dan sepakati paket harganya.</p>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script>
    // Filtering Logic
    const filterBtns = document.querySelectorAll('.filter-btn');
    const trainerCards = document.querySelectorAll('.trainer-card');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Update active state
            filterBtns.forEach(b => {
                b.classList.remove('btn-primary');
                b.classList.add('btn-ghost');
            });
            btn.classList.remove('btn-ghost');
            btn.classList.add('btn-primary');

            const filterValue = btn.getAttribute('data-filter');

            trainerCards.forEach(card => {
                const spec = card.getAttribute('data-specialty');
                if (filterValue === 'all' || spec.includes(filterValue)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // ==========================================
    // INTERACTIVE ONBOARDING TOUR (TRAINERS)
    // ==========================================
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof initPageTour === 'function') {
            initPageTour([
                {
                    target: '#tour-step-1',
                    title: 'Filter Spesialisasi 🎯',
                    text: 'Pilih spesialisasi yang sesuai dengan target fitness kamu, mulai dari Fat Loss hingga Yoga.',
                    position: 'bottom'
                },
                {
                    target: '#trainerGrid',
                    title: 'Profil Trainer 👤',
                    text: 'Lihat daftar pelatih profesional kami. Kamu bisa mengecek rating, harga per sesi, dan tombol untuk menghubungi mereka langsung via WhatsApp.',
                    position: 'bottom'
                },
                {
                    target: '#tour-step-3',
                    title: 'Panduan Booking 📱',
                    text: 'Sistem booking kami sekarang sangat fleksibel! Kamu bisa berdiskusi langsung dengan pelatih favoritmu lewat WhatsApp sebelum mulai latihan.',
                    position: 'bottom'
                }
            ], 'fitnessValsTourCompleted_trainers');
        }
    });
</script>
@endsection
