<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Fitness Vals</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- AOS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @yield('styles')
</head>
<body>
<div class="dash-layout">
    <aside class="dash-sidebar" id="dashSidebar">
        <div class="dash-sidebar-header">
            <div class="dash-sidebar-brand"><i class="bi bi-lightning-charge-fill"></i> Fitness Vals</div>
            <div class="dash-sidebar-sub">Member Dashboard</div>
        </div>
        <div class="dash-sidebar-profile">
            <div class="avatar" style="border:2px solid var(--color-accent);"><img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=FF4655&color=fff" alt=""></div>
            <div class="dash-sidebar-profile-info">
                <h5>{{ Auth::user()->name }}</h5>
                <span style="font-size:10px;color:var(--color-cta);"><i class="bi bi-star-fill" style="font-size:9px;"></i> {{ ucfirst(Auth::user()->plan ?? 'Basic') }} Member</span>
            </div>
        </div>
        <nav class="dash-nav">
            <div class="dash-nav-label">Main Menu</div>
            <a href="{{ route('dashboard.home') }}" class="dash-nav-item {{ request()->routeIs('dashboard.home') ? 'active' : '' }}"><i class="bi bi-grid-1x2"></i> Dashboard</a>
            <a href="{{ route('dashboard.progress') }}" class="dash-nav-item {{ request()->routeIs('dashboard.progress') ? 'active' : '' }}"><i class="bi bi-graph-up-arrow"></i> Progress Latihan</a>
            <a href="{{ route('dashboard.nutrition') }}" class="dash-nav-item {{ request()->routeIs('dashboard.nutrition') ? 'active' : '' }}"><i class="bi bi-fire"></i> Kalori & Nutrisi</a>
            <a href="{{ route('dashboard.meals') }}" class="dash-nav-item {{ request()->routeIs('dashboard.meals') ? 'active' : '' }}"><i class="bi bi-stars"></i> Rekomendasi AI</a>
            <div class="dash-nav-label">Program</div>
            <a href="{{ route('dashboard.schedule') }}" class="dash-nav-item {{ request()->routeIs('dashboard.schedule') ? 'active' : '' }}"><i class="bi bi-calendar3"></i> Jadwal Kelas</a>
            <a href="{{ route('dashboard.trainers') }}" class="dash-nav-item {{ request()->routeIs('dashboard.trainers') ? 'active' : '' }}"><i class="bi bi-person-badge"></i> Personal Trainer</a>
            <a href="{{ route('dashboard.bmi') }}" class="dash-nav-item {{ request()->routeIs('dashboard.bmi') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Cek BMI</a>
            <div class="dash-nav-divider"></div>
            <a href="{{ route('dashboard.profile') }}" class="dash-nav-item {{ request()->routeIs('dashboard.profile') ? 'active' : '' }}"><i class="bi bi-person-circle"></i> Profil Saya</a>
            <button onclick="if(window.restartTour) window.restartTour(); else window.location.href='{{ route('dashboard.home') }}';" class="dash-nav-item" style="width:100%;text-align:left;background:none;border:none;cursor:pointer;"><i class="bi bi-info-circle"></i> Panduan Website</button>
        </nav>
        <div class="dash-sidebar-footer">
            <a href="{{ route('home') }}" class="dash-nav-item"><i class="bi bi-globe2"></i> Kembali ke Website</a>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="dash-nav-item" style="color:rgba(239,68,68,0.7);background:transparent;border:none;width:100%;text-align:left;cursor:pointer;">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="dash-main">
        <header class="dash-header">
            <div class="dash-header-left">
                <button class="btn-icon" id="sidebarToggle" style="display:none;margin-right:var(--space-3);" aria-label="Menu"><i class="bi bi-list"></i></button>
                <h2>@yield('page_title', 'Dashboard')</h2>
                <p>@yield('page_subtitle', date('l, d F Y'))</p>
            </div>
            <div class="dash-header-right">
                <button class="btn-icon" style="position:relative;"><i class="bi bi-bell"></i><span style="position:absolute;top:6px;right:6px;width:8px;height:8px;background:var(--color-danger);border-radius:50%;border:2px solid #fff;"></span></button>
                <button class="btn-icon"><i class="bi bi-gear"></i></button>
                <div class="avatar" style="border:2px solid var(--color-accent);"><img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=FF4655&color=fff" alt=""></div>
            </div>
        </header>
        <div class="dash-content">
            @yield('content')
        </div>
    </div>
