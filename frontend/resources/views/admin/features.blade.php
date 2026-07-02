@extends('layouts.admin')
@section('title', 'Pengaturan Fitur')
@section('page_title', 'Pengaturan Fitur')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="card" style="margin-bottom:var(--space-6);border-left:4px solid var(--color-accent);">
    <h5><i class="bi bi-key"></i> Feature Toggle — Kunci untuk Reselling</h5>
    <p class="text-muted" style="font-size:var(--fs-sm);">Aktifkan atau nonaktifkan fitur sesuai kebutuhan gym. Perubahan akan langsung berlaku di website dan dashboard member.</p>
</div>

@foreach([
    ['name'=>'Kelas & Program Gym','desc'=>'Halaman listing kelas dan booking kelas di website publik.','on'=>true],
    ['name'=>'Jadwal Kelas (Member Dashboard)','desc'=>'Tampilan kalender dan jadwal kelas di dashboard member.','on'=>true],
    ['name'=>'Personal Trainer Listing','desc'=>'Halaman daftar personal trainer di website publik.','on'=>true],
    ['name'=>'Booking Sesi Trainer','desc'=>'Fitur booking sesi 1-on-1 dengan personal trainer.','on'=>true],
    ['name'=>'Rekomendasi Makanan (AI)','desc'=>'Fitur rekomendasi makanan berbasis AI di dashboard member.','on'=>true],
    ['name'=>'Log Kalori & Nutrisi','desc'=>'Fitur tracking kalori dan nutrisi harian di dashboard.','on'=>true],
    ['name'=>'Progress Latihan','desc'=>'Tracking progress latihan, berat badan, dan pencapaian.','on'=>true],
    ['name'=>'Cek BMI','desc'=>'Kalkulator BMI dan rekomendasi kalori.','on'=>true],
    ['name'=>'Halaman Tentang Kami','desc'=>'Halaman about us dengan sejarah, visi, misi, tim.','on'=>true],
    ['name'=>'Halaman Lokasi & Kontak','desc'=>'Halaman contact dengan peta, form, dan jam operasional.','on'=>true],
    ['name'=>'FAQ Section di Homepage','desc'=>'Section FAQ yang muncul di halaman membership.','on'=>true],
    ['name'=>'Testimonial Section','desc'=>'Section testimonial/member stories di homepage.','on'=>true],
    ['name'=>'Fitur Notifikasi Member','desc'=>'Email reminder kelas dan push notification untuk member.','on'=>false],
] as $f)
<div class="feature-toggle-item" style="{{ $f['on']?'border-color:var(--color-accent-light);':'' }}">
    <div class="feature-toggle-info">
        <h5>{{ $f['name'] }}</h5>
        <p>{{ $f['desc'] }}</p>
    </div>
    <label class="toggle"><input type="checkbox" {{ $f['on']?'checked':'' }}><span class="toggle-slider"></span></label>
</div>
@endforeach

<div style="margin-top:var(--space-6);"><button type="submit" class="btn btn-primary">Simpan Pengaturan Fitur</button></div>
</form>
@endsection
