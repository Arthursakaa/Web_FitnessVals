<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Membership — Fitness Vals</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary: #1A9B9E;
            --primary-dark: #137779;
            --bg: #F8FAFC;
            --surface: #FFFFFF;
            --text-main: #0F172A;
            --text-muted: #64748B;
            --border: #E2E8F0;
            --accent: #FF6B2C;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: var(--bg); color: var(--text-main); min-height: 100vh; display: flex; flex-direction: column; }
        
        /* Navbar Simple */
        .pay-nav { background: var(--surface); padding: 20px 0; border-bottom: 1px solid var(--border); }
        .container { max-width: 1000px; margin: 0 auto; padding: 0 24px; }
        .logo-wrap { display: flex; align-items: center; gap: 10px; font-weight: 800; font-size: 20px; color: var(--text-main); text-decoration: none; }
        .logo-icon { width: 32px; height: 32px; background: var(--primary); color: #fff; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
        
        .main-wrapper { flex: 1; padding: 40px 0; }
        .checkout-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 32px; }
        
        @media (max-width: 768px) {
            .checkout-grid { grid-template-columns: 1fr; }
        }

        .card { background: var(--surface); border-radius: var(--radius-xl); padding: 32px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid var(--border); }
        .card-title { font-size: 20px; font-weight: 700; margin-bottom: 24px; display: flex; align-items: center; gap: 8px; }
        
        /* Payment Methods */
        .pm-group { margin-bottom: 20px; }
        .pm-label { font-size: 14px; font-weight: 600; color: var(--text-muted); margin-bottom: 12px; display: block; text-transform: uppercase; letter-spacing: 0.5px; }
        .pm-options { display: flex; flex-direction: column; gap: 12px; }
        .pm-option { display: flex; align-items: center; justify-content: space-between; padding: 16px; border: 1.5px solid var(--border); border-radius: var(--radius-md); cursor: pointer; transition: all 0.2s ease; position: relative; overflow: hidden; }
        .pm-option:hover { border-color: var(--primary); background: rgba(26, 155, 158, 0.02); }
        .pm-option.selected { border-color: var(--primary); background: rgba(26, 155, 158, 0.05); }
        .pm-option.selected::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: var(--primary); }
        .pm-info { display: flex; align-items: center; gap: 16px; }
        .pm-icon { width: 56px; height: 36px; background: #fff; border: 1px solid var(--border); border-radius: 6px; display: flex; align-items: center; justify-content: center; padding: 4px; overflow: hidden; }
        .pm-icon img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .pm-name { font-weight: 600; font-size: 15px; }
        .pm-radio { width: 20px; height: 20px; border-radius: 50%; border: 2px solid var(--border); display: flex; align-items: center; justify-content: center; }
        .pm-option.selected .pm-radio { border-color: var(--primary); }
        .pm-option.selected .pm-radio::after { content: ''; width: 10px; height: 10px; background: var(--primary); border-radius: 50%; }

        /* Order Summary */
        .summary-card { position: sticky; top: 24px; }
        .plan-badge { display: inline-flex; background: rgba(26, 155, 158, 0.1); color: var(--primary); padding: 6px 12px; border-radius: 100px; font-weight: 700; font-size: 13px; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 1px; }
        .summary-price { font-size: 36px; font-weight: 800; color: var(--text-main); margin-bottom: 8px; }
        .summary-cycle { color: var(--text-muted); font-size: 14px; margin-bottom: 24px; font-weight: 500; }
        
        .summary-list { list-style: none; margin-bottom: 24px; border-top: 1px dashed var(--border); border-bottom: 1px dashed var(--border); padding: 20px 0; }
        .summary-list li { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; color: var(--text-muted); }
        .summary-list li:last-child { margin-bottom: 0; }
        .summary-list li.total { font-size: 18px; font-weight: 700; color: var(--text-main); margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--border); }
        
        .btn-pay { width: 100%; background: var(--primary); color: #fff; border: none; padding: 16px; border-radius: var(--radius-lg); font-size: 16px; font-weight: 700; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 10px; box-shadow: 0 8px 20px rgba(26, 155, 158, 0.25); }
        .btn-pay:hover { background: var(--primary-dark); transform: translateY(-2px); box-shadow: 0 12px 24px rgba(26, 155, 158, 0.3); }
        .btn-pay:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }

        /* Loader Animation */
        .loader { border: 3px solid rgba(255,255,255,0.3); border-top: 3px solid #fff; border-radius: 50%; width: 24px; height: 24px; animation: spin 1s linear infinite; display: none; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        /* Success Overlay */
        .overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; z-index: 999; opacity: 0; visibility: hidden; transition: all 0.4s ease; }
        .overlay.active { opacity: 1; visibility: visible; }
        .success-box { background: var(--surface); padding: 48px; border-radius: var(--radius-xl); text-align: center; max-width: 400px; width: 90%; transform: translateY(20px); transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .overlay.active .success-box { transform: translateY(0); }
        
        /* Checkmark Animation */
        .success-icon { width: 80px; height: 80px; margin: 0 auto 24px; position: relative; }
        .check-circle { stroke-dasharray: 166; stroke-dashoffset: 166; stroke-width: 2; stroke-miterlimit: 10; stroke: var(--primary); fill: none; animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards; }
        .check-path { transform-origin: 50% 50%; stroke-dasharray: 48; stroke-dashoffset: 48; stroke: var(--primary); stroke-width: 3; stroke-linecap: round; stroke-linejoin: round; fill: none; animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.6s forwards; }
        @keyframes stroke { 100% { stroke-dashoffset: 0; } }
        
        .success-title { font-size: 24px; font-weight: 800; color: var(--text-main); margin-bottom: 8px; }
        .success-desc { color: var(--text-muted); font-size: 15px; margin-bottom: 24px; }
        .success-redirect { font-size: 13px; color: var(--text-muted); display: flex; align-items: center; justify-content: center; gap: 8px; }
        .success-redirect .spinner { width: 14px; height: 14px; border: 2px solid var(--border); border-top-color: var(--primary); border-radius: 50%; animation: spin 1s linear infinite; }
        
        /* Security Badge */
        .secure-badge { display: flex; align-items: center; justify-content: center; gap: 8px; font-size: 13px; color: var(--text-muted); margin-top: 24px; }
    </style>
</head>
<body>

    <nav class="pay-nav">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
            <a href="#" class="logo-wrap">
                <div class="logo-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m13 2 0 6 4 0-7 8 0-6-4 0Z"/></svg></div>
                Fitness Vals
            </a>
            <div style="font-size: 14px; font-weight: 600; color: var(--text-muted); display: flex; align-items: center; gap: 6px;">
                <i class="bi bi-lock-fill" style="color:var(--primary);"></i> Pembayaran Aman
            </div>
        </div>
    </nav>

    <div class="main-wrapper">
        <div class="container">
            <div class="checkout-grid">
                
                <!-- Left: Payment Methods -->
                <div class="card">
                    <h2 class="card-title"><i class="bi bi-wallet2" style="color:var(--primary);"></i> Pilih Metode Pembayaran</h2>
                    
                    <div class="pm-group">
                        <span class="pm-label">Virtual Account</span>
                        <div class="pm-options">
                            <div class="pm-option selected" data-method="bca">
                                <div class="pm-info">
                                    <div class="pm-icon"><img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" alt="BCA"></div>
                                    <div class="pm-name">BCA Virtual Account</div>
                                </div>
                                <div class="pm-radio"></div>
                            </div>
                            <div class="pm-option" data-method="mandiri">
                                <div class="pm-info">
                                    <div class="pm-icon"><img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg" alt="Mandiri"></div>
                                    <div class="pm-name">Mandiri Virtual Account</div>
                                </div>
                                <div class="pm-radio"></div>
                            </div>
                        </div>
                    </div>

                    <div class="pm-group">
                        <span class="pm-label">E-Wallet & QRIS</span>
                        <div class="pm-options">
                            <div class="pm-option" data-method="gopay">
                                <div class="pm-info">
                                    <div class="pm-icon"><img src="https://upload.wikimedia.org/wikipedia/commons/8/86/Gopay_logo.svg" alt="GoPay"></div>
                                    <div class="pm-name">GoPay</div>
                                </div>
                                <div class="pm-radio"></div>
                            </div>
                            <div class="pm-option" data-method="qris">
                                <div class="pm-info">
                                    <div class="pm-icon"><img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" alt="QRIS"></div>
                                    <div class="pm-name">QRIS (All Payment Apps)</div>
                                </div>
                                <div class="pm-radio"></div>
                            </div>
                        </div>
                    </div>

                    <div class="pm-group" style="margin-bottom:0;">
                        <span class="pm-label">Kartu Kredit / Debit</span>
                        <div class="pm-options">
                            <div class="pm-option" data-method="cc">
                                <div class="pm-info">
                                    <div class="pm-icon" style="width: auto; gap: 4px;">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa" style="height: 14px;">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" style="height: 18px;">
                                    </div>
                                    <div class="pm-name">Kartu Kredit / Debit</div>
                                </div>
                                <div class="pm-radio"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Order Summary -->
                <div class="card summary-card">
                    <div class="plan-badge">PAKET {{ strtoupper($plan) }}</div>
                    <div class="summary-price">Rp{{ number_format($totalAmount, 0, ',', '.') }}</div>
                    <div class="summary-cycle">Ditagih {{ $cycle == 'monthly' ? 'Bulanan' : 'Tahunan' }}</div>

                    <ul class="summary-list">
                        <li>
                            <span>Membership {{ ucfirst($plan) }}</span>
                            <span>Rp{{ number_format($amount, 0, ',', '.') }}</span>
                        </li>
                        <li>
                            <span>Biaya Admin</span>
                            <span style="color:#10B981;">Gratis</span>
                        </li>
                        <li>
                            <span>Kode Unik</span>
                            <span>Rp{{ number_format($uniqueCode, 0, ',', '.') }}</span>
                        </li>
                        <li class="total">
                            <span>Total Pembayaran</span>
                            <span>Rp{{ number_format($totalAmount, 0, ',', '.') }}</span>
                        </li>
                    </ul>

                    <form id="paymentForm" action="{{ route('payment.process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="method" id="selectedMethod" value="bca">
                        <button type="submit" class="btn-pay" id="payBtn">
                            <span id="payText">Bayar Sekarang</span>
                            <div class="loader" id="payLoader"></div>
                        </button>
                    </form>

                    <div class="secure-badge">
                        <i class="bi bi-shield-check"></i> Pembayaran Anda diamankan dengan enkripsi 256-bit
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Success Overlay -->
    <div class="overlay" id="successOverlay">
        <div class="success-box">
            <div class="success-icon">
                <svg viewBox="0 0 80 80" xmlns="http://www.w3.org/2000/svg">
                    <circle class="check-circle" cx="40" cy="40" r="35"/>
                    <path class="check-path" d="M25 40l10 10 20-20"/>
                </svg>
            </div>
            <h3 class="success-title">Pembayaran Berhasil!</h3>
            <p class="success-desc">Terima kasih, akun keanggotaan Fitness Vals Anda telah aktif.</p>
            <div class="success-redirect">
                <div class="spinner"></div> Mengarahkan ke Dashboard...
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const options = document.querySelectorAll('.pm-option');
            const hiddenMethod = document.getElementById('selectedMethod');
            const form = document.getElementById('paymentForm');
            const payBtn = document.getElementById('payBtn');
            const payText = document.getElementById('payText');
            const payLoader = document.getElementById('payLoader');
            const overlay = document.getElementById('successOverlay');

            // Handle Selection
            options.forEach(opt => {
                opt.addEventListener('click', () => {
                    options.forEach(o => o.classList.remove('selected'));
                    opt.classList.add('selected');
                    hiddenMethod.value = opt.getAttribute('data-method');
                });
            });

            // Handle Submit Simulation
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Show Loading
                payBtn.disabled = true;
                payText.style.display = 'none';
                payLoader.style.display = 'block';

                // Simulate processing delay (2 seconds)
                setTimeout(() => {
                    // Send actual request
                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        },
                        body: new FormData(form)
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Show Success Overlay
                        overlay.classList.add('active');
                        
                        // Redirect after 3 seconds
                        setTimeout(() => {
                            window.location.href = "{{ route('dashboard.home') }}";
                        }, 3000);
                    })
                    .catch(error => {
                        alert('Terjadi kesalahan koneksi.');
                        payBtn.disabled = false;
                        payText.style.display = 'block';
                        payLoader.style.display = 'none';
                    });
                }, 2000);
            });
        });
    </script>
</body>
</html>
