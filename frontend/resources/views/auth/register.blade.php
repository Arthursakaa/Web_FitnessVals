<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Fitness Vals</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}"><link rel="stylesheet" href="{{ asset('css/reset.css') }}"><link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <style>
    body{display:flex;min-height:100vh;}
    .reg-left{flex:1;background: linear-gradient(135deg, rgba(26,32,44,0.95) 0%, rgba(0,0,0,0.85) 100%), url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=800&q=80') center/cover;display:flex;flex-direction:column;justify-content:center;padding:var(--space-16);color:#fff;position:relative;overflow:hidden;}
    .reg-left::before{content:'';position:absolute;inset:0;background:url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400"><defs><pattern id="d" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="400" height="400" fill="url(%23d)"/></svg>');}
    .reg-left *{position:relative;z-index:2;}
    .reg-left h1{font-size:var(--fs-4xl);font-weight:var(--fw-extrabold);margin-bottom:var(--space-4);color:#ffffff;}
    .reg-left p{color:rgba(255,255,255,0.85);font-size:var(--fs-lg);line-height:1.7;}
    .benefit-list{margin-top:var(--space-8);}
    .benefit-list li{display:flex;align-items:center;gap:var(--space-3);margin-bottom:var(--space-4);font-size:var(--fs-base);}
    .benefit-list li .bi{width:36px;height:36px;border-radius:50%;background:rgba(26,155,158,0.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--color-primary);font-size:20px;}
    .reg-right{width:540px;display:flex;align-items:center;justify-content:center;padding:var(--space-8);overflow-y:auto;background-color:#F8FAFC;}
    .reg-box{width:100%;max-width:440px;background:#ffffff;padding:40px;border-radius:var(--radius-xl);box-shadow:0 10px 40px rgba(0,0,0,0.06);}
    .form-label { font-weight: 700; color: #1E293B; margin-bottom: 6px; display: block; font-size: var(--fs-sm); }
    @media(max-width:768px){.reg-left{display:none;}.reg-right{width:100%;}}
    </style>
</head>
<body>
<div class="reg-left">
    <a href="{{ route('home') }}" class="nav-logo" style="color:#fff;margin-bottom:var(--space-10);font-size:24px;font-weight:800;letter-spacing:-0.5px;">
        <div class="logo-icon" style="width:36px;height:36px;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;border-radius:8px;"><i class="bi bi-lightning-charge-fill"></i></div> 
        Fitness Vals
    </a>
    <h1>Mulai Perjalanan Fitnessmu Sekarang</h1>
    <p>Daftar gratis dan nikmati semua fasilitas premium Fitness Vals.</p>
    <ul class="benefit-list">
        <li><span class="bi"><i class="bi bi-check-lg"></i></span> Free trial 1 hari di semua lokasi</li>
        <li><span class="bi"><i class="bi bi-check-lg"></i></span> Akses 8 kelas fitness gratis</li>
        <li><span class="bi"><i class="bi bi-check-lg"></i></span> Dashboard tracking progress & nutrisi</li>
        <li><span class="bi"><i class="bi bi-check-lg"></i></span> AI meal recommendation</li>
        <li><span class="bi"><i class="bi bi-check-lg"></i></span> Konsultasi gratis dengan trainer</li>
    </ul>
</div>
<div class="reg-right">
    <div class="reg-box">
        <h2 style="font-size:var(--fs-2xl);margin-bottom:var(--space-1);">Buat Akun Baru</h2>
        <p style="color:var(--color-text-muted);font-size:var(--fs-sm);margin-bottom:var(--space-6);">Sudah punya akun? <a href="{{ route('login') }}" style="color:var(--color-primary);font-weight:var(--fw-semibold);">Masuk</a></p>
        <form action="{{ route('register.process') }}" method="POST">
            @csrf
            
            @if($errors->any())
            <div style="background:#FEE2E2;color:#EF4444;padding:var(--space-3);border-radius:var(--radius-md);margin-bottom:var(--space-4);font-size:var(--fs-sm);">
                {{ $errors->first() }}
            </div>
            @endif

            <div class="form-group"><label class="form-label">Nama Lengkap</label><input type="text" name="name" class="form-input" placeholder="Andi Pratama" value="{{ old('name') }}" required></div>
            <div class="form-group"><label class="form-label">Email</label><input type="email" name="email" class="form-input" placeholder="email@example.com" value="{{ old('email') }}" required></div>
            
            <div class="form-row" style="margin-bottom:var(--space-5);">
                <div>
                    <label class="form-label">Paket Membership</label>
                    <select name="plan" class="form-input form-select" required>
                        <option value="basic" {{ request('plan') == 'basic' ? 'selected' : '' }}>Basic - Rp299K/bln</option>
                        <option value="pro" {{ request('plan') == 'pro' || !request('plan') ? 'selected' : '' }}>Pro - Rp549K/bln</option>
                        <option value="elite" {{ request('plan') == 'elite' ? 'selected' : '' }}>Elite - Rp899K/bln</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Siklus Tagihan</label>
                    <select name="billing_cycle" class="form-input form-select" required>
                        <option value="monthly">Bulanan</option>
                        <option value="yearly" selected>Tahunan (Hemat 20%)</option>
                    </select>
                </div>
            </div>
            <div class="form-group"><label class="form-label">Password</label><input type="password" name="password" class="form-input" placeholder="Min. 8 karakter" required></div>
            <div class="form-group"><label class="form-label">Konfirmasi Password</label><input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password" required></div>
            <div style="margin-bottom:var(--space-6);">
                <label style="font-size:var(--fs-xs);display:flex;align-items:flex-start;gap:var(--space-2);cursor:pointer;line-height:1.5;">
                    <input type="checkbox" style="margin-top:3px;" required>
                    Saya menyetujui <a href="#" style="color:var(--color-primary);">Syarat & Ketentuan</a> serta <a href="#" style="color:var(--color-primary);">Kebijakan Privasi</a> Fitness Vals.
                </label>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg"><i class="bi bi-person-circle"></i> Daftar Sekarang</button>
        </form>
    </div>
</div>
</body>
</html>
