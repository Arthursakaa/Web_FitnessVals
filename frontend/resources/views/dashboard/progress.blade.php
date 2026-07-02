@extends('layouts.dashboard')
@section('title', 'Progress Latihan')
@section('page_title', 'Progress Latihan')

@section('content')
{{-- STAT CARDS --}}
<div class="grid-3" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:var(--space-4); margin-bottom:var(--space-6);">
    <div class="card" style="display:flex; align-items:center; gap:var(--space-4); padding:var(--space-4);">
        <div style="font-size:32px; color:var(--color-primary); background:var(--color-primary-light); width:60px; height:60px; display:flex; align-items:center; justify-content:center; border-radius:var(--radius-md);">
            <i class="bi bi-activity"></i>
        </div>
        <div>
            <p class="text-muted" style="font-size:var(--fs-xs); text-transform:uppercase; font-weight:600; margin-bottom:2px;">Total Sesi</p>
            <h3 style="margin:0;">{{ $totalWorkouts }}</h3>
        </div>
    </div>
    <div class="card" style="display:flex; align-items:center; gap:var(--space-4); padding:var(--space-4);">
        <div style="font-size:32px; color:var(--color-success); background:#DCFCE7; width:60px; height:60px; display:flex; align-items:center; justify-content:center; border-radius:var(--radius-md);">
            <i class="bi bi-calendar-check"></i>
        </div>
        <div>
            <p class="text-muted" style="font-size:var(--fs-xs); text-transform:uppercase; font-weight:600; margin-bottom:2px;">Aktif Bulan Ini</p>
            <h3 style="margin:0;">{{ $thisMonthWorkouts }}</h3>
        </div>
    </div>
    <div class="card" style="display:flex; align-items:center; gap:var(--space-4); padding:var(--space-4);">
        <div style="font-size:32px; color:#EAB308; background:#FEF9C3; width:60px; height:60px; display:flex; align-items:center; justify-content:center; border-radius:var(--radius-md);">
            <i class="bi bi-lightning-fill"></i>
        </div>
        <div>
            <p class="text-muted" style="font-size:var(--fs-xs); text-transform:uppercase; font-weight:600; margin-bottom:2px;">Streak</p>
            <h3 style="margin:0;">{{ $totalWorkouts > 0 ? 1 : 0 }} Hari</h3>
        </div>
    </div>
</div>

{{-- BADGES --}}
<div class="card" id="tour-step-1" style="margin-bottom:var(--space-6);">
    <h4 style="margin-bottom:var(--space-4);"><i class="bi bi-trophy-fill"></i> Pencapaian</h4>
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(140px, 1fr)); gap:var(--space-4);">
        @foreach($badges as $b)
        <div style="text-align:center;padding:var(--space-4);border:1px solid {{ $b['done']?'var(--color-accent)':'var(--color-border)' }};border-radius:var(--radius-lg);position:relative;overflow:hidden;{{ $b['done']?'background:var(--color-accent-light);':'background:var(--color-bg-alt);' }}">
            @if(!$b['done'])
            <div style="position:absolute; bottom:0; left:0; height:4px; background:var(--color-border-light); width:100%;">
                <div style="height:100%; background:var(--color-primary); width:{{ min(100, ($b['current'] / $b['target']) * 100) }}%;"></div>
            </div>
            @endif
            <div style="font-size:36px; color:{{ $b['done']?'var(--color-accent)':'var(--color-text-muted)' }};"><i class="bi {{ $b['icon'] }}"></i></div>
            <p style="font-size:var(--fs-xs);font-weight:600;margin-top:8px;">{{ $b['name'] }}</p>
            @if(!$b['done'])
            <p style="font-size:10px; color:var(--color-text-muted); margin-top:4px;">{{ $b['current'] }} / {{ $b['target'] }}</p>
            @endif
        </div>
        @endforeach
    </div>
</div>

<div class="grid-2" id="tour-step-2" style="gap:var(--space-6);margin-bottom:var(--space-6);grid-template-columns:1fr 1fr;">
    <div class="chart-card" style="min-width:0;">
        <h4>Berat Badan</h4>
        @if(count($weightData) > 0)
        <div class="chart-container"><canvas id="weightChart2"></canvas></div>
        @else
        <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; height:180px; text-align:center; color:var(--color-text-muted);">
            <div style="font-size:32px; margin-bottom:8px;"><i class="bi bi-graph-up-arrow"></i></div>
            <p style="font-size:var(--fs-sm);">Belum ada data berat badan.<br>Cek BMI untuk mencatat.</p>
        </div>
        @endif
    </div>
    <div class="chart-card" style="min-width:0;"><h4>Frekuensi Latihan / Minggu</h4><div class="chart-container"><canvas id="freqChart"></canvas></div></div>
