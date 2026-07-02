@extends('layouts.public')
@section('title', 'Membership')

@section('content')
<section class="page-hero">
    <div class="container" data-aos="fade-up">
        <div class="section-tag" style="color:var(--color-cta);"><i class="bi bi-star-fill"></i> MEMBERSHIP</div>
        <h1>Pilih Paket Membership<br>Terbaikmu</h1>
        <p>Investasi terbaik untuk kesehatan dan kebugaran jangka panjangmu. Mulai dari Rp299K/bulan.</p>
    </div>
</section>

{{-- PRICING CARDS --}}
<section class="section">
    <div class="container">
        
        <div style="display:flex;flex-direction:column;align-items:center;margin-bottom:var(--space-10);" data-aos="fade-up">
            <div style="background:var(--color-bg-alt);padding:6px;border-radius:100px;display:inline-flex;align-items:center;border:1px solid var(--color-border-light);">
                <button id="btnMonthly" class="btn" style="background:var(--color-primary);color:#fff;border-radius:100px;padding:10px 28px;box-shadow:0 4px 10px rgba(0,0,0,0.1);font-weight:600;border:none;">Bulanan</button>
                <button id="btnYearly" class="btn btn-ghost" style="color:var(--color-text-muted);border-radius:100px;padding:10px 28px;font-weight:600;border:none;transition:all 0.3s ease;">Tahunan <span style="background:#FEF3C7;color:#D97706;padding:3px 10px;border-radius:100px;font-size:10px;margin-left:6px;font-weight:800;letter-spacing:0.5px;">HEMAT 20%</span></button>
            </div>
            <p style="font-size:var(--fs-sm);color:var(--color-text-muted);margin-top:var(--space-3);">*Harga tahunan ditagih di awal untuk 12 bulan</p>
        </div>

        <div class="grid-3" data-aos="fade-up">
            {{-- BASIC --}}
            <div class="pricing-card" style="border:1px solid var(--color-border);">
                <div style="font-size:40px;margin-bottom:var(--space-4);color:var(--color-text-muted);"><i class="bi bi-person-walking"></i></div>
                <h4>Basic</h4>
                <p class="text-muted" style="font-size:var(--fs-sm);margin-bottom:var(--space-5);">Untuk pemula yang ingin memulai perjalanan fitness</p>
                <div class="price"><span class="price-val" data-monthly="299K" data-yearly="239K">Rp299K</span><span style="font-size:var(--fs-sm);color:var(--color-text-muted);font-weight:normal;">/bulan</span></div>
                <ul class="pricing-features">
                    <li><span class="check"><i class="bi bi-check-lg"></i></span> Akses gym area utama</li>
                    <li><span class="check"><i class="bi bi-check-lg"></i></span> Locker harian</li>
                    <li><span class="check"><i class="bi bi-check-lg"></i></span> Cek BMI & tracking dasar</li>
                    <li><span class="check"><i class="bi bi-check-lg"></i></span> Wi-Fi gratis</li>
                    <li class="disabled"><span>—</span> Akses kelas grup</li>
                    <li class="disabled"><span>—</span> Personal Trainer</li>
                    <li class="disabled"><span>—</span> AI Meal Recommendation</li>
                </ul>
                <a href="{{ route('register') }}?plan=basic" class="btn btn-outline btn-block btn-lg">Pilih Basic</a>
            </div>

            {{-- PRO --}}
            <div class="pricing-card popular" style="position:relative; transform:scale(1.05); z-index:2; box-shadow:0 20px 40px rgba(0,0,0,0.15);">
                <div style="position:absolute;top:-15px;left:50%;transform:translateX(-50%);background:var(--color-accent);color:#fff;padding:6px 20px;border-radius:100px;font-size:11px;font-weight:800;letter-spacing:1px;text-transform:uppercase;box-shadow:0 4px 10px rgba(255,107,44,0.3);white-space:nowrap;"><i class="bi bi-star-fill" style="margin-right:4px;"></i> Paling Diminati</div>
                <div style="font-size:40px;margin-bottom:var(--space-4);color:var(--color-accent);"><i class="bi bi-fire"></i></div>
                <h4 style="color:#fff;">Pro</h4>
                <p style="color:rgba(255,255,255,0.7);font-size:var(--fs-sm);margin-bottom:var(--space-5);">Paket dengan value terbaik untuk member aktif</p>
                <div class="price" style="color:#fff;"><span class="price-val" data-monthly="549K" data-yearly="439K">Rp549K</span><span style="font-size:var(--fs-sm);color:rgba(255,255,255,0.5);font-weight:normal;">/bulan</span></div>
                <ul class="pricing-features" style="border-color:rgba(255,255,255,0.1);">
                    <li style="border-color:rgba(255,255,255,0.1);color:rgba(255,255,255,0.9);"><span class="check" style="background:rgba(255,107,44,0.2);color:var(--color-accent);"><i class="bi bi-check-lg"></i></span> Semua fitur Basic</li>
                    <li style="border-color:rgba(255,255,255,0.1);color:rgba(255,255,255,0.9);"><span class="check" style="background:rgba(255,107,44,0.2);color:var(--color-accent);"><i class="bi bi-check-lg"></i></span> 8 kelas grup unlimited</li>
                    <li style="border-color:rgba(255,255,255,0.1);color:rgba(255,255,255,0.9);"><span class="check" style="background:rgba(255,107,44,0.2);color:var(--color-accent);"><i class="bi bi-check-lg"></i></span> AI Meal Recommendation</li>
                    <li style="border-color:rgba(255,255,255,0.1);color:rgba(255,255,255,0.9);"><span class="check" style="background:rgba(255,107,44,0.2);color:var(--color-accent);"><i class="bi bi-check-lg"></i></span> Full progress dashboard</li>
                    <li style="border-color:rgba(255,255,255,0.1);color:rgba(255,255,255,0.9);"><span class="check" style="background:rgba(255,107,44,0.2);color:var(--color-accent);"><i class="bi bi-check-lg"></i></span> Nutrisi tracking lengkap</li>
                    <li style="border-color:rgba(255,255,255,0.1);color:rgba(255,255,255,0.9);"><span class="check" style="background:rgba(255,107,44,0.2);color:var(--color-accent);"><i class="bi bi-check-lg"></i></span> Locker permanen & Handuk</li>
                </ul>
                <a href="{{ route('register') }}?plan=pro" class="btn btn-cta btn-block btn-lg" style="box-shadow:0 8px 20px rgba(255,107,44,0.3);">Lanjut ke Pro <i class="bi bi-arrow-right"></i></a>
            </div>

            {{-- ELITE --}}
            <div class="pricing-card" style="border:2px solid #1E293B; background:linear-gradient(180deg, #ffffff 0%, #F8FAFC 100%);position:relative;">
                <div style="position:absolute;top:16px;right:20px;color:#1E293B;font-size:24px;opacity:0.2;"><i class="bi bi-gem"></i></div>
                <div style="font-size:40px;margin-bottom:var(--space-4);color:#1E293B;"><i class="bi bi-trophy-fill"></i></div>
                <h4>Elite</h4>
                <p class="text-muted" style="font-size:var(--fs-sm);margin-bottom:var(--space-5);">Premium tanpa batas dengan privasi & hasil maksimal</p>
                <div class="price"><span class="price-val" data-monthly="899K" data-yearly="719K">Rp899K</span><span style="font-size:var(--fs-sm);color:var(--color-text-muted);font-weight:normal;">/bulan</span></div>
                <ul class="pricing-features">
                    <li><span class="check"><i class="bi bi-check-lg"></i></span> Semua fitur Pro</li>
                    <li><span class="check" style="background:#1E293B;color:#fff;"><i class="bi bi-check-lg"></i></span> 4x Personal Trainer/bulan</li>
                    <li><span class="check" style="background:#1E293B;color:#fff;"><i class="bi bi-check-lg"></i></span> Recovery zone & sauna</li>
                    <li><span class="check"><i class="bi bi-check-lg"></i></span> Guest pass 2x/bulan</li>
                    <li><span class="check"><i class="bi bi-check-lg"></i></span> Konsultasi nutrisi 1-on-1</li>
                    <li><span class="check"><i class="bi bi-check-lg"></i></span> Priority class booking</li>
                </ul>
                <a href="{{ route('register') }}?plan=elite" class="btn btn-dark btn-block btn-lg">Pilih Elite</a>
            </div>
        </div>
    </div>