</div>
@if(!Auth::user()->profile || !Auth::user()->profile->weight_kg)
{{-- NEW USER ONBOARDING WIZARD --}}
<div class="modal-overlay active" id="onboardingWizard" style="z-index: 99999; backdrop-filter: blur(8px); display:flex; pointer-events:auto; background: rgba(15, 23, 42, 0.75);">
    <div class="modal active" style="max-width: 480px; padding:0; overflow:hidden; pointer-events:auto; border-radius: var(--radius-xl); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); transform: translateY(0);">
        <div style="padding:var(--space-6) var(--space-5) var(--space-5); background: #ffffff;">
            
            <div style="text-align:center; margin-bottom:var(--space-5);">
                <div style="width:64px; height:64px; background:linear-gradient(135deg, var(--color-primary), var(--color-accent)); color:white; border-radius:20px; display:flex; align-items:center; justify-content:center; font-size:28px; margin:0 auto var(--space-3); box-shadow: 0 10px 15px -3px rgba(255,107,44,0.3); transform: rotate(-5deg);">
                    <i class="bi bi-person-lines-fill" style="transform: rotate(5deg);"></i>
                </div>
                <h3 style="font-family:var(--font-heading); color:var(--color-text); margin-bottom:var(--space-1); font-size:22px; font-weight:800;">Personalisasi Profil</h3>
                <p class="text-muted" style="font-size:var(--fs-sm); max-width:90%; margin:0 auto; line-height:1.5;">Bantu AI mempelajari kondisi tubuhmu agar dapat menyusun kalori dan nutrisi yang paling tepat.</p>
            </div>

            <!-- Progress Dots -->
            <div style="display:flex; justify-content:center; gap:6px; margin-bottom:var(--space-5);">
                <div id="dot-1" style="height:6px; width:24px; border-radius:3px; background:var(--color-primary); transition:0.3s;"></div>
                <div id="dot-2" style="height:6px; width:24px; border-radius:3px; background:var(--color-border); transition:0.3s;"></div>
                <div id="dot-3" style="height:6px; width:24px; border-radius:3px; background:var(--color-border); transition:0.3s;"></div>
            </div>

            <form action="{{ route('dashboard.onboarding.store') }}" method="POST" id="onboardingForm">
                @csrf
                
                {{-- STEP 1 --}}
                <div class="wizard-step" id="wizard-step-1" style="animation: tourPop 0.3s ease-out;">
                    <div class="form-row">
                        <div class="form-group"><label class="form-label" style="font-weight:600;">Jenis Kelamin</label><select name="gender" class="form-input form-select" required style="background-color:var(--color-bg-alt);"><option value="">Pilih...</option><option value="male">Laki-laki</option><option value="female">Perempuan</option></select></div>
                        <div class="form-group"><label class="form-label" style="font-weight:600;">Usia</label><input type="number" name="age" class="form-input" required min="10" max="100" placeholder="Cth: 25" style="background-color:var(--color-bg-alt);"></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label" style="font-weight:600;">Tinggi Badan (cm)</label><input type="number" name="height_cm" class="form-input" required min="100" max="250" placeholder="Cth: 170" style="background-color:var(--color-bg-alt);"></div>
                        <div class="form-group"><label class="form-label" style="font-weight:600;">Berat Badan (kg)</label><input type="number" name="weight_kg" step="0.1" class="form-input" required min="30" max="200" placeholder="Cth: 65" style="background-color:var(--color-bg-alt);"></div>
                    </div>
                    <div style="margin-top:var(--space-5);">
                        <button type="button" class="btn btn-primary btn-block" onclick="nextWizardStep(2)" style="height:48px; font-weight:600;">Lanjutkan</button>
                    </div>
                </div>

                {{-- STEP 2 --}}
                <div class="wizard-step" id="wizard-step-2" style="display:none; animation: tourPop 0.3s ease-out;">
                    <div class="form-group">
                        <label class="form-label" style="font-weight:600;">Tujuan Utama</label>
                        <select name="fitness_goal" class="form-input form-select" required style="background-color:var(--color-bg-alt);">
                            <option value="">Pilih tujuan Anda...</option>
                            <option value="Fat Loss">Fat Loss (Turun Berat Badan)</option>
                            <option value="Muscle Gain">Muscle Gain (Bentuk Otot)</option>
                            <option value="Maintenance">Maintenance (Jaga Bentuk)</option>
                            <option value="Endurance">Endurance (Ketahanan Fisik)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="font-weight:600;">Aktivitas Harian</label>
                        <select class="form-input form-select" name="activity_level_multiplier" required style="background-color:var(--color-bg-alt);">
                            <option value="1.2">Jarang Olahraga (Pekerja Kantoran)</option>
                            <option value="1.375">Ringan (Olahraga 1-3x seminggu)</option>
                            <option value="1.55">Sedang (Olahraga 3-5x seminggu)</option>
                            <option value="1.725">Sangat Aktif (Olahraga tiap hari)</option>
                        </select>
                    </div>
                    <div style="display:flex; gap:var(--space-3); margin-top:var(--space-5);">
                        <button type="button" class="btn btn-outline" onclick="nextWizardStep(1)" style="flex:1; height:48px; font-weight:600;">Kembali</button>
                        <button type="button" class="btn btn-primary" onclick="nextWizardStep(3)" style="flex:2; height:48px; font-weight:600;">Lanjutkan</button>
                    </div>
                </div>

                {{-- STEP 3 --}}
                <div class="wizard-step" id="wizard-step-3" style="display:none; animation: tourPop 0.3s ease-out;">
                    <div class="form-group">
                        <label class="form-label" style="font-weight:600;">Preferensi Makanan</label>
                        <select class="form-input form-select" name="dietary_preference" required style="background-color:var(--color-bg-alt);">
                            <option value="Normal">Normal (Bebas makan apa saja)</option>
                            <option value="Halal">Halal Only</option>
                            <option value="Vegetarian">Vegetarian</option>
                            <option value="Vegan">Vegan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="font-weight:600; margin-bottom:var(--space-2);">Kondisi Medis & Alergi</label>
                        <div style="display:flex;flex-wrap:wrap;gap:8px; max-height:160px; overflow-y:auto; padding:var(--space-2) 0;">
                            @foreach(['Tidak Ada', 'Diabetes', 'Hipertensi', 'Kolesterol', 'Asam Urat', 'Penyakit Jantung', 'Maag/GERD', 'Low Sodium', 'Alergi Kacang', 'Alergi Seafood', 'Laktosa Intoleran', 'Alergi Telur', 'Alergi Gluten'] as $d)
                            <label class="badge badge-outline" style="cursor:pointer; padding:8px 14px; margin:0; border-radius:20px; border-color:var(--color-border); color:var(--color-text); font-weight:500;">
                                <input type="checkbox" name="medical_history[]" value="{{ $d }}" style="margin-right:6px;" {{ $d=='Tidak Ada' ? 'checked' : '' }}> {{ $d }}
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div style="display:flex; gap:var(--space-3); margin-top:var(--space-5);">
                        <button type="button" class="btn btn-outline" onclick="nextWizardStep(2)" style="flex:1; height:48px; font-weight:600;">Kembali</button>
                        <button type="submit" class="btn btn-primary" style="flex:2; height:48px; font-weight:600;">Simpan Profil <i class="bi bi-check2"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function nextWizardStep(step) {
        // Validation
        if (step === 2) {
            const form = document.getElementById('wizard-step-1');
            const inputs = form.querySelectorAll('input, select');
            let valid = true;
            inputs.forEach(i => { if(!i.checkValidity()) { i.reportValidity(); valid = false; }});
            if(!valid) return;
        } else if (step === 3) {
            const form = document.getElementById('wizard-step-2');
            const inputs = form.querySelectorAll('input, select');
            let valid = true;
            inputs.forEach(i => { if(!i.checkValidity()) { i.reportValidity(); valid = false; }});
            if(!valid) return;
        }

        document.querySelectorAll('.wizard-step').forEach(el => el.style.display = 'none');
        document.getElementById('wizard-step-' + step).style.display = 'block';
        
        // Update dots
        for(let i=1; i<=3; i++) {
            document.getElementById('dot-' + i).style.background = i <= step ? 'var(--color-primary)' : 'var(--color-border)';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Toggle selected badge styling
        document.querySelectorAll('input[name="medical_history[]"]').forEach(cb => {
            
            const updateBadge = (checkbox) => {
                if(checkbox.checked) {
                    checkbox.parentElement.style.background = 'var(--color-primary)';
                    checkbox.parentElement.style.color = '#fff';
                    checkbox.parentElement.style.borderColor = 'var(--color-primary)';
                } else {
                    checkbox.parentElement.style.background = 'transparent';
                    checkbox.parentElement.style.color = 'var(--color-text)';
                    checkbox.parentElement.style.borderColor = 'var(--color-border)';
                }
            };
            
            // Init styles
            updateBadge(cb);

            cb.addEventListener('change', function() {
                if (this.value !== 'Tidak Ada' && this.checked) {
                    const noEl = document.querySelector('input[value="Tidak Ada"]');
                    noEl.checked = false;
                    updateBadge(noEl);
                } else if (this.value === 'Tidak Ada' && this.checked) {
                    document.querySelectorAll('input[name="medical_history[]"]:not([value="Tidak Ada"])').forEach(otherCb => {
                        otherCb.checked = false;
                        updateBadge(otherCb);
                    });
                }
                updateBadge(this);
            });
        });
        
        // Prevent closing wizard
        const wizardModal = document.getElementById('onboardingWizard');
        if (wizardModal) {
            wizardModal.addEventListener('click', function(e) { e.stopPropagation(); });
        }
    });