</div>

{{-- WEEKLY WORKOUT SCHEDULE --}}
<div class="card" id="tour-step-3" style="margin-bottom:var(--space-6);">
    <div class="flex-between" style="margin-bottom:var(--space-4);">
        <h4>Rencana Latihan Minggu Ini</h4>
        <button class="btn btn-outline btn-sm" data-modal="editScheduleModal"><i class="bi bi-calendar-event"></i> Atur Jadwal</button>
    </div>
    
    @if(count($weeklyPlans) == 0)
    <div style="background:var(--color-primary-light); color:var(--color-primary); padding:var(--space-3); border-radius:var(--radius-sm); margin-bottom:var(--space-4); font-size:var(--fs-sm); display:flex; align-items:center; gap:10px;">
        <i class="bi bi-info-circle-fill"></i> Kamu belum mengatur rencana latihan minggu ini. Yuk atur sekarang!
    </div>
    @endif

    <div class="schedule-grid" style="display:grid;grid-template-columns:repeat(auto-fit, minmax(80px, 1fr));gap:var(--space-2);text-align:center;">
        @php 
            $dayMap = [1 => 'Sen', 2 => 'Sel', 3 => 'Rab', 4 => 'Kam', 5 => 'Jum', 6 => 'Sab', 0 => 'Min']; 
            $currentDayIndex = now()->dayOfWeek;
        @endphp
        @foreach($dayMap as $idx => $day)
        <div style="padding:var(--space-3) 0;border-radius:var(--radius-md);background:{{ $idx==$currentDayIndex ? 'var(--color-primary-light)' : 'var(--color-bg-alt)' }};border:1px solid {{ $idx==$currentDayIndex ? 'var(--color-primary)' : 'var(--color-border)' }};">
            <div style="font-weight:bold;font-size:var(--fs-sm);margin-bottom:var(--space-2);color:{{ $idx==$currentDayIndex ? 'var(--color-primary)' : 'var(--color-text)' }};">{{ $day }}</div>
            <div style="font-size:11px;color:var(--color-text-muted);">
                {{ isset($weeklyPlans[$idx]) ? $weeklyPlans[$idx]->target_muscle_group : 'Rest Day' }}
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- WORKOUT LOG --}}
<div class="card" id="tour-step-4">
    <div class="flex-between" style="margin-bottom:var(--space-4);">
        <h4>Log Latihan Harian</h4>
        <button class="btn btn-primary btn-sm" data-modal="addWorkoutModal">+ Catat Latihan</button>
    </div>
    
    @if(session('success'))
        <div style="background:#DCFCE7;color:#166534;padding:var(--space-3);border-radius:var(--radius-md);margin-bottom:var(--space-4);font-size:var(--fs-sm);">
            {{ session('success') }}
        </div>
    @endif

    @forelse($workoutLogs ?? [] as $log)
    <div style="display:flex;align-items:center;gap:var(--space-4);padding:var(--space-4) 0;border-bottom:1px solid var(--color-border-light);">
        <div style="width:4px;height:48px;border-radius:2px;background:var(--color-accent);"></div>
        <div style="flex:1;">
            <div class="flex-between"><strong style="font-size:var(--fs-sm);">{{ $log->focus_area }}</strong><span class="text-muted" style="font-size:var(--fs-xs);">{{ \Carbon\Carbon::parse($log->log_date)->format('d M Y') }}</span></div>
            <div style="display:flex;flex-direction:column;gap:4px;font-size:var(--fs-xs);color:var(--color-text-muted);margin-top:4px;">
                @foreach($log->exercises as $ex)
                    @if($ex->duration_minutes)
                        <span><i class="bi bi-activity"></i> {{ $ex->exercise_name }} ({{ $ex->duration_minutes }} Menit)</span>
                    @else
                        <span><i class="bi bi-activity"></i> {{ $ex->exercise_name }} ({{ $ex->sets }}x{{ $ex->reps }})</span>
                    @endif
                @endforeach
                @if($log->notes)
                    <span style="font-style:italic;margin-top:2px;">"{{ $log->notes }}"</span>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="text-center" style="padding:var(--space-6) 0;">
        <div style="font-size:48px;color:var(--color-text-muted);margin-bottom:var(--space-3);"><i class="bi bi-journal-x"></i></div>
        <p class="text-muted" style="font-size:var(--fs-sm);margin-bottom:var(--space-4);">Belum ada log latihan.<br>Setiap langkah kecil adalah progress, ayo mulai perjalanan fitness-mu!</p>
        <button class="btn btn-outline btn-sm" data-modal="addWorkoutModal">Mulai Catat Latihan Pertama</button>
    </div>
    @endforelse