</section>

{{-- COMPARISON TABLE --}}
<section class="section" style="background:var(--color-bg-alt);">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>Bandingkan Semua Fitur</h2>
            <p>Detail lengkap setiap paket membership</p>
        </div>
        <div class="table-wrap" data-aos="fade-up">
            <table class="table" style="text-align:center;">
                <thead><tr><th style="text-align:left;">Fitur</th><th>Basic</th><th style="background:var(--color-accent-light);">Pro <i class="bi bi-star-fill"></i></th><th>Elite</th></tr></thead>
                <tbody>
                    @foreach([
                        ['Akses Gym Area Utama','<i class="bi bi-check-lg"></i>','<i class="bi bi-check-lg"></i>','<i class="bi bi-check-lg"></i>'],['Locker','Harian','Permanen','Premium'],['Kelas Grup (8 variasi)','—','Unlimited','Unlimited + Priority'],['Nutrisi Tracking','Dasar','Full + AI','Full + Konsultasi'],['Personal Trainer','—','—','4x/bulan'],['Recovery Zone & Sauna','—','—','<i class="bi bi-check-lg"></i>'],['Progress Dashboard','Dasar','Full','Full + Report'],['Guest Pass','—','—','2x/bulan'],['Parking','—','—','Gratis'],['Support','Email','Chat','Priority 24/7'],
                    ] as $r)
                    <tr>
                        <td style="text-align:left;font-weight:var(--fw-medium);">{{ $r[0] }}</td>
                        <td style="font-size:var(--fs-sm);">{!! $r[1] !!}</td>
                        <td style="font-size:var(--fs-sm);background:rgba(26,155,158,0.03);">{!! $r[2] !!}</td>
                        <td style="font-size:var(--fs-sm);">{!! $r[3] !!}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- TESTIMONIALS --}}
