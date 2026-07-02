@extends('layouts.admin')
@section('title', 'Kelola Konten')
@section('page_title', 'Kelola Konten Website')

@section('content')
<div class="tabs">
    <button class="tab-btn active" data-tab="tabHomepage">Homepage</button>
    <button class="tab-btn" data-tab="tabAbout">Tentang Kami</button>
    <button class="tab-btn" data-tab="tabMembership">Membership</button>
    <button class="tab-btn" data-tab="tabContact">Kontak</button>
    <button class="tab-btn" data-tab="tabFaq">FAQ</button>
    <button class="tab-btn" data-tab="tabFooter">Footer</button>
</div>

{{-- HOMEPAGE --}}
<div class="tab-content" id="tabHomepage">
    @if(session('success'))
        <div style="background:#D1FAE5;color:#065F46;padding:var(--space-3);border-radius:var(--radius-md);margin-bottom:var(--space-4);font-size:var(--fs-sm);">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card" style="margin-bottom:var(--space-5);">
            <h4 style="margin-bottom:var(--space-5);">Hero Section</h4>
        <div class="form-group"><label class="form-label">Headline</label><input type="text" name="home_hero_title" class="form-input" value="{{ \App\Models\Setting::getVal('home_hero_title', 'BENTUK TUBUH IMPIANMU DIMULAI DI SINI') }}"></div>
        <div class="form-group"><label class="form-label">Subheadline</label><textarea name="home_hero_subtitle" class="form-input" rows="2">{{ \App\Models\Setting::getVal('home_hero_subtitle', 'Latihan fisik yang terencana di pusat kebugaran modern, didukung dengan peralatan lengkap dan pelatih profesional.') }}</textarea></div>
        <div class="form-group"><label class="form-label">CTA Button 1 Text</label><input type="text" name="home_hero_cta1" class="form-input" value="{{ \App\Models\Setting::getVal('home_hero_cta1', 'LIHAT SEMUA KELAS') }}"></div>
        <div class="form-group">
            <label class="form-label">Gambar Latar Hero (Kosongkan jika tidak ingin mengubah)</label>
            @if(\App\Models\Setting::getVal('home_hero_image'))
                <img src="{{ \App\Models\Setting::getVal('home_hero_image') }}" style="height:100px;border-radius:var(--radius-sm);margin-bottom:var(--space-2);display:block;">
            @endif
            <input type="file" name="home_hero_image" class="form-input" accept="image/*" style="padding:10px;">
        </div>
    </div>
    <div class="card" style="margin-bottom:var(--space-5);">
        <h4 style="margin-bottom:var(--space-5);">Statistik</h4>
        <div class="grid-4">
            @foreach([['l'=>'Member Aktif','v'=>'10000'],['l'=>'Elite Trainers','v'=>'6'],['l'=>'Success Rate (%)','v'=>'98'],['l'=>'Daily Classes','v'=>'20']] as $s)
            <div class="form-group"><label class="form-label">{{ $s['l'] }}</label><input type="number" class="form-input" value="{{ $s['v'] }}"></div>
            @endforeach
        </div>
    </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan Homepage</button>
    </form>
</div>

{{-- ABOUT --}}
<div class="tab-content" id="tabAbout" style="display:none;">
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <h4 style="margin-bottom:var(--space-5);">Tentang Kami</h4>
            <div class="form-group"><label class="form-label">Sejarah Gym</label><textarea name="about_history" class="form-input" rows="4">{{ \App\Models\Setting::getVal('about_history', 'Fitness Vals didirikan pada tahun 2018 dengan visi sederhana...') }}</textarea></div>
            <div class="form-group"><label class="form-label">Visi</label><textarea name="about_vision" class="form-input" rows="2">{{ \App\Models\Setting::getVal('about_vision', 'Menjadi platform kebugaran terdepan di Indonesia...') }}</textarea></div>
            <div class="form-group"><label class="form-label">Misi</label><textarea name="about_mission" class="form-input" rows="2">{{ \App\Models\Setting::getVal('about_mission', 'Menyediakan fasilitas kelas dunia...') }}</textarea></div>
            <div class="form-group">
                <label class="form-label">Foto Header About</label>
                @if(\App\Models\Setting::getVal('about_hero_image'))
                    <img src="{{ \App\Models\Setting::getVal('about_hero_image') }}" style="height:100px;border-radius:var(--radius-sm);margin-bottom:var(--space-2);display:block;">
                @endif
                <input type="file" name="about_hero_image" class="form-input" accept="image/*" style="padding:10px;">
            </div>
            <button type="submit" class="btn btn-primary">Simpan Tentang Kami</button>
        </div>
    </form>
</div>

