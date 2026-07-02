@extends('layouts.public')
@section('title', 'Detail Kelas: ' . $class->name)
@section('content')
<section class="page-hero">
    <div class="container">
        <a href="{{ route('classes') }}" class="btn btn-outline-white btn-sm" style="margin-bottom:var(--space-4); border-color:rgba(255,255,255,0.3); color:#fff;"><i class="bi bi-arrow-left"></i> Kembali ke Daftar Kelas</a>
        <h1>{{ $class->name }}</h1>
        <p>{{ $class->type }} — {{ $class->difficulty }} Level</p>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="grid-2" style="gap:var(--space-10); align-items:start;">
            <div>
                <img src="{{ $class->image }}" alt="{{ $class->name }}" style="border-radius:var(--radius-xl);width:100%;height:auto;aspect-ratio:16/9;object-fit:cover;margin-bottom:var(--space-6);box-shadow:var(--shadow-md);">
                <h3 style="margin-bottom:var(--space-4);">Tentang Kelas Ini</h3>
                <p class="text-muted" style="line-height:1.8;margin-bottom:var(--space-6);">{{ $class->description }}</p>
                <h4 style="margin-bottom:var(--space-3);">Manfaat</h4>
                <ul style="color:var(--color-text-secondary);font-size:var(--fs-sm);line-height:2;">
                    <li><i class="bi bi-check-lg" style="color:var(--color-primary);margin-right:8px;"></i> Meningkatkan kapasitas kardiovaskular</li>
                    <li><i class="bi bi-check-lg" style="color:var(--color-primary);margin-right:8px;"></i> Membakar kalori secara optimal</li>
                    <li><i class="bi bi-check-lg" style="color:var(--color-primary);margin-right:8px;"></i> Meningkatkan kebugaran tubuh secara menyeluruh</li>
                    <li><i class="bi bi-check-lg" style="color:var(--color-primary);margin-right:8px;"></i> Melatih disiplin dan konsistensi</li>
                </ul>
            </div>
            <div>
                <div class="card" style="margin-bottom:var(--space-6);">
                    <h4 style="margin-bottom:var(--space-4);"><i class="bi bi-clipboard-check"></i> Detail Kelas</h4>
                    <div style="font-size:var(--fs-sm);">
                        <div class="flex-between" style="padding:10px 0;border-bottom:1px solid var(--color-border-light);"><span class="text-muted">Durasi</span><strong>{{ $class->duration_minutes }} Menit</strong></div>
                        <div class="flex-between" style="padding:10px 0;border-bottom:1px solid var(--color-border-light);"><span class="text-muted">Kalori</span><strong>~{{ $class->duration_minutes * 10 }} Cal</strong></div>
                        <div class="flex-between" style="padding:10px 0;border-bottom:1px solid var(--color-border-light);"><span class="text-muted">Level</span><span class="badge badge-accent">{{ $class->difficulty }}</span></div>
                        <div class="flex-between" style="padding:10px 0;"><span class="text-muted">Kapasitas Maksimal</span><strong>{{ $class->max_capacity }} orang</strong></div>
                    </div>
                </div>
                <div class="card" style="margin-bottom:var(--space-6);">
                    <h4 style="margin-bottom:var(--space-4);"><i class="bi bi-calendar3"></i> Jadwal Mingguan</h4>
                    <p class="text-muted" style="font-size:var(--fs-sm); line-height:1.6;">Jadwal detail dan ketersediaan instruktur dapat dilihat langsung pada dashboard member. Silakan daftar atau login untuk melihat slot yang tersedia dan melakukan booking.</p>
                </div>
                <a href="{{ route('register') }}" class="btn btn-primary btn-block btn-lg">Book Session →</a>
            </div>
        </div>
    </div>
</section>
@endsection