<section class="section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <div class="section-tag"><i class="bi bi-chat-quote-fill"></i> KATA MEREKA</div>
            <h2>Lebih dari 150 Member Puas</h2>
        </div>
        <div class="grid-3" data-aos="fade-up">
            <div class="card" style="background:var(--color-bg-alt);border:none;">
                <div style="color:var(--color-accent);margin-bottom:var(--space-3);"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                <p style="font-style:italic;margin-bottom:var(--space-5);">"Paket Pro benar-benar worth it! Kelasnya bervariasi banget dari yoga sampai HIIT, dan trainernya sangat ramah buat pemula seperti saya."</p>
                <div style="display:flex;align-items:center;gap:var(--space-3);">
                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop" style="width:48px;height:48px;border-radius:50%;object-fit:cover;">
                    <div><h5 style="margin-bottom:2px;">Sarah D.</h5><p style="font-size:var(--fs-xs);color:var(--color-text-muted);">Member Pro (6 Bulan)</p></div>
                </div>
            </div>
            <div class="card" style="background:var(--color-bg-alt);border:none;">
                <div style="color:var(--color-accent);margin-bottom:var(--space-3);"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                <p style="font-style:italic;margin-bottom:var(--space-5);">"Fasilitas recovery zone di paket Elite menyelamatkan otot saya setelah leg day. Lingkungan gymnya bersih, alatnya lengkap & premium."</p>
                <div style="display:flex;align-items:center;gap:var(--space-3);">
                    <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&h=100&fit=crop" style="width:48px;height:48px;border-radius:50%;object-fit:cover;">
                    <div><h5 style="margin-bottom:2px;">Kevin M.</h5><p style="font-size:var(--fs-xs);color:var(--color-text-muted);">Member Elite (1 Tahun)</p></div>
                </div>
            </div>
            <div class="card" style="background:var(--color-bg-alt);border:none;">
                <div style="color:var(--color-accent);margin-bottom:var(--space-3);"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                <p style="font-style:italic;margin-bottom:var(--space-5);">"Awalnya ragu ambil Basic, tapi ternyata alatnya lengkap banget. Aplikasi tracking nutrisinya juga sangat membantu journey diet saya."</p>
                <div style="display:flex;align-items:center;gap:var(--space-3);">
                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&h=100&fit=crop" style="width:48px;height:48px;border-radius:50%;object-fit:cover;">
                    <div><h5 style="margin-bottom:2px;">Anya W.</h5><p style="font-size:var(--fs-xs);color:var(--color-text-muted);">Member Basic (3 Bulan)</p></div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- STEPS --}}
