@extends('layouts.public')
@section('title', 'Hubungi Kami')

@section('content')
<section class="page-hero">
    <div class="container" data-aos="fade-up">
        <div class="section-tag" style="color:var(--color-cta);"><i class="bi bi-telephone"></i> KONTAK</div>
        <h1>Hubungi Kami</h1>
        <p>Ada pertanyaan? Tim kami siap membantu kamu kapanpun.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        {{-- CONTACT CARDS --}}
        <div class="grid-3" style="margin-bottom:var(--space-12);" data-aos="fade-up">
            @foreach([
                ['icon'=>'bi-telephone','title'=>'Telepon','val'=>\App\Models\Setting::getVal('contact_phone', '+62 812-3456-7890'),'sub'=>'Sen-Jum, 08:00-20:00'],
                ['icon'=>'bi-envelope','title'=>'Email','val'=>\App\Models\Setting::getVal('contact_email', 'info@fitnessvals.com'),'sub'=>'Respon dalam 24 jam'],
                ['icon'=>'bi-geo-alt','title'=>'Lokasi','val'=>\App\Models\Setting::getVal('contact_address', 'Jl. Ijen Boulevard No. 8, Kota Malang 65112'),'sub'=>'Indonesia'],
            ] as $c)
            <div class="feature-card">
                <div class="feature-icon"><i class="bi {{ $c['icon'] }}" style="font-size:24px;color:var(--color-primary);"></i></div>
                <h4>{{ $c['title'] }}</h4>
                <p style="font-weight:var(--fw-semibold);color:var(--color-text);margin-bottom:var(--space-1);">{{ $c['val'] }}</p>
                <p>{{ $c['sub'] }}</p>
            </div>
            @endforeach
        </div>

        <div class="grid-2" style="gap:var(--space-8);">
            {{-- FORM --}}
            <div data-aos="fade-right">
                <div class="section-tag"><i class="bi bi-chat-dots"></i> KIRIM PESAN</div>
                <h3 style="font-size:var(--fs-2xl);margin-bottom:var(--space-6);">Ada Pertanyaan? Hubungi Kami</h3>
                <form>
                    <div class="form-row">
                        <div class="form-group"><label class="form-label">Nama Lengkap</label><input type="text" class="form-input" placeholder="Nama kamu" required></div>
                        <div class="form-group"><label class="form-label">No. HP / WhatsApp</label><input type="tel" class="form-input" placeholder="+62 8xx-xxxx-xxxx"></div>
                    </div>
                    <div class="form-group"><label class="form-label">Email</label><input type="email" class="form-input" placeholder="email@example.com" required></div>
                    <div class="form-group"><label class="form-label">Subjek</label>
                        <select class="form-input form-select">
                            <option>Informasi Membership</option><option>Personal Trainer</option><option>Jadwal Kelas</option><option>Kerjasama/Partnership</option><option>Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group"><label class="form-label">Pesan</label><textarea class="form-input" rows="5" placeholder="Tulis pesan kamu..." required></textarea></div>
                    <button type="submit" class="btn btn-primary btn-lg">Kirim Pesan <i class="bi bi-send" style="margin-left:8px;"></i></button>
                </form>
            </div>

            {{-- MAP + HOURS --}}
            <div data-aos="fade-left">
                <div style="border-radius:var(--radius-2xl);overflow:hidden;margin-bottom:var(--space-6);box-shadow:var(--shadow-lg);">
                    <iframe src="{{ \App\Models\Setting::getVal('contact_map_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.1026048187895!2d112.6200155!3d-7.9780517!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78829c991e6dc3%3A0x7d0180db1e0750eb!2sJl.%20Besar%20Ijen%2C%20Kota%20Malang%2C%20Jawa%20Timur!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid') }}" width="100%" height="280" style="border:0;display:block;" allowfullscreen loading="lazy"></iframe>
                </div>
                <div class="card">
                    <h4 style="margin-bottom:var(--space-4);display:flex;align-items:center;gap:var(--space-2);"><span style="font-style:normal;">🕐</span> Jam Operasional</h4>
                    @foreach(['Senin - Jumat'=>['05:00 - 22:00',true],'Sabtu'=>['06:00 - 20:00',true],'Minggu'=>['08:00 - 14:00',false]] as $day => $info)
                    <div class="flex-between" style="padding:var(--space-3) 0;border-bottom:1px solid var(--color-border-light);">
                        <span style="font-size:var(--fs-sm);font-weight:var(--fw-medium);">{{ $day }}</span>
                        <div style="display:flex;align-items:center;gap:var(--space-2);">
                            <span style="font-size:var(--fs-sm);">{{ $info[0] }}</span>
                            @if($info[1])<span class="badge badge-success" style="font-size:9px;">Buka</span>@else<span class="badge badge-warning" style="font-size:9px;">Terbatas</span>@endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container" data-aos="fade-up">
        <h2>Atau Langsung Kunjungi Kami</h2>
        <p>Datang langsung ke gym terdekat dan rasakan pengalaman Fitness Vals secara gratis!</p>
        <a href="{{ route('register') }}" class="btn btn-cta btn-lg">Klaim Free Trial →</a>
    </div>
</section>
@endsection