</div>

{{-- AI NUTRITION RECOMMENDATION BANNER --}}
<div class="card" style="margin-top:var(--space-6); background: var(--gradient-hero); color: #fff; border: none;">
    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: var(--space-4);">
        <div>
            <div class="badge" style="background: rgba(255,255,255,0.2); color: #fff; margin-bottom: var(--space-2);">🤖 AI NUTRITION</div>
            <h3 style="color: #fff; margin-bottom: var(--space-1);">Latihan Hari Ini Selesai?</h3>
            <p style="color: rgba(255,255,255,0.8); font-size: var(--fs-sm);">Dapatkan rekomendasi asupan makanan optimal berdasarkan progress workout kamu hari ini.</p>
        </div>
        <a href="{{ route('dashboard.meals') ?? '#' }}" class="btn btn-cta" style="background: #fff; color: var(--color-primary); border-color: #fff;">Lihat Rekomendasi Makanan →</a>
    </div>
</div>

<div class="modal-overlay" id="addWorkoutModal">
    <div class="modal" style="width: 550px;">
        <div class="modal-header"><h3>Catat Latihan Hari Ini</h3><button data-close-modal style="font-size:20px;" type="button"><i class="bi bi-x-lg"></i></button></div>
        <form action="{{ route('dashboard.progress.log') }}" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group" style="flex:2;">
                    <label class="form-label">Fokus Latihan</label>
                    <select name="focus_area" id="focusAreaSelect" class="form-input form-select" required>
                        <option value="" disabled selected>Pilih Fokus Latihan</option>
                        <option value="Chest">Chest (Dada)</option>
                        <option value="Back">Back (Punggung)</option>
                        <option value="Legs">Legs (Kaki)</option>
                        <option value="Shoulders">Shoulders (Bahu)</option>
                        <option value="Arms">Arms (Lengan)</option>
                        <option value="Core">Core (Perut)</option>
                        <option value="Cardio/HIIT">Cardio / HIIT</option>
                        <option value="Full Body">Full Body Workout</option>
                    </select>
                </div>
                <div class="form-group" style="flex:1;">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="log_date" class="form-input" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Daftar Gerakan (Maks 3)</label>
                <!-- Exercise 1 -->
                <div class="form-row exercise-row" style="margin-bottom:var(--space-2);">
                    <div style="flex:2">
                        <select name="exercises[0][name]" class="form-input form-select exercise-select">
                            <option value="">Pilih Fokus Latihan Dulu</option>
                        </select>
                    </div>
                    <div style="flex:1" class="set-rep-input"><input type="number" name="exercises[0][sets]" class="form-input" placeholder="Sets"></div>
                    <div style="flex:1" class="set-rep-input"><input type="number" name="exercises[0][reps]" class="form-input" placeholder="Reps"></div>
                    <div style="flex:2; display:none;" class="duration-input"><input type="number" name="exercises[0][duration]" class="form-input" placeholder="Menit"></div>
                </div>
                <!-- Exercise 2 -->
                <div class="form-row exercise-row" style="margin-bottom:var(--space-2);">
                    <div style="flex:2">
                        <select name="exercises[1][name]" class="form-input form-select exercise-select">
                            <option value="">Pilih Fokus Latihan Dulu</option>
                        </select>
                    </div>
                    <div style="flex:1" class="set-rep-input"><input type="number" name="exercises[1][sets]" class="form-input" placeholder="Sets"></div>
                    <div style="flex:1" class="set-rep-input"><input type="number" name="exercises[1][reps]" class="form-input" placeholder="Reps"></div>
                    <div style="flex:2; display:none;" class="duration-input"><input type="number" name="exercises[1][duration]" class="form-input" placeholder="Menit"></div>
                </div>
                <!-- Exercise 3 -->
                <div class="form-row exercise-row">
                    <div style="flex:2">
                        <select name="exercises[2][name]" class="form-input form-select exercise-select">
                            <option value="">Pilih Fokus Latihan Dulu</option>
                        </select>
                    </div>
                    <div style="flex:1" class="set-rep-input"><input type="number" name="exercises[2][sets]" class="form-input" placeholder="Sets"></div>
                    <div style="flex:1" class="set-rep-input"><input type="number" name="exercises[2][reps]" class="form-input" placeholder="Reps"></div>
                    <div style="flex:2; display:none;" class="duration-input"><input type="number" name="exercises[2][duration]" class="form-input" placeholder="Menit"></div>
                </div>
            </div>
            
            <div class="form-group"><label class="form-label">Catatan Tambahan</label><textarea name="notes" class="form-input" rows="2" placeholder="Catatan... misal PR baru"></textarea></div>
            <button type="submit" class="btn btn-primary btn-block">Simpan Log Latihan</button>
        </form>
    </div>
