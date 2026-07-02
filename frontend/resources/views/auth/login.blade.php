<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Fitness Vals</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}"><link rel="stylesheet" href="{{ asset('css/reset.css') }}"><link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <style>
    body{display:flex;min-height:100vh;}
    .login-left{flex:1;background: linear-gradient(135deg, rgba(26,32,44,0.95) 0%, rgba(0,0,0,0.85) 100%), url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=800&q=80') center/cover; display:flex;flex-direction:column;justify-content:center;padding:var(--space-16);color:#fff;position:relative;overflow:hidden;}
    .login-left::before{content:'';position:absolute;inset:0;background:url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400"><defs><pattern id="d" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1.5" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="400" height="400" fill="url(%23d)"/></svg>');}
    .login-left *{position:relative;z-index:2;}
    .login-left h1{font-size:var(--fs-4xl);font-weight:var(--fw-extrabold);margin-bottom:var(--space-4);color:#ffffff !important;}
    .login-left p{color:rgba(255,255,255,0.85) !important;font-size:var(--fs-base);max-width:400px;line-height:1.7;}
    .login-stats{display:flex;gap:var(--space-8);margin-top:var(--space-10);}
    .login-stats div .num{font-size:var(--fs-2xl);font-weight:var(--fw-extrabold);color:var(--color-cta);}
    .login-stats div .lbl{font-size:var(--fs-xs);color:rgba(255,255,255,0.5);}
    .login-right{width:540px;display:flex;align-items:center;justify-content:center;padding:var(--space-10);background-color:#F8FAFC;}
    .login-box{width:100%;max-width:440px;background:#ffffff;padding:40px;border-radius:var(--radius-xl);box-shadow:0 10px 40px rgba(0,0,0,0.06);}
    .form-label { font-weight: 700; color: #1E293B; margin-bottom: 6px; display: block; font-size: var(--fs-sm); }
    @media(max-width:768px){.login-left{display:none;}.login-right{width:100%;}}
    </style>
</head>
<body>
<div class="login-left">
    <a href="{{ route('home') }}" class="nav-logo" style="color:#fff;margin-bottom:var(--space-10);font-size:24px;font-weight:800;letter-spacing:-0.5px;">
        <div class="logo-icon" style="width:36px;height:36px;background:var(--color-primary);color:#fff;display:flex;align-items:center;justify-content:center;border-radius:8px;"><i class="bi bi-lightning-charge-fill"></i></div> 
        Fitness Vals
    </a>
    <h1>Transform Your<br>Body Today</h1>
    <p>Bergabung dengan 150 member yang sudah merasakan transformasi bersama Fitness Vals.</p>
    <div class="login-stats">
        <div><div class="num">150</div><div class="lbl">Member Aktif</div></div>
        <div><div class="num">1</div><div class="lbl">Lokasi</div></div>
        <div><div class="num">4.9 <i class="bi bi-star-fill" style="font-size:18px;position:relative;top:-2px;"></i></div><div class="lbl">Rating</div></div>
    </div>
</div>
<div class="login-right">
    <div class="login-box">
        <h2 style="font-size:var(--fs-2xl);margin-bottom:var(--space-1);">Masuk ke akunmu</h2>
        <p style="color:var(--color-text-muted);font-size:var(--fs-sm);margin-bottom:var(--space-8);">Belum punya akun? <a href="{{ route('register') }}" style="color:var(--color-primary);font-weight:var(--fw-semibold);">Daftar gratis</a></p>
        <form action="{{ route('login.process') }}" method="POST">
            @csrf
            
            @if($errors->any())
            <div style="background:#FEE2E2;color:#EF4444;padding:var(--space-3);border-radius:var(--radius-md);margin-bottom:var(--space-4);font-size:var(--fs-sm);">
                {{ $errors->first() }}
            </div>
            @endif

            <div class="form-group"><label class="form-label">Email</label><input type="email" name="email" class="form-input" placeholder="email@example.com" value="{{ old('email') }}" required></div>
            <div class="form-group"><label class="form-label">Password</label><input type="password" name="password" class="form-input" placeholder="••••••••" required></div>
            <div class="flex-between" style="margin-bottom:var(--space-6);">
                <label style="font-size:var(--fs-xs);display:flex;align-items:center;gap:var(--space-1);cursor:pointer;"><input type="checkbox" name="remember"> Ingat saya</label>
                <a href="#" style="font-size:var(--fs-xs);color:var(--color-primary);">Lupa password?</a>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg"><i class="bi bi-key"></i> Masuk</button>
        </form>

    </div>
</div>
</body>
</html>
