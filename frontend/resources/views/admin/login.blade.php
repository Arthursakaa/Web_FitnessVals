<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Fitness Vals</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}"><link rel="stylesheet" href="{{ asset('css/reset.css') }}"><link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <style>
    body { display: flex; min-height: 100vh; background: var(--color-bg-dark); }
    .admin-login-left {
        flex: 1; background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #0F172A 100%);
        display: flex; flex-direction: column; justify-content: center; align-items: center;
        padding: var(--space-16); position: relative; overflow: hidden;
    }
    .admin-login-left::before {
        content: ''; position: absolute; inset: 0;
        background: radial-gradient(ellipse at 30% 40%, rgba(255,70,85,0.08) 0%, transparent 60%),
                    radial-gradient(ellipse at 70% 70%, rgba(0,194,255,0.06) 0%, transparent 50%);
    }
    .admin-login-left * { position: relative; z-index: 2; }
    .admin-brand-icon {
        width: 80px; height: 80px; border-radius: var(--radius-2xl);
        background: linear-gradient(135deg, var(--color-primary), #FF6B77);
        display: flex; align-items: center; justify-content: center;
        font-size: 36px; color: #fff; margin-bottom: var(--space-6);
        box-shadow: 0 12px 40px rgba(255,70,85,0.3);
    }
    .admin-login-left h1 {
        font-size: var(--fs-3xl); font-weight: var(--fw-extrabold);
        color: #fff; text-align: center; margin-bottom: var(--space-3);
    }
    .admin-login-left p { color: rgba(255,255,255,0.5); font-size: var(--fs-sm); text-align: center; max-width: 320px; }
    .admin-features { display: flex; flex-direction: column; gap: var(--space-4); margin-top: var(--space-10); }
    .admin-feature-item {
        display: flex; align-items: center; gap: var(--space-3);
        color: rgba(255,255,255,0.6); font-size: var(--fs-sm);
    }
    .admin-feature-item i {
        width: 36px; height: 36px; border-radius: var(--radius-md);
        background: rgba(255,255,255,0.06); display: flex; align-items: center;
        justify-content: center; color: var(--color-primary); font-size: 16px;
        border: 1px solid rgba(255,255,255,0.08);
    }
    .admin-login-right {
        width: 100%; display: flex; align-items: center; justify-content: center;
        padding: var(--space-10); background: #F8FAFC; /* Softer background */
    }
    .login-box { width: 100%; max-width: 420px; background: #fff; padding: var(--space-8); border-radius: var(--radius-xl); box-shadow: 0 10px 40px rgba(0,0,0,0.05); }
    .login-box h2 { font-size: var(--fs-2xl); margin-bottom: var(--space-2); text-align: center; }
    .login-box .subtitle { color: var(--color-text-muted); font-size: var(--fs-sm); margin-bottom: var(--space-8); text-align: center; }
    </style>
</head>
<body>
<div class="admin-login-right">
    <div class="login-box">
        <h2>Masuk sebagai Admin</h2>
        <p class="subtitle">Akses terbatas untuk administrator gym.</p>
        <form action="{{ route('admin.login.process') }}" method="POST">
            @csrf
            
            @if(session('error'))
                <div style="background:#FEE2E2;color:#991B1B;padding:var(--space-3);border-radius:var(--radius-md);margin-bottom:var(--space-4);font-size:var(--fs-sm);">
                    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div style="background:#FEE2E2;color:#991B1B;padding:var(--space-3);border-radius:var(--radius-md);margin-bottom:var(--space-4);font-size:var(--fs-sm);">
                    <ul style="margin-left:var(--space-4);">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group">
                <label class="form-label">Email Admin</label>
                <input type="email" name="email" class="form-input" placeholder="admin@fitnessvals.com" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-input" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg"><i class="bi bi-shield-lock"></i> Login to Admin</button>
        </form>
        <p style="text-align:center;margin-top:var(--space-6);font-size:var(--fs-xs);color:var(--color-text-muted);">
            <a href="{{ route('home') }}" style="color:var(--color-text-muted);"><i class="bi bi-arrow-left"></i> Kembali ke Website</a>
        </p>
    </div>
</div>
</body>
</html>