</div>

<div class="modal-overlay" id="editScheduleModal">
    <div class="modal" style="width: 500px;">
        <div class="modal-header"><h3>Atur Rencana Latihan Mingguan</h3><button data-close-modal style="font-size:20px;"><i class="bi bi-x-lg"></i></button></div>
        <form action="{{ route('dashboard.progress.plan') }}" method="POST">
            @csrf
            <p class="text-muted" style="font-size:var(--fs-sm);margin-bottom:var(--space-4);">Tentukan target bagian otot yang ingin dilatih setiap harinya.</p>
            @php $fullDays = [1=>'Senin', 2=>'Selasa', 3=>'Rabu', 4=>'Kamis', 5=>'Jumat', 6=>'Sabtu', 0=>'Minggu']; @endphp
            @foreach($fullDays as $idx => $day)
            <div class="form-group flex-between" style="gap:var(--space-4);margin-bottom:var(--space-3);">
                <label style="width:70px;font-weight:600;font-size:var(--fs-sm);">{{ $day }}</label>
                <select name="plan[{{ $idx }}]" class="form-input form-select" style="flex:1;padding:8px 12px;">
                    @php $currentPlan = isset($weeklyPlans[$idx]) ? $weeklyPlans[$idx]->target_muscle_group : ''; @endphp
                    <option value="" disabled {{ $currentPlan == '' ? 'selected' : '' }}>Pilih Target Latihan</option>
                    <option value="Rest Day" {{ $currentPlan == 'Rest Day' || $currentPlan == 'Rest' ? 'selected' : '' }}>Rest Day</option>
                    <option value="Chest" {{ $currentPlan == 'Chest' ? 'selected' : '' }}>Chest (Dada)</option>
                    <option value="Back" {{ $currentPlan == 'Back' ? 'selected' : '' }}>Back (Punggung)</option>
                    <option value="Legs" {{ $currentPlan == 'Legs' ? 'selected' : '' }}>Legs (Kaki)</option>
                    <option value="Shoulders" {{ $currentPlan == 'Shoulders' ? 'selected' : '' }}>Shoulders (Bahu)</option>
                    <option value="Arms" {{ $currentPlan == 'Arms' ? 'selected' : '' }}>Arms (Lengan)</option>
                    <option value="Core" {{ $currentPlan == 'Core' ? 'selected' : '' }}>Core (Perut)</option>
                    <option value="Cardio/HIIT" {{ $currentPlan == 'Cardio/HIIT' ? 'selected' : '' }}>Cardio / HIIT</option>
                    <option value="Full Body" {{ $currentPlan == 'Full Body' ? 'selected' : '' }}>Full Body</option>
                </select>
            </div>
            @endforeach
            <button type="submit" class="btn btn-primary btn-block" style="margin-top:var(--space-4);">Simpan Rencana</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const weightLabels = {!! json_encode($weightLabels ?? []) !!};
    const weightData = {!! json_encode($weightData ?? []) !!};

    const finalWLabels = weightLabels.length ? weightLabels : ['Belum ada data'];
    const finalWData = weightData.length ? weightData : [0];

    @if(count($weightData) > 0)
    new Chart(document.getElementById('weightChart2'), { 
        type:'line', 
        data:{
            labels: finalWLabels,
            datasets:[{
                label:'kg',
                data: finalWData,
                borderColor:'#FF6B2C',
                backgroundColor:'rgba(255,107,44,0.1)',
                fill:true,
                tension:.4,
                pointRadius:4
            }]
        }, 
        options:{
            responsive:true,
            maintainAspectRatio:false,
            plugins:{legend:{display:false}},
            scales:{
                y:{beginAtZero:false,grid:{color:'#F0F0F2'}},
                x:{grid:{display:false}}
            }
        } 
    });
    @endif

    new Chart(document.getElementById('freqChart'), { 
        type:'bar', 
        data:{
            labels:['W1','W2','W3','W4','W5','W6','W7','W8'],
            datasets:[{data:[3,4,3,5,4,5,4,6],backgroundColor:'#A8E63D',borderRadius:6}]
        }, 
        options:{
            responsive:true,
            maintainAspectRatio:false,
            plugins:{legend:{display:false}},
            scales:{y:{beginAtZero:true,grid:{color:'#F0F0F2'}},x:{grid:{display:false}}}
        } 
    });

    // Dynamic Workout Form Logic
    const exerciseDict = {
        'Chest': ['Bench Press', 'Incline Dumbbell Press', 'Chest Flyes', 'Push Ups', 'Cable Crossovers', 'Dips', 'Pec Deck Machine'],
        'Back': ['Pull Ups', 'Lat Pulldown', 'Barbell Row', 'Seated Cable Row', 'Deadlift', 'Face Pulls', 'T-Bar Row'],
        'Legs': ['Squats', 'Leg Press', 'Lunges', 'Leg Extensions', 'Leg Curls', 'Calf Raises', 'Romanian Deadlift', 'Bulgarian Split Squats'],
        'Shoulders': ['Overhead Press', 'Lateral Raises', 'Front Raises', 'Reverse Pec Deck', 'Arnold Press', 'Upright Row'],
        'Arms': ['Bicep Curls', 'Tricep Pushdowns', 'Hammer Curls', 'Skull Crushers', 'Preacher Curls', 'Overhead Tricep Ext', 'Concentration Curls'],
        'Core': ['Crunches', 'Plank', 'Leg Raises', 'Russian Twists', 'Ab Wheel Rollouts', 'Cable Crunches', 'Bicycle Crunches'],
        'Cardio/HIIT': ['Treadmill Running', 'Cycling', 'Rowing Machine', 'Jump Rope', 'Burpees', 'Kettlebell Swings', 'Stair Climber'],
        'Full Body': ['Burpees', 'Thrusters', 'Clean and Press', 'Kettlebell Swings', 'Deadlift', 'Squats']
    };

    const focusSelect = document.getElementById('focusAreaSelect');
    const exerciseSelects = document.querySelectorAll('.exercise-select');

    if (focusSelect) {
        focusSelect.addEventListener('change', function() {
            const focus = this.value;
            let optionsHTML = '<option value="">Pilih Gerakan</option>';
            
            if (exerciseDict[focus]) {
                exerciseDict[focus].forEach(ex => {
                    optionsHTML += `<option value="${ex}">${ex}</option>`;
                });
            }
            
            exerciseSelects.forEach(select => {
                select.innerHTML = optionsHTML;
            });

            // Toggle duration vs sets/reps
            const isCardio = focus === 'Cardio/HIIT';
            const exerciseRows = document.querySelectorAll('.exercise-row');
            
            exerciseRows.forEach(row => {
                const setRepInputs = row.querySelectorAll('.set-rep-input');
                const durationInput = row.querySelector('.duration-input');
                
                if (isCardio) {
                    setRepInputs.forEach(el => el.style.display = 'none');
                    if(durationInput) durationInput.style.display = 'block';
                } else {
                    setRepInputs.forEach(el => el.style.display = 'block');
                    if(durationInput) durationInput.style.display = 'none';
                }
            });
        });
    }

    // ==========================================
    // INTERACTIVE ONBOARDING TOUR (PROGRESS)
    // ==========================================
    if (typeof initPageTour === 'function') {
        initPageTour([
            {
                target: '#tour-step-1',
                title: 'Koleksi Pencapaian 🏆',
                text: 'Selesaikan tantangan untuk mengumpulkan badge ini! Semakin banyak badge, semakin konsisten kamu berlatih.',
                position: 'bottom'
            },
            {
                target: '#tour-step-2',
                title: 'Grafik Pertumbuhan 📈',
                text: 'Pantau perubahan berat badan dan frekuensi latihanmu dari minggu ke minggu di grafik interaktif ini.',
                position: 'bottom'
            },
            {
                target: '#tour-step-3',
                title: 'Rencana Latihan (Split) 📅',
                text: 'Klik Atur Jadwal untuk merencanakan otot mana yang ingin kamu latih setiap harinya. Sangat penting untuk repetisi yang konsisten!',
                position: 'bottom'
            },
            {
                target: '#tour-step-4',
                title: 'Catat Latihan Harian 📝',
                text: 'Habis nge-gym? Klik + Catat Latihan untuk merekam jumlah Set & Rep yang baru saja kamu lakukan agar progress terukur.',
                position: 'right'
            }
        ], 'fitnessValsTourCompleted_progress');
    }
});
</script>
@endsection
