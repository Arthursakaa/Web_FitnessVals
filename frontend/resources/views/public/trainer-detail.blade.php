@extends('layouts.public')
@section('title', 'Profil ' . $trainer->name)
@section('content')
<section class="page-hero">
    <div class="container">
        <a href="{{ route('trainers') }}" class="btn btn-outline-white btn-sm" style="margin-bottom:var(--space-4); border-color:rgba(255,255,255,0.3); color:#fff;"><i class="bi bi-arrow-left"></i> Kembali ke Daftar Trainer</a>
        <h1>{{ $trainer->name }}</h1>
        <p>{{ $trainer->specialty }} Specialist</p>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="grid-2" style="gap:var(--space-10);align-items:start;">
<div>
    <div class="card text-center" style="margin-bottom:var(--space-6);padding:var(--space-10);">
        <div class="avatar" style="width:150px;height:150px;margin:0 auto var(--space-5);border:4px solid var(--color-accent-light);border-radius:50%;overflow:hidden;box-shadow:var(--shadow-md);">
            <img src="{{ Str::startsWith($trainer->photo_url, 'http') ? $trainer->photo_url : asset('storage/' . $trainer->photo_url) }}" onerror="this.src='/images/placeholder.jpg'" alt="{{ $trainer->name }}" style="width:100%;height:100%;object-fit:cover;">
        </div>
        <h3>{{ $trainer->name }}</h3>
        <p style="color:var(--color-primary);font-weight:600;">{{ $trainer->specialty }}</p>
        <p class="text-muted" style="font-size:var(--fs-sm);margin:var(--space-2) 0;">
            <i class="bi bi-star-fill text-warning"></i> {{ $trainer->rating }} Rating • <i class="bi bi-people-fill"></i> {{ rand(50, 200) }}+ Member Ditangani
        </p>
        <div style="display:flex;gap:var(--space-3);margin-top:var(--space-5);justify-content:center;flex-wrap:wrap;">
            <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $trainer->whatsapp ?? '')) }}" target="_blank" class="btn btn-primary btn-block"><i class="bi bi-whatsapp"></i> Hubungi via WhatsApp</a>
        </div>
    </div>
    <div class="card">
        <h4 style="margin-bottom:var(--space-3);">Spesialisasi</h4>
        <div style="display:flex;flex-wrap:wrap;gap:var(--space-2);">
            @foreach(explode('&', str_replace('dan', '&', $trainer->specialty)) as $s)
            <span class="badge badge-accent">{{ trim($s) }}</span>
            @endforeach
            <span class="badge badge-accent">Fitness Goal</span>
            <span class="badge badge-accent">Personalized Plan</span>
        </div>
    </div>
</div>
<div>
    <div class="card" style="margin-bottom:var(--space-6);">
        <h4 style="margin-bottom:var(--space-4);">Bio</h4>
        <p class="text-muted" style="line-height:1.8;">{{ $trainer->bio ?? 'Belum ada informasi bio untuk pelatih ini.' }}</p>
    </div>
    
    <div class="card" style="margin-bottom:var(--space-6);">
        <h4 style="margin-bottom:var(--space-4);">Jadwal Ketersediaan</h4>
        <div style="font-size:var(--fs-sm);">
            @if($trainer->availability)
                <div class="flex-between" style="padding:8px 0;border-bottom:1px solid var(--color-border-light);">
                    <span>Tersedia pada hari:</span>
                    <strong>{{ $trainer->availability }}</strong>
                </div>
                <p class="text-muted" style="margin-top:var(--space-2); font-size:var(--fs-xs);">*Waktu pasti bisa disesuaikan saat melakukan booking sesi melalui dashboard.</p>
            @else
                <p class="text-muted">Jadwal belum diatur. Silakan hubungi via WhatsApp untuk memastikan ketersediaan.</p>
            @endif
        </div>
    </div>
    <div class="card">
        <h4 style="margin-bottom:var(--space-4);">Paket Harga</h4>
        <div style="font-size:var(--fs-sm);">
            <div class="flex-between" style="padding:10px 0;border-bottom:1px solid var(--color-border-light);"><span>Single Session</span><strong style="color:var(--color-primary);">Rp{{ number_format($trainer->price_per_session, 0, ',', '.') }}</strong></div>
            <div class="flex-between" style="padding:10px 0;border-bottom:1px solid var(--color-border-light);"><span>5 Sessions Pack</span><strong style="color:var(--color-primary);">Rp{{ number_format($trainer->price_per_session * 5, 0, ',', '.') }}</strong></div>
            <div class="flex-between" style="padding:10px 0;"><span>10 Sessions Pack <span class="badge" style="background:#10B981;color:#fff;font-size:10px;">Best Value</span></span><strong style="color:var(--color-primary);">Rp{{ number_format($trainer->price_per_session * 9, 0, ',', '.') }}</strong></div>
        </div>
    </div>
</div>
</div></div></section>
@endsection
