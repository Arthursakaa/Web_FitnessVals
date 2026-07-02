@extends('layouts.admin')
@section('title', 'Pengaturan Gym')
@section('page_title', 'Pengaturan Gym')

@section('content')
<div class="grid-2" style="gap:var(--space-6);">
    <div>
        <div class="card" style="margin-bottom:var(--space-5);">
            <h4 style="margin-bottom:var(--space-5);">🏢 Informasi Gym</h4>
            <div class="form-group"><label class="form-label">Nama Gym</label><input type="text" class="form-input" value="Fitness Vals"></div>
            <div class="form-group"><label class="form-label">Slogan / Tagline</label><input type="text" class="form-input" value="Transform Your Body, Transform Your Life"></div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">No. Telepon</label><input type="text" class="form-input" value="+62 812-3456-7890"></div>
                <div class="form-group"><label class="form-label">Email Resmi</label><input type="email" class="form-input" value="info@fitnessvals.com"></div>
            </div>
            <div class="form-group"><label class="form-label">Alamat</label><textarea class="form-input" rows="2">Jl. Ijen Boulevard No. 8, Kota Malang 65112</textarea></div>
        </div>

        <div class="card" style="margin-bottom:var(--space-5);">
            <h4 style="margin-bottom:var(--space-5);"><i class="bi bi-clock"></i> Jam Operasional</h4>
            @foreach(['Senin'=>'05:00 - 22:00','Selasa'=>'05:00 - 22:00','Rabu'=>'05:00 - 22:00','Kamis'=>'05:00 - 22:00','Jumat'=>'05:00 - 22:00','Sabtu'=>'06:00 - 20:00','Minggu'=>'08:00 - 14:00'] as $day => $hours)
            <div class="flex-between" style="margin-bottom:var(--space-3);">
                <span style="font-size:var(--fs-sm);font-weight:600;width:80px;">{{ $day }}</span>
                <div class="flex-gap">
                    <input type="time" class="form-input" value="{{ explode(' - ',$hours)[0] }}" style="width:auto;padding:6px 10px;">
                    <span>—</span>
                    <input type="time" class="form-input" value="{{ explode(' - ',$hours)[1] }}" style="width:auto;padding:6px 10px;">
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div>
        <div class="card" style="margin-bottom:var(--space-5);">
            <h4 style="margin-bottom:var(--space-5);"><i class="bi bi-gear"></i> Pengaturan Umum</h4>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Mata Uang</label><select class="form-input form-select"><option selected>Rp (IDR)</option><option>$ (USD)</option><option>€ (EUR)</option><option>¥ (JPY)</option></select></div>
                <div class="form-group"><label class="form-label">Timezone</label><select class="form-input form-select"><option selected>Asia/Jakarta (WIB)</option><option>Asia/Makassar (WITA)</option><option>Asia/Jayapura (WIT)</option></select></div>
            </div>
            <div class="form-group"><label class="form-label">Kapasitas Default Kelas</label><input type="number" class="form-input" value="20"></div>
        </div>

        <div class="card" style="margin-bottom:var(--space-5);">
            <h4 style="margin-bottom:var(--space-5);"><i class="bi bi-envelope"></i> Email Templates</h4>
            <div class="form-group">
                <label class="form-label">Subject Email Verifikasi Akun</label>
                <input type="text" class="form-input" value="Selamat datang di Fitness Vals! Akun kamu sudah diverifikasi.">
            </div>
            <div class="form-group">
                <label class="form-label">Subject Email Reminder Kelas</label>
                <input type="text" class="form-input" value="Reminder: Kelas kamu besok jam {waktu} — {nama_kelas}">
            </div>
            <div class="form-group">
                <label class="form-label">Subject Email Membership Expiring</label>
                <input type="text" class="form-input" value="Membership kamu akan berakhir dalam {hari} hari">
            </div>
        </div>

        <button class="btn btn-primary">Simpan Pengaturan</button>
    </div>
</div>
@endsection