</script>
@endif
@vite(['resources/js/app.js'])
<script>
// ==========================================
// GLOBAL INTERACTIVE ONBOARDING TOUR ENGINE
// ==========================================
let currentTourSteps = [];
let currentTourIndex = 0;
let currentTourStorageKey = '';
let tourOverlay, tourTooltip;

function initPageTour(steps, storageKey) {
    if (!steps || steps.length === 0) return;
    
    currentTourSteps = steps;
    currentTourStorageKey = storageKey;
    currentTourIndex = 0;

    // Create DOM elements if they don't exist
    if (!document.querySelector('.tour-overlay')) {
        tourOverlay = document.createElement('div');
        tourOverlay.className = 'tour-overlay';
        document.body.appendChild(tourOverlay);
        
        tourTooltip = document.createElement('div');
        tourTooltip.className = 'tour-tooltip card';
        document.body.appendChild(tourTooltip);
    }

    // Auto-start if not completed yet
    if (!localStorage.getItem(storageKey)) {
        setTimeout(() => {
            showTourStep(0);
        }, 1000);
    }
}

function showTourStep(index) {
    if (index >= currentTourSteps.length) {
        endTour();
        return;
    }

    currentTourIndex = index;

    // Remove previous highlights
    document.querySelectorAll('.tour-highlight').forEach(el => {
        el.classList.remove('tour-highlight');
        el.style.zIndex = '';
        el.style.position = '';
        el.style.background = '';
    });

    const step = currentTourSteps[index];
    const targetEl = document.querySelector(step.target);

    if (!targetEl) {
        showTourStep(index + 1); // Skip if element not found
        return;
    }

    // Highlight target element
    targetEl.classList.add('tour-highlight');
    const style = window.getComputedStyle(targetEl);
    if(style.position === 'static') targetEl.style.position = 'relative';
    targetEl.style.zIndex = '10000';
    if(style.backgroundColor === 'rgba(0, 0, 0, 0)' || style.backgroundColor === 'transparent') {
        targetEl.style.backgroundColor = '#ffffff';
    }

    // Render Tooltip
    tourTooltip.innerHTML = `
        <div style="margin-bottom:15px;">
            <h4 style="margin-bottom:5px; color:var(--color-primary); font-size:16px;">${step.title}</h4>
            <p style="font-size:13px; color:var(--color-text-muted); line-height:1.5; margin:0;">${step.text}</p>
        </div>
        <div style="display:flex; justify-content:space-between; align-items:center;">
            <span style="font-size:11px; color:var(--color-text-muted); font-weight:600;">Langkah ${index + 1} dari ${currentTourSteps.length}</span>
            <div style="display:flex; gap:8px;">
                ${index > 0 ? `<button class="btn btn-ghost btn-sm" id="tour-prev" style="padding:4px 10px;">Kembali</button>` : `<button class="btn btn-ghost btn-sm" id="tour-skip" style="padding:4px 10px; color:var(--color-text-muted);">Lewati</button>`}
                <button class="btn btn-primary btn-sm" id="tour-next" style="padding:4px 15px;">${index === currentTourSteps.length - 1 ? 'Selesai! 🎉' : 'Lanjut ➔'}</button>
            </div>
        </div>
    `;

    tourTooltip.style.display = 'block';
    tourOverlay.style.display = 'block';

    // Calculate position
    const rect = targetEl.getBoundingClientRect();
    let top = rect.bottom + window.scrollY + 15;
    let left = rect.left + window.scrollX;

    if (step.position === 'left') {
        top = rect.top + window.scrollY;
        left = rect.left + window.scrollX - 320 - 15;
    } else if (step.position === 'right') {
        top = rect.top + window.scrollY + 50;
        left = rect.right + window.scrollX + 15;
    }

    // Prevent tooltip from going off-screen
    if (left < 10) left = 10;
    if (left + 320 > window.innerWidth) left = window.innerWidth - 330;
    
    tourTooltip.style.top = top + 'px';
    tourTooltip.style.left = left + 'px';

    // Scroll into view
    targetEl.scrollIntoView({ behavior: 'smooth', block: 'center' });

    // Event Listeners
    if(document.getElementById('tour-next')) document.getElementById('tour-next').onclick = () => showTourStep(index + 1);
    if(document.getElementById('tour-prev')) document.getElementById('tour-prev').onclick = () => showTourStep(index - 1);
    if(document.getElementById('tour-skip')) document.getElementById('tour-skip').onclick = endTour;
}