{{-- MEMBERSHIP --}}
<div class="tab-content" id="tabMembership" style="display:none;">
    <div class="card">
        <h4 style="margin-bottom:var(--space-5);">Paket Membership</h4>
        @foreach([['name'=>'Basic','price'=>'299000'],['name'=>'Pro','price'=>'549000'],['name'=>'Elite','price'=>'899000']] as $p)
        <div style="padding:var(--space-4);border:1px solid var(--color-border);border-radius:var(--radius-lg);margin-bottom:var(--space-4);">
            <div class="form-row">
                <div class="form-group"><label class="form-label">Nama Paket</label><input type="text" class="form-input" value="{{ $p['name'] }}"></div>
                <div class="form-group"><label class="form-label">Harga/Bulan (Rp)</label><input type="number" class="form-input" value="{{ $p['price'] }}"></div>
            </div>
            <div class="form-group"><label class="form-label">Daftar Fitur (1 per baris)</label><textarea class="form-input" rows="3">Akses gym area utama
Locker harian
Wi-Fi gratis</textarea></div>
            <div class="flex-between"><label style="font-size:var(--fs-sm);display:flex;align-items:center;gap:var(--space-2);"><input type="checkbox" {{ $p['name']=='Pro'?'checked':'' }}> Highlight sebagai "Most Popular"</label><button class="btn btn-ghost btn-sm" style="color:var(--color-danger);">Hapus Paket</button></div>
        </div>
        @endforeach
        <button class="btn btn-outline btn-sm" style="margin-bottom:var(--space-4);">+ Tambah Paket Baru</button><br>
        <button class="btn btn-primary">Simpan Membership</button>
    </div>
</div>

{{-- CONTACT --}}
<div class="tab-content" id="tabContact" style="display:none;">
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        <div class="card">
            <h4 style="margin-bottom:var(--space-5);">Informasi Kontak & Lokasi</h4>
            <div class="form-group"><label class="form-label">Alamat</label><input type="text" name="contact_address" class="form-input" value="{{ \App\Models\Setting::getVal('contact_address', 'Jl. Ijen Boulevard No. 8, Kota Malang 65112') }}"></div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">No. Telepon</label><input type="text" name="contact_phone" class="form-input" value="{{ \App\Models\Setting::getVal('contact_phone', '+62 812-3456-7890') }}"></div>
                <div class="form-group"><label class="form-label">Email</label><input type="email" name="contact_email" class="form-input" value="{{ \App\Models\Setting::getVal('contact_email', 'info@fitnessvals.com') }}"></div>
            </div>
            <div class="form-group"><label class="form-label">Link Google Maps Embed</label><input type="url" name="contact_map_url" class="form-input" value="{{ \App\Models\Setting::getVal('contact_map_url', '') }}"></div>
            <button type="submit" class="btn btn-primary">Simpan Info Kontak</button>
        </div>
    </form>
</div>

{{-- FAQ --}}
<div class="tab-content" id="tabFaq" style="display:none;">
    <div class="card">
        <div class="flex-between" style="margin-bottom:var(--space-5);"><h4>FAQ</h4><button class="btn btn-primary btn-sm">+ Tambah FAQ</button></div>
        @foreach([['q'=>'Apakah bisa freeze membership?','a'=>'Ya, kamu bisa freeze membership...'],['q'=>'Bagaimana cara upgrade paket?','a'=>'Kamu bisa upgrade kapan saja...']] as $i => $f)
        <div style="padding:var(--space-4);border:1px solid var(--color-border);border-radius:var(--radius-lg);margin-bottom:var(--space-3);">
            <div class="form-group"><label class="form-label">Pertanyaan</label><input type="text" class="form-input" value="{{ $f['q'] }}"></div>
            <div class="form-group"><label class="form-label">Jawaban</label><textarea class="form-input" rows="2">{{ $f['a'] }}</textarea></div>
            <button class="btn btn-ghost btn-sm" style="color:var(--color-danger);">Hapus FAQ</button>
        </div>
        @endforeach
        <button class="btn btn-primary">Simpan FAQ</button>
    </div>
</div>

{{-- FOOTER --}}
<div class="tab-content" id="tabFooter" style="display:none;">
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        <div class="card">
            <h4 style="margin-bottom:var(--space-5);">Footer</h4>
            <div class="form-group"><label class="form-label">Tagline</label><input type="text" name="footer_tagline" class="form-input" value="{{ \App\Models\Setting::getVal('footer_tagline', 'Platform kebugaran premium yang menggabungkan teknologi dan sains olahraga.') }}"></div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Instagram URL</label><input type="url" name="footer_ig" class="form-input" value="{{ \App\Models\Setting::getVal('footer_ig') }}" placeholder="https://instagram.com/..."></div>
                <div class="form-group"><label class="form-label">YouTube URL</label><input type="url" name="footer_yt" class="form-input" value="{{ \App\Models\Setting::getVal('footer_yt') }}" placeholder="https://youtube.com/..."></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Twitter URL</label><input type="url" name="footer_tw" class="form-input" value="{{ \App\Models\Setting::getVal('footer_tw') }}"></div>
                <div class="form-group"><label class="form-label">Facebook URL</label><input type="url" name="footer_fb" class="form-input" value="{{ \App\Models\Setting::getVal('footer_fb') }}"></div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Footer</button>
        </div>
    </form>
</div>
@endsection
