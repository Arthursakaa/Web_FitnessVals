@extends('layouts.dashboard')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Selamat datang kembali, ' . (explode(' ', Auth::user()->name)[0] ?? 'Member') . '!')

@section('content')
{{-- GREETING --}}
<div class="greeting-card" id="tour-step-1" data-aos="fade-down">
    <div class="greeting-text">
        <p style="font-size:var(--fs-xs);color:rgba(255,255,255,0.5);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:var(--space-2);">HARI KE-{{ $daysSinceJoined }} FITNESS JOURNEY</p>
        <h3>Good {{ date('H') < 12 ? 'Morning' : (date('H') < 17 ? 'Afternoon' : 'Evening') }}, {{ explode(' ', Auth::user()->name)[0] ?? 'Member' }}! <i class="bi bi-trophy"></i></h3>
        <div style="margin-bottom:var(--space-3); margin-top:4px;">
            <span class="badge {{ Auth::user()->plan == 'elite' ? 'badge-dark' : (Auth::user()->plan == 'pro' ? 'badge-accent' : 'badge-outline') }}" style="font-size:10px;text-transform:uppercase;letter-spacing:1px;padding:4px 12px;background:{{ Auth::user()->plan == 'elite' ? '#1E293B' : '' }};color:{{ Auth::user()->plan == 'elite' ? '#fff' : '' }};">
                <i class="bi {{ Auth::user()->plan == 'elite' ? 'bi-gem' : (Auth::user()->plan == 'pro' ? 'bi-fire' : 'bi-person-walking') }}"></i> 
                Member {{ ucfirst(Auth::user()->plan ?? 'Basic') }}
            </span>
        </div>
        <p>Konsistensi adalah kunci. Kamu sudah 75% menuju target bulanan. Ayo lanjutkan!</p>
        <div style="display:flex;gap:var(--space-3);margin-top:var(--space-5);">
            <a href="{{ route('dashboard.nutrition') }}" class="btn btn-cta btn-sm"><i class="bi bi-plus-lg"></i> Log Makanan</a>
            <a href="{{ route('dashboard.schedule') }}" class="btn btn-outline-white btn-sm"><i class="bi bi-calendar3"></i> Lihat Jadwal</a>
        </div>
    </div>
    <div class="greeting-emoji"><i class="bi bi-activity"></i></div>
</div>

{{-- SUMMARY --}}
<div class="summary-grid" id="tour-step-2">
    <div class="summary-card" data-aos="fade-up" data-aos-delay="0">
        <div class="summary-card-header">
            <div class="summary-card-icon teal"><i class="bi bi-fire"></i></div>
            <span class="trend up">Total Konsumsi</span>
        </div>
        <div class="value">{{ number_format($todayCalories) }}</div>
        <div class="label">Kalori Hari Ini</div>
        <div class="progress-bar" style="margin-top:var(--space-3);"><div class="progress-fill" style="width:{{ min(100, $targetCalories > 0 ? ($todayCalories/$targetCalories)*100 : 0) }}%;"></div></div>
    </div>
    <div class="summary-card" data-aos="fade-up" data-aos-delay="100">
        <div class="summary-card-header">
            <div class="summary-card-icon gold"><i class="bi bi-bullseye"></i></div>
        </div>
        <div class="value">{{ number_format($targetCalories) }}</div>
        <div class="label">Target Kalori Harian</div>
        <p style="font-size:10px;color:var(--color-text-muted);margin-top:var(--space-2);">{{ number_format(max(0, $targetCalories - $todayCalories)) }} kcal remaining</p>
    </div>
    <div class="summary-card" data-aos="fade-up" data-aos-delay="200">
        <div class="summary-card-header">
            <div class="summary-card-icon blue"><span style="font-style:normal;">👣</span></div>
            <span class="trend up">Kelas Aktif</span>
        </div>
        <div class="value">{{ $activeClassesCount }}</div>
        <div class="label">Total Bulan Ini</div>
    </div>
    <div class="summary-card" data-aos="fade-up" data-aos-delay="300">
        <div class="summary-card-header">
            <div class="summary-card-icon red"><i class="bi bi-calendar3"></i></div>
        </div>
        @if($nextClass)
            <div class="value" style="font-size:var(--fs-lg);">{{ $nextClass->gymClass->name }}</div>
            <div class="label">Berikutnya — {{ \Carbon\Carbon::parse($nextClass->start_time)->format('H:i') }}</div>
        @else
            <div class="value" style="font-size:var(--fs-lg);">Belum Ada Jadwal</div>
            <div class="label">Daftar kelas hari ini!</div>
        @endif
        <a href="{{ route('dashboard.schedule') }}" style="font-size:var(--fs-xs);color:var(--color-primary);font-weight:600;margin-top:var(--space-2);display:inline-block;">Lihat Detail →</a>
    </div>
