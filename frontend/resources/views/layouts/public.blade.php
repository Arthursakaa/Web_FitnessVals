<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Fitness Vals') — Fitness Vals</title>
    <meta name="description" content="@yield('meta_desc', 'Gym premium di Kota Malang. 8 variasi kelas, personal trainer bersertifikasi, dan fasilitas modern.')">
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    {{-- AOS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
    {{-- Swiper --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    {{-- App CSS --}}
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/public.css') }}">
    @yield('styles')
</head>
<body>
    {{-- NAVBAR --}}
    <nav class="navbar" id="navbar">
        <div class="container">
            <a href="{{ route('home') }}" class="nav-logo">
                <div class="logo-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m13 2 0 6 4 0-7 8 0-6-4 0Z"/></svg></div>
                Fitness Vals
            </a>
            <div class="nav-links" id="navLinks">
                <a href="{{ route('membership') }}" class="{{ request()->routeIs('membership') ? 'active' : '' }}">Membership</a>
                <a href="{{ route('trainers') }}" class="{{ request()->routeIs('trainers*') ? 'active' : '' }}">Personal Trainer</a>
                <a href="{{ route('classes') }}" class="{{ request()->routeIs('classes*') ? 'active' : '' }}">Kelas</a>
                <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">Tentang Kami</a>
                <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Kontak</a>
            </div>
            <div class="nav-actions">
                @auth
                    @php
                        $dashRoute = Auth::user()->role === 'admin' ? route('admin.dashboard') : route('dashboard.home');
                    @endphp
                    <a href="{{ $dashRoute }}" class="btn btn-ghost" style="display:flex; align-items:center; gap:8px; padding:4px 12px; border-radius:var(--radius-full);">
                        <div style="width:32px; height:32px; border-radius:50%; overflow:hidden; border:2px solid var(--color-primary);">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=FF4655&color=fff" alt="Avatar" style="width:100%; height:100%;">
                        </div>
                        <span style="font-size:var(--fs-sm); font-weight:var(--fw-semibold);">Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost" style="font-size:var(--fs-sm);">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-cta btn-sm">Klaim Free Trial</a>
                @endauth
            </div>
            <button class="nav-mobile-toggle" id="mobileToggle" aria-label="Toggle menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <main>@yield('content')</main>

    {{-- FLOATING WHATSAPP --}}
    @php $waNumber = \App\Models\Setting::getVal('contact_whatsapp', '6281122334455'); @endphp
    <a href="https://wa.me/{{ $waNumber }}" class="whatsapp-float" target="_blank" aria-label="WhatsApp">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
    </a>

    {{-- CTA BAR --}}
    <div class="cta-bar" id="ctaBar">
        <p><i class="bi bi-lightning-charge-fill"></i> Klaim Free Trial Gym Premium</p>
        <a href="{{ route('register') }}" class="btn btn-cta btn-sm">Klaim Free Trial →</a>
    </div>

    {{-- FOOTER --}}
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="{{ route('home') }}" class="nav-logo"><div class="logo-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m13 2 0 6 4 0-7 8 0-6-4 0Z"/></svg></div> Fitness Vals</a>
                    <p>{{ \App\Models\Setting::getVal('footer_tagline', 'Gym premium di Kota Malang dengan 8 variasi kelas, personal trainer bersertifikasi, dan fasilitas modern.') }}</p>
                    <div class="footer-social">
                        @php
                            $ig = \App\Models\Setting::getVal('footer_ig', 'https://instagram.com/fitnessvals');
                            $yt = \App\Models\Setting::getVal('footer_yt', 'https://youtube.com/@fitnessvals');
                            $tw = \App\Models\Setting::getVal('footer_tw', '');
                            $fb = \App\Models\Setting::getVal('footer_fb', 'https://facebook.com/fitnessvals');
                        @endphp
                        @if($ig && $ig !== '#')<a href="{{ $ig }}" aria-label="Instagram" target="_blank"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="20" x="2" y="2" rx="5"/><circle cx="12" cy="12" r="5"/><circle cx="17.5" cy="6.5" r="1.5" fill="currentColor" stroke="none"/></svg></a>@endif
                        @if($fb && $fb !== '#')<a href="{{ $fb }}" aria-label="Facebook" target="_blank"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a>@endif
                        @if($yt && $yt !== '#')<a href="{{ $yt }}" aria-label="YouTube" target="_blank"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2.5 17a24.12 24.12 0 0 1 0-10 2 2 0 0 1 1.4-1.4 49.56 49.56 0 0 1 16.2 0A2 2 0 0 1 21.5 7a24.12 24.12 0 0 1 0 10 2 2 0 0 1-1.4 1.4 49.55 49.55 0 0 1-16.2 0A2 2 0 0 1 2.5 17"/><path d="m10 15 5-3-5-3z"/></svg></a>@endif
                        @if($tw && $tw !== '#')<a href="{{ $tw }}" aria-label="Twitter" target="_blank"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/></svg></a>@endif
                    </div>
                </div>
                <div>
                    <h5>Layanan</h5>
                    <ul>
                        <li><a href="{{ route('membership') }}">Membership</a></li>
                        <li><a href="{{ route('trainers') }}">Personal Trainer</a></li>
                        <li><a href="{{ route('classes') }}">Kelas Fitness</a></li>
                    </ul>
                </div>
                <div>
                    <h5>Perusahaan</h5>
                    <ul>
                        <li><a href="{{ route('about') }}">Tentang Kami</a></li>
                        <li><a href="{{ route('contact') }}">Hubungi Kami</a></li>
                        <li><a href="#" onclick="alert('Fitur sedang dikembangkan!'); return false;">Karir</a></li>
                        <li><a href="#" onclick="alert('Fitur sedang dikembangkan!'); return false;">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
                <div>
                    <h5>Jam Operasional</h5>
                    <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:10px; font-size:var(--fs-sm);">
                        <li style="display:flex; justify-content:space-between; border-bottom:1px dashed rgba(255,255,255,0.15); padding-bottom:6px;">
                            <span style="color:rgba(255,255,255,0.7);">Senin - Jumat</span>
                            <span style="font-weight:600; color:#fff;">05:00 - 22:00</span>
                        </li>
                        <li style="display:flex; justify-content:space-between; border-bottom:1px dashed rgba(255,255,255,0.15); padding-bottom:6px;">
                            <span style="color:rgba(255,255,255,0.7);">Sabtu</span>
                            <span style="font-weight:600; color:#fff;">06:00 - 20:00</span>
                        </li>
                        <li style="display:flex; justify-content:space-between; padding-bottom:6px; color:var(--color-primary);">
                            <span style="font-weight:500;">Minggu & Libur</span>
                            <span style="font-weight:600;">08:00 - 14:00</span>
                        </li>
                    </ul>
                    <div style="margin-top:var(--space-5); display:flex; flex-direction:column; gap:8px;">
                        <p style="font-size:var(--fs-xs);color:rgba(255,255,255,0.7);"><i class="bi bi-geo-alt text-primary"></i> {{ \App\Models\Setting::getVal('contact_address', 'Jl. Ijen Boulevard No. 8, Kota Malang 65112') }}</p>
                        <p style="font-size:var(--fs-xs);color:rgba(255,255,255,0.7);"><i class="bi bi-telephone text-primary"></i> {{ \App\Models\Setting::getVal('contact_phone', '+62 811 2233 4455') }}</p>
                        <p style="font-size:var(--fs-xs);color:rgba(255,255,255,0.7);"><i class="bi bi-envelope text-primary"></i> {{ \App\Models\Setting::getVal('contact_email', 'hello@fitnessvals.com') }}</p>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <span>&copy; {{ date('Y') }} Fitness Vals. All rights reserved.</span>
                <span>Dibuat dengan <i class="bi bi-heart-fill" style="color:var(--color-primary); font-size:12px;"></i> di Kota Malang</span>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>AOS.init({ duration: 700, once: true, offset: 80 });</script>
    @yield('scripts')
</body>
</html>