<section class="section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <div class="section-tag"><i class="bi bi-signpost-2"></i> CARA BERGABUNG</div>
            <h2>4 Langkah Mudah</h2>
        </div>
        <div class="grid-4" data-aos="fade-up">
            @foreach([
                ['step'=>'01','icon'=>'bi-plus-lg','title'=>'Daftar Online','desc'=>'Isi form pendaftaran di website atau aplikasi.'],
                ['step'=>'02','icon'=>'bi-credit-card','title'=>'Pilih Paket','desc'=>'Pilih paket membership yang sesuai kebutuhanmu.'],
                ['step'=>'03','icon'=>'bi-shield-check','title'=>'Verifikasi','desc'=>'Upload identitas dan tunggu approval admin.'],
                ['step'=>'04','icon'=>'bi-lightning-charge-fill','title'=>'Mulai Latihan!','desc'=>'Datang ke gym terdekat dan mulai journey-mu.'],
            ] as $s)
            <div class="feature-card">
                <div style="font-family:var(--font-heading);font-size:var(--fs-4xl);font-weight:var(--fw-extrabold);color:var(--color-primary);opacity:0.15;margin-bottom:var(--space-2);">{{ $s['step'] }}</div>
                <div class="feature-icon"><i class="bi {{ $s['icon'] }}" style="font-size:24px;color:var(--color-primary);"></i></div>
                <h4>{{ $s['title'] }}</h4>
                <p>{{ $s['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- FAQ --}}
<section class="section" style="background:var(--color-bg-alt);">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2>Pertanyaan Umum</h2>
        </div>
        <div style="max-width:720px;margin:0 auto;" data-aos="fade-up">
            @foreach([
                ['q'=>'Apakah saya bisa menangguhkan (freeze) masa aktif membership?','a'=>'Ya, kamu bisa mengajukan penangguhan (freeze) masa aktif hingga 30 hari per tahun untuk paket Pro dan Elite. Pengajuan dapat dilakukan melalui admin di aplikasi atau langsung di resepsionis gym.'],
                ['q'=>'Apakah Fitness Vals menyediakan program Free Trial?','a'=>'Tentu! Kami menyediakan akses 1-Day Free Trial agar kamu bisa mencoba seluruh fasilitas gym premium dan kelas kebugaran kami secara gratis. Cukup mendaftar secara online dan datang ke lokasi terdekat.'],
                ['q'=>'Bagaimana prosedur untuk melakukan upgrade paket membership?','a'=>'Kamu dapat melakukan upgrade paket kapan saja melalui dashboard member atau langsung di meja resepsionis (front desk). Biaya akan disesuaikan secara prorata sesuai sisa masa aktif membership-mu.'],
                ['q'=>'Apakah satu keanggotaan berlaku untuk semua lokasi cabang?','a'=>'Ya! Semua paket membership memberikan akses penuh ke lokasi utama Fitness Vals di Kota Malang, beserta seluruh cabang baru yang akan segera hadir (All-Club Access).'],
                ['q'=>'Bagaimana tata cara memesan (booking) jadwal kelas?','a'=>'Pemesanan slot kelas dapat dilakukan dengan sangat mudah melalui dashboard member di website atau aplikasi kami, maksimal H-3 sebelum kelas tersebut dimulai. Kuota kelas terbatas, jadi pastikan kamu booking lebih awal!'],
            ] as $f)
            <div class="faq-item">
                <button class="faq-question">{{ $f['q'] }}<span class="faq-icon"><i class="bi bi-chevron-down"></i></span></button>
                <div class="faq-answer"><div class="faq-answer-inner">{{ $f['a'] }}</div></div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container" data-aos="fade-up">
        <h2>Siap Mulai?</h2>
        <p>Klaim free trial dan rasakan pengalaman gym premium dengan harga terjangkau.</p>
        <a href="{{ route('register') }}" class="btn btn-cta btn-lg">Klaim Free Trial →</a>
    </div>
</section>
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnMonthly = document.getElementById('btnMonthly');
        const btnYearly = document.getElementById('btnYearly');
        const priceVals = document.querySelectorAll('.price-val');
        
        btnMonthly.addEventListener('click', () => {
            btnMonthly.style.background = 'var(--color-primary)';
            btnMonthly.style.color = '#fff';
            btnMonthly.style.boxShadow = '0 4px 10px rgba(0,0,0,0.1)';
            
            btnYearly.style.background = 'transparent';
            btnYearly.style.color = 'var(--color-text-muted)';
            btnYearly.style.boxShadow = 'none';
            
            priceVals.forEach(el => el.innerText = 'Rp' + el.getAttribute('data-monthly'));
        });
        
        btnYearly.addEventListener('click', () => {
            btnYearly.style.background = 'var(--color-primary)';
            btnYearly.style.color = '#fff';
            btnYearly.style.boxShadow = '0 4px 10px rgba(0,0,0,0.1)';
            
            btnMonthly.style.background = 'transparent';
            btnMonthly.style.color = 'var(--color-text-muted)';
            btnMonthly.style.boxShadow = 'none';
            
            priceVals.forEach(el => el.innerText = 'Rp' + el.getAttribute('data-yearly'));
        });
    });
</script>
@endsection