</div>

<div class="grid-2" style="gap:var(--space-6);margin-bottom:var(--space-6);">
    {{-- WEIGHT CHART --}}
    <div class="chart-card" data-aos="fade-right" data-aos-delay="100">
        <div class="flex-between" style="margin-bottom:var(--space-4);">
            <h4><i class="bi bi-graph-down-arrow"></i> Berat Badan</h4>
            <div class="flex-gap">
                <button class="btn btn-ghost btn-sm" style="padding:4px 10px;font-size:10px;">7D</button>
                <button class="btn btn-primary btn-sm" style="padding:4px 10px;font-size:10px;">30D</button>
                <button class="btn btn-ghost btn-sm" style="padding:4px 10px;font-size:10px;">90D</button>
            </div>
        </div>
        <div class="chart-container"><canvas id="weightChart"></canvas></div>
        <div style="display:flex;justify-content:space-between;margin-top:var(--space-4);font-size:var(--fs-xs);">
            <div><span class="text-muted">Saat ini</span><br><strong style="font-size:var(--fs-lg);">{{ $currentWeight > 0 ? $currentWeight . ' kg' : 'Belum Ada' }}</strong></div>
            <div style="text-align:right;"><span class="text-muted">Target</span><br><strong style="font-size:var(--fs-lg);color:var(--color-primary);">{{ $targetWeight > 0 ? $targetWeight . ' kg' : 'Belum Ada' }}</strong></div>
        </div>
    </div>

    {{-- UPCOMING + QUICK ACTIONS --}}
    <div>
        {{-- Quick Actions --}}
        <div class="card" id="tour-step-3" style="margin-bottom:var(--space-5);" data-aos="fade-left" data-aos-delay="150">
            <h4 style="margin-bottom:var(--space-4);display:flex;align-items:center;gap:var(--space-2);"><i class="bi bi-lightning-charge-fill"></i> Quick Actions</h4>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:var(--space-3);">
                <a href="{{ route('dashboard.nutrition') }}" class="card-flat" style="text-align:center;padding:var(--space-4);cursor:pointer;transition:all var(--transition-fast);text-decoration:none;color:var(--color-text);" onmouseover="this.style.borderColor='var(--color-accent)'" onmouseout="this.style.borderColor=''">
                    <i class="bi bi-fire"></i>
                    <span style="font-size:var(--fs-xs);font-weight:600;">Catat Makan</span>
                </a>
                <a href="{{ route('dashboard.progress') }}" class="card-flat" style="text-align:center;padding:var(--space-4);cursor:pointer;transition:all var(--transition-fast);text-decoration:none;color:var(--color-text);" onmouseover="this.style.borderColor='var(--color-accent)'" onmouseout="this.style.borderColor=''">
                    <i class="bi bi-lightning-charge-fill"></i>
                    <span style="font-size:var(--fs-xs);font-weight:600;">Log Latihan</span>
                </a>
                <a href="{{ route('dashboard.bmi') }}" class="card-flat" style="text-align:center;padding:var(--space-4);cursor:pointer;transition:all var(--transition-fast);text-decoration:none;color:var(--color-text);" onmouseover="this.style.borderColor='var(--color-accent)'" onmouseout="this.style.borderColor=''">
                    <i class="bi bi-speedometer2"></i>
                    <span style="font-size:var(--fs-xs);font-weight:600;">Cek BMI</span>
                </a>
            </div>
        </div>

        {{-- Upcoming Classes --}}
        <div class="card" data-aos="fade-left" data-aos-delay="250">
            <div class="flex-between" style="margin-bottom:var(--space-4);">
                <h4 style="display:flex;align-items:center;gap:var(--space-2);"><i class="bi bi-calendar3"></i> Kelas Mendatang</h4>
                <a href="{{ route('dashboard.schedule') }}" style="font-size:var(--fs-xs);color:var(--color-primary);font-weight:600;">Lihat Semua</a>
            </div>
            <div style="display:flex;align-items:center;gap:var(--space-3);padding:var(--space-3) 0;border-bottom:1px solid var(--color-border-light);">
                <div style="width:4px;height:44px;border-radius:3px;background:var(--color-cta);flex-shrink:0;"></div>
                <div style="flex:1;">
                @if($nextClass)
                    <strong style="font-size:var(--fs-sm);">{{ $nextClass->gymClass->name }}</strong><br>
                    <span class="text-muted" style="font-size:var(--fs-xs);">{{ \Carbon\Carbon::parse($nextClass->start_time)->format('l, d M • H:i') }} | {{ $nextClass->trainer_name }}</span>
                @else
                    <strong style="font-size:var(--fs-sm);">Belum ada jadwal</strong><br>
                    <span class="text-muted" style="font-size:var(--fs-xs);">Ayo daftar kelas hari ini!</span>
                @endif
                </div>
                @if($nextClass)
                <button class="btn btn-ghost btn-sm" style="font-size:10px;padding:4px 10px;">Detail</button>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- BOTTOM ROW --}}
