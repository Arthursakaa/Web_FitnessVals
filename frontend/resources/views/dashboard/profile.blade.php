@extends('layouts.dashboard')
@section('title', 'Profil Saya')
@section('page_title', 'Profil Saya')

@section('content')
<div class="grid-2" style="gap:var(--space-6);grid-template-columns:1fr 2fr;">
    {{-- PROFILE CARD --}}
    <div>
        <div class="card text-center" id="tour-step-1" style="margin-bottom:var(--space-5);">
            <div class="avatar" style="width:120px;height:120px;margin:0 auto var(--space-4);border:4px solid var(--color-accent-light);font-size:40px;">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=120&background=FF6B2C&color=fff" alt="">
            </div>
            <h3>{{ $user->name }}</h3>
            <p class="text-muted" style="font-size:var(--fs-sm);">{{ $user->email }}</p>
            <span class="badge {{ $user->plan == 'elite' ? 'badge-dark' : ($user->plan == 'pro' ? 'badge-accent' : 'badge-outline') }}" style="margin-top:var(--space-2);text-transform:uppercase;">{{ $user->plan ?? 'Basic' }} Member</span>
            <div style="margin-top:var(--space-4);padding-top:var(--space-4);border-top:1px solid var(--color-border);">
                <p style="font-size:var(--fs-xs);color:var(--color-text-muted);">Member sejak: {{ $user->created_at->format('M Y') }}</p>
                <p style="font-size:var(--fs-xs);color:var(--color-text-muted);">Siklus Tagihan: {{ ucfirst($user->billing_cycle ?? 'Bulanan') }}</p>
            </div>
            <button class="btn btn-outline btn-block btn-sm" style="margin-top:var(--space-3);"><i class="bi bi-camera"></i> Ganti Foto</button>
        </div>
        <div class="card">
            <h5 style="margin-bottom:var(--space-3);">Notifikasi</h5>
            <div class="flex-between" style="margin-bottom:var(--space-3);font-size:var(--fs-sm);"><span>Email reminder kelas</span><label class="toggle"><input type="checkbox" checked><span class="toggle-slider"></span></label></div>
            <div class="flex-between" style="font-size:var(--fs-sm);"><span>Push notification</span><label class="toggle"><input type="checkbox" checked><span class="toggle-slider"></span></label></div>
        </div>
    </div>

    {{-- FORMS --}}
    <div>
        <div class="card" id="tour-step-2" style="margin-bottom:var(--space-5);">
            <h4 style="margin-bottom:var(--space-5);">Data Personal & Keamanan</h4>
            @if($errors->any())
                <div style="background:#FEE2E2;color:#991B1B;padding:var(--space-3);border-radius:var(--radius-md);margin-bottom:var(--space-4);font-size:var(--fs-sm);">
                    <ul style="margin-left:var(--space-4);">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('dashboard.profile.update') }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Nama Lengkap</label><input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required></div>
                    <div class="form-group"><label class="form-label">Email</label><input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required></div>
                </div>
                
                <h5 style="margin:var(--space-4) 0 var(--space-3);padding-top:var(--space-4);border-top:1px solid var(--color-border-light);">Ganti Password <span class="text-muted" style="font-size:var(--fs-xs);font-weight:normal;">(Opsional, kosongi jika tidak ingin mengubah)</span></h5>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Password Baru</label><input type="password" name="password" class="form-input" placeholder="Minimal 8 karakter"></div>
                    <div class="form-group"><label class="form-label">Konfirmasi Password</label><input type="password" name="password_confirmation" class="form-input" placeholder="Ketik ulang password baru"></div>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
        <div class="card" id="tour-step-3" style="margin-bottom:var(--space-5);">
            <h4 style="margin-bottom:var(--space-5);">Data Kesehatan & Preferensi Makanan</h4>
            <form action="{{ route('dashboard.profile.health') }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Tinggi Badan (cm)</label><input type="number" name="height_cm" class="form-input" value="{{ $profile->height_cm ?? 175 }}" required></div>
                    <div class="form-group"><label class="form-label">Berat Badan (kg)</label><input type="number" name="weight_kg" class="form-input" value="{{ $profile->weight_kg ?? 70 }}" step="0.1" required></div>
                </div>
                <div class="form-group"><label class="form-label">Target Berat Badan (kg)</label><input type="number" name="target_weight_kg" class="form-input" value="{{ $profile->target_weight_kg ?? '' }}" step="0.1"></div>
                <div class="form-group">
                    <label class="form-label">Preferensi Makanan</label>
                    <select class="form-input form-select" name="dietary_preference">
                        <option value="Normal" {{ ($profile->dietary_preference ?? '') == 'Normal' ? 'selected' : '' }}>Normal</option>
                        <option value="Halal" {{ ($profile->dietary_preference ?? '') == 'Halal' ? 'selected' : '' }}>Halal Only</option>
                        <option value="Vegetarian" {{ ($profile->dietary_preference ?? '') == 'Vegetarian' ? 'selected' : '' }}>Vegetarian</option>
                        <option value="Vegan" {{ ($profile->dietary_preference ?? '') == 'Vegan' ? 'selected' : '' }}>Vegan</option>
                        <option value="Keto" {{ ($profile->dietary_preference ?? '') == 'Keto' ? 'selected' : '' }}>Keto</option>
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Riwayat Penyakit (Filter Rekomendasi)</label>
                    <div style="display:flex;flex-wrap:wrap;gap:var(--space-2);">
                        @php $medHist = $profile->medical_history ?? []; @endphp
                        @foreach(['Tidak Ada', 'Diabetes', 'Hipertensi', 'Kolesterol', 'Asam Urat', 'Penyakit Jantung', 'Maag/GERD', 'Low Sodium', 'Alergi Kacang', 'Alergi Seafood', 'Alergi Susu (Laktosa)', 'Alergi Telur', 'Alergi Gluten'] as $d)
                        <label class="badge badge-outline" style="cursor:pointer;padding:6px 12px;">
                            <input type="checkbox" name="medical_history[]" value="{{ $d }}" style="margin-right:4px;" {{ in_array($d, $medHist) || (empty($medHist) && $d=='Tidak Ada') ? 'checked' : '' }}> {{ $d }}
                        </label>
                        @endforeach
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Data Kesehatan</button>
            </form>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    @endif

    // ==========================================
    // INTERACTIVE ONBOARDING TOUR (PROFILE)
    // ==========================================
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof initPageTour === 'function') {
            initPageTour([
                {
                    target: '#tour-step-1',
                    title: 'Profil Keanggotaan 💳',
                    text: 'Di sini kamu bisa melihat status membership (Basic/Pro/Elite) dan siklus tagihan aktifmu.',
                    position: 'right'
                },
                {
                    target: '#tour-step-2',
                    title: 'Keamanan Akun 🔒',
                    text: 'Pastikan email selalu aktif dan gunakan password yang kuat untuk menjaga keamanan data fitness-mu.',
                    position: 'left'
                },
                {
                    target: '#tour-step-3',
                    title: 'Kondisi Medis & Alergi ⚕️',
                    text: 'SANGAT PENTING: Lengkapi riwayat penyakit dan alergi di sini agar AI kami bisa memberikan rekomendasi makanan dan latihan yang aman untukmu!',
                    position: 'left'
                }
            ], 'fitnessValsTourCompleted_profile');
        }
    });
</script>
@endsection
