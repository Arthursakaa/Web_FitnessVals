@extends('layouts.admin')
@section('title', 'Member Detail')
@section('page_title', 'Member Review Details')

@section('content')
<div class="grid-2" style="gap:var(--space-6);grid-template-columns:1fr 2fr;">
    <div class="card text-center">
        <div class="avatar avatar-xl" style="margin:0 auto var(--space-4);border:4px solid var(--color-accent-light);">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&size=96&background=FF6B2C&color=fff" alt="">
        </div>
        <h3>{{ $member->name }}</h3>
        <p class="text-muted" style="font-size:var(--fs-sm);">{{ $member->email }}</p>
        <span class="badge {{ strtolower($member->status ?? 'active') == 'active' ? 'badge-success' : 'badge-warning' }}" style="margin-top:var(--space-2);">{{ ucfirst($member->status ?? 'Active') }}</span>
    </div>
    <div>
        <div class="card" style="margin-bottom:var(--space-5);">
            <h4 style="margin-bottom:var(--space-4);">Informasi Pendaftaran</h4>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-4);font-size:var(--fs-sm);">
                @php
                    $fields = [
                        ['l' => 'Nama Lengkap', 'v' => $member->name],
                        ['l' => 'Email', 'v' => $member->email],
                        ['l' => 'No. HP', 'v' => $member->profile->phone ?? '-'],
                        ['l' => 'Tanggal Lahir', 'v' => isset($member->profile->date_of_birth) ? \Carbon\Carbon::parse($member->profile->date_of_birth)->format('d F Y') : '-'],
                        ['l' => 'Jenis Kelamin', 'v' => ucfirst($member->profile->gender ?? '-')],
                        ['l' => 'Paket Dipilih', 'v' => ucfirst($member->plan ?? 'Basic')],
                        ['l' => 'Tanggal Daftar', 'v' => $member->created_at->format('d F Y')],
                        ['l' => 'Status', 'v' => ucfirst($member->status ?? 'Active')]
                    ];
                @endphp
                @foreach($fields as $f)
                <div><p class="text-muted" style="font-size:var(--fs-xs);">{{ $f['l'] }}</p><strong>{{ $f['v'] }}</strong></div>
                @endforeach
            </div>
        </div>
        <div class="card">
            <h4 style="margin-bottom:var(--space-4);">Aksi Admin</h4>
            <div style="display:flex;flex-wrap:wrap;gap:var(--space-3);">
                <button class="btn btn-outline btn-sm"><i class="bi bi-envelope"></i> Kirim Email</button>
                <button class="btn btn-outline btn-sm"><i class="bi bi-pencil-square"></i> Edit Data</button>
                <button class="btn btn-outline btn-sm"><i class="bi bi-arrow-repeat"></i> Reset Password</button>
                <button class="btn btn-outline btn-sm" style="color:var(--color-warning);"><i class="bi bi-pause-fill"></i> Suspend</button>
                <button class="btn btn-outline btn-sm" style="color:var(--color-danger);"><i class="bi bi-trash"></i> Hapus</button>
            </div>
        </div>
    </div>
</div>
@endsection