<div class="grid-2" style="gap:var(--space-6);">
    {{-- Today Meals --}}
    <div class="card" style="position:relative;overflow:hidden;" data-aos="fade-up" data-aos-delay="200">
        <div class="flex-between" style="margin-bottom:var(--space-4);">
            <h4 style="display:flex;align-items:center;gap:var(--space-2);"><i class="bi bi-trophy-fill"></i> Rekomendasi Hari Ini</h4>
            @if(Auth::user()->plan != 'basic')
            <a href="{{ route('dashboard.meals') }}" style="font-size:var(--fs-xs);color:var(--color-primary);font-weight:600;">Lihat Semua</a>
            @endif
        </div>
        
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:var(--space-3); {{ Auth::user()->plan == 'basic' ? 'filter:blur(4px);opacity:0.6;pointer-events:none;' : '' }}">
            @foreach($recommendedMeals as $m)
            @php 
                $icon = 'bi-egg'; $color = 'var(--color-cyan)'; $time = 'Snack';
                $mealType = $m->meal_type ?? '';
                if($mealType == 'Sarapan') { $icon = 'bi-egg'; $color = 'var(--color-cyan)'; $time = 'Sarapan'; }
                if($mealType == 'Makan Siang') { $icon = 'bi-fire'; $color = 'var(--color-primary)'; $time = 'Siang'; }
                if($mealType == 'Makan Malam') { $icon = 'bi-cup-hot'; $color = 'var(--color-mint)'; $time = 'Malam'; }
            @endphp
            <div class="card-flat" style="text-align:center;padding:var(--space-4);overflow:hidden;min-width:0;">
                <div style="width:48px;height:48px;border-radius:var(--radius-lg);background:var(--color-bg);display:flex;align-items:center;justify-content:center;margin:0 auto var(--space-2);"><i class="bi {{ $icon }}" style="font-size:22px;color:{{ $color }};"></i></div>
                <p style="font-size:var(--fs-sm);font-weight:var(--fw-semibold);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="{{ $m->name }}">{{ $m->name }}</p>
                <p style="font-size:var(--fs-xs);color:var(--color-text-muted);">{{ $m->calories }} kcal</p>
                <span class="badge badge-teal" style="font-size:9px;margin-top:var(--space-1);">{{ $time }}</span>
            </div>
            @endforeach
        </div>

        @if(Auth::user()->plan == 'basic')
        <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;background:rgba(255,255,255,0.4);z-index:10;">
            <div style="background:var(--color-bg-dark);color:#fff;padding:12px 24px;border-radius:100px;display:flex;align-items:center;gap:8px;font-size:var(--fs-sm);font-weight:600;box-shadow:0 10px 25px rgba(0,0,0,0.2);">
                <i class="bi bi-lock-fill" style="color:var(--color-accent);"></i> Fitur Khusus Paket Pro & Elite
            </div>
            <a href="{{ route('membership') }}" class="btn btn-primary btn-sm" style="margin-top:12px;padding:6px 16px;">Upgrade Sekarang</a>
        </div>
        @endif
    </div>

    {{-- Monthly Goals --}}
    <div class="card" data-aos="fade-up" data-aos-delay="300">
        <h4 style="margin-bottom:var(--space-5);display:flex;align-items:center;gap:var(--space-2);"><i class="bi bi-trophy-fill"></i> Target Bulanan</h4>
        @foreach([
            ['label'=>'Sesi Latihan','val'=> $workoutLogsCount ?? 0,'max'=>24,'color'=>'var(--color-accent)'],
            ['label'=>'Target Kalori Harian Terpenuhi','val'=> min($todayCalories >= ($targetCalories ?? 2000) ? 1 : 0, 30),'max'=>30,'color'=>'var(--color-cta)'],
            ['label'=>'Langkah 10K/hari','val'=>0,'max'=>30,'color'=>'#3B82F6'],
        ] as $p)
        <div style="margin-bottom:var(--space-5);">
            <div class="flex-between" style="margin-bottom:var(--space-2);">
                <span style="font-size:var(--fs-sm);font-weight:var(--fw-medium);">{{ $p['label'] }}</span>
                <span style="font-size:var(--fs-sm);font-weight:var(--fw-bold);color:{{ $p['color'] }};">{{ $p['val'] }}/{{ $p['max'] }}</span>
            </div>
            <div class="progress-bar" style="height:10px;"><div class="progress-fill" style="width:{{ ($p['val']/$p['max'])*100 }}%;background:{{ $p['color'] }};"></div></div>
        </div>
        @endforeach
        <div style="padding:var(--space-4);background:var(--color-accent-light);border-radius:var(--radius-lg);margin-top:var(--space-2);">
            <p style="font-size:var(--fs-xs);color:var(--color-primary);font-weight:var(--fw-semibold);"><i class="bi bi-bullseye"></i> Kamu sudah 75% menuju target bulan ini. Terus semangat!</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const weightLabels = {!! json_encode($weightLabels) !!};
    const weightData = {!! json_encode($weightData) !!};
    
    new Chart(document.getElementById('weightChart'), {
        type: 'line',
        data: {
            labels: weightLabels.length > 0 ? weightLabels : ['W1','W2','W3','W4','W5','W6','W7','W8'],
            datasets: [{
                label: 'Berat (kg)',
                data: weightData.length > 0 ? weightData : [78, 77.2, 76.5, 76, 75.2, 74.8, 74, 73.8],
                borderColor: '#1A9B9E',
                backgroundColor: 'rgba(26,155,158,0.08)',
                fill: true, tension: 0.4, pointRadius: 5, pointBackgroundColor: '#1A9B9E',
                pointBorderColor: '#fff', pointBorderWidth: 2,
                borderWidth: 2.5,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: false, grid: { color: '#F0F2F5', drawBorder: false }, ticks: { font: { size: 11 } } },
                x: { grid: { display: false }, ticks: { font: { size: 11 } } }
            }
        }
    });
    // ==========================================
    // INTERACTIVE ONBOARDING TOUR (HOME)
    // ==========================================
    initPageTour([
        {
            target: '#tour-step-1',
            title: 'Selamat Datang di Dashboard! 👋',
            text: 'Ini adalah pusat kendali perjalanan fitness kamu. Di sini kamu bisa melihat ringkasan aktivitas dan progress harianmu.',
            position: 'bottom'
        },
        {
            target: '#tour-step-2',
            title: 'Ringkasan Harian 📊',
            text: 'Pantau jumlah kalori yang sudah dikonsumsi, kelas aktif, dan pengingat kelas berikutnya dalam satu pandangan cepat.',
            position: 'bottom'
        },
        {
            target: '#tour-step-3',
            title: 'Akses Cepat (Quick Actions) ⚡',
            text: 'Gunakan tombol-tombol ini untuk mencatat makanan, melihat progress latihan, atau mengecek BMI kamu dalam hitungan detik.',
            position: 'left'
        },
        {
            target: '#dashSidebar',
            title: 'Navigasi Utama 🧭',
            text: 'Semua fitur lengkap mulai dari Kalori, Jadwal Kelas, hingga Personal Trainer bisa kamu akses kapan saja dari menu sebelah kiri ini.',
            position: 'right'
        }
    ], 'fitnessValsTourCompleted_home');
});
</script>
@endsection