function endTour() {
    if(tourOverlay) tourOverlay.style.display = 'none';
    if(tourTooltip) tourTooltip.style.display = 'none';
    document.querySelectorAll('.tour-highlight').forEach(el => {
        el.classList.remove('tour-highlight');
        el.style.zIndex = '';
        el.style.position = '';
        el.style.background = '';
    });
    if(currentTourStorageKey) {
        localStorage.setItem(currentTourStorageKey, 'true');
    }
}

// Global restart function
window.restartTour = function() {
    if(currentTourSteps.length > 0) {
        showTourStep(0);
    } else {
        Swal.fire({
            icon: 'info',
            title: 'Info Panduan',
            text: 'Tidak ada panduan interaktif khusus untuk halaman ini.',
            confirmButtonColor: 'var(--color-primary)'
        });
    }
};
</script>
@yield('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal Toggle Logic
    document.querySelectorAll('[data-modal]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const modalId = this.getAttribute('data-modal');
            const modal = document.getElementById(modalId);
            if(modal) {
                modal.classList.add('active');
            }
        });
    });

    document.querySelectorAll('[data-close-modal]').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const modal = this.closest('.modal-overlay');
            if(modal) {
                modal.classList.remove('active');
            }
        });
    });

    // Close on overlay click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if(e.target === this) {
                this.classList.remove('active');
            }
        });
    });

    // Initialize AOS
    AOS.init({
        duration: 800,
        once: true,
        offset: 50,
    });
});
</script>
<style>
/* CSS for Global Tour */
.tour-overlay {
    position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(3px);
    z-index: 9999;
    display: none;
    transition: opacity 0.3s;
}
.tour-tooltip {
    position: absolute;
    width: 320px;
    z-index: 10001;
    padding: 20px;
    border-left: 4px solid var(--color-primary);
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    display: none;
    animation: tourPop 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.tour-highlight {
    box-shadow: 0 0 0 4px #fff, 0 0 0 8px var(--color-primary) !important;
    border-radius: var(--radius-lg);
    transition: all 0.3s;
}
@keyframes tourPop {
    0% { transform: scale(0.8) translateY(20px); opacity: 0; }
    100% { transform: scale(1) translateY(0); opacity: 1; }
}
</style>
</body>
</html>
