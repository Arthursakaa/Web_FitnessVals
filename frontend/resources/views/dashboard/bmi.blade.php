@extends('layouts.dashboard')
@section('title', 'Cek BMI')
@section('page_title', 'Cek BMI')

@section('content')
<div class="grid-2" style="gap:var(--space-6);">
    {{-- INPUT FORM --}}
    <div class="card" id="tour-step-1">
        <h4 style="margin-bottom:var(--space-5);">Kalkulator BMI</h4>
        <form id="bmiForm">
            <div class="form-row">
                <div class="form-group"><label class="form-label">Berat Badan (kg)</label><input type="number" class="form-input" id="bmiWeight" value="{{ $profile->weight_kg ?? 70 }}" step="0.1" required></div>
                <div class="form-group"><label class="form-label">Tinggi Badan (cm)</label><input type="number" class="form-input" id="bmiHeight" value="{{ $profile->height_cm ?? 170 }}" required></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Usia</label><input type="number" class="form-input" id="bmiAge" value="{{ $profile->age ?? 25 }}" required></div>
                <div class="form-group"><label class="form-label">Jenis Kelamin</label><select class="form-input form-select" id="bmiGender"><option value="male" {{ ($profile->gender ?? '') == 'male' ? 'selected' : '' }}>Laki-laki</option><option value="female" {{ ($profile->gender ?? '') == 'female' ? 'selected' : '' }}>Perempuan</option></select></div>
            </div>
            <div class="form-group">
                <label class="form-label">Level Aktivitas</label>
                <select class="form-input form-select" id="bmiActivity">
                    <option value="1.2" {{ ($profile->activity_level_multiplier ?? 0) == 1.2 ? 'selected' : '' }}>Sedentary (jarang olahraga)</option>
                    <option value="1.375" {{ ($profile->activity_level_multiplier ?? 0) == 1.375 ? 'selected' : '' }}>Light (1-3x/minggu)</option>
                    <option value="1.55" {{ ($profile->activity_level_multiplier ?? 1.55) == 1.55 ? 'selected' : '' }}>Moderate (3-5x/minggu)</option>
                    <option value="1.725" {{ ($profile->activity_level_multiplier ?? 0) == 1.725 ? 'selected' : '' }}>Active (6-7x/minggu)</option>
                    <option value="1.9" {{ ($profile->activity_level_multiplier ?? 0) == 1.9 ? 'selected' : '' }}>Very Active (2x/hari)</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Hitung BMI</button>
        </form>
    </div>

    {{-- RESULT --}}
    <div class="card" id="bmiResult">
        <div class="text-center" style="margin-bottom:var(--space-6);">
            <p class="text-muted" style="font-size:var(--fs-sm);">Body Mass Index</p>
            <div id="bmiValue" style="font-family:var(--font-heading);font-size:72px;font-weight:800;color:var(--color-primary);line-height:1;">--</div>
            <span class="badge badge-success" id="bmiCategory" style="margin-top:var(--space-2);font-size:var(--fs-sm);">--</span>
        </div>
        <div class="bmi-scale">
            <div class="bmi-indicator" id="bmiIndicator" style="left:50%;">●</div>
        </div>
        <div class="bmi-labels">
            <span>Kurang Berat Badan<br>&lt;18.5</span>
            <span>Normal<br>18.5-24.9</span>
            <span>Kelebihan Berat Badan<br>25-29.9</span>
            <span>Obesitas<br>&gt;30</span>
        </div>
        <div style="margin-top:var(--space-6);padding:var(--space-4);background:var(--color-bg-alt);border-radius:var(--radius-lg);">
            <p style="font-size:var(--fs-sm);margin-bottom:var(--space-2);"><strong>Rekomendasi Kalori:</strong></p>
            <div style="display:flex;justify-content:space-between;margin-bottom:var(--space-2);">
                <div>
                    <span class="text-muted" style="font-size:var(--fs-xs);">Pemeliharaan (TDEE)</span>
                    <p id="bmiCalories" style="font-size:var(--fs-lg);font-weight:700;color:var(--color-primary);">-- kcal</p>
                </div>
                <div>
                    <span class="text-muted" style="font-size:var(--fs-xs);">Fat Loss (Defisit)</span>
                    <p id="bmiFatLoss" style="font-size:var(--fs-lg);font-weight:700;color:var(--color-warning);">-- kcal</p>
                </div>
                <div>
                    <span class="text-muted" style="font-size:var(--fs-xs);">Muscle Gain (Surplus)</span>
                    <p id="bmiMuscleGain" style="font-size:var(--fs-lg);font-weight:700;color:var(--color-success);">-- kcal</p>
                </div>
            </div>
            
            <div id="bmiAdviceBox" style="margin-top:var(--space-4);padding-top:var(--space-3);border-top:1px solid var(--color-border-light);">
                <p style="font-size:var(--fs-sm);font-weight:600;margin-bottom:var(--space-1);">💡 Saran Spesialis:</p>
                <p id="bmiAdviceText" class="text-muted" style="font-size:var(--fs-xs);line-height:1.5;">--</p>
            </div>
        </div>
        <button id="saveBmiBtn" class="btn btn-outline btn-block" style="margin-top:var(--space-4);">💾 Simpan ke Profil</button>
    </div>
</div>

{{-- BMI HISTORY --}}
<div class="grid-2" id="tour-step-3" style="margin-top:var(--space-6);gap:var(--space-6);">
    <div class="chart-card">
        <h4>Grafik Riwayat BMI</h4>
        <div class="chart-container"><canvas id="bmiHistoryChart"></canvas></div>
    </div>
    
    <div class="card" style="max-height: 400px; overflow-y: auto;">
        <h4 style="margin-bottom:var(--space-4);">Catatan Terakhir</h4>
        @if(count($bmiRecords) > 0)
            <table class="table" style="width:100%;font-size:var(--fs-sm);text-align:left;">
                <thead>
                    <tr style="border-bottom:1px solid var(--color-border-light);">
                        <th style="padding:var(--space-2) 0;">Tanggal</th>
                        <th style="padding:var(--space-2) 0;">Berat</th>
                        <th style="padding:var(--space-2) 0;">BMI</th>
                        <th style="padding:var(--space-2) 0;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bmiRecords->sortByDesc('recorded_at')->take(5) as $record)
                    <tr style="border-bottom:1px solid var(--color-border-light);">
                        <td style="padding:var(--space-3) 0;color:var(--color-text-muted);">{{ \Carbon\Carbon::parse($record->recorded_at)->format('d M Y') }}</td>
                        <td style="padding:var(--space-3) 0;">{{ $record->weight_kg }} kg</td>
                        <td style="padding:var(--space-3) 0;font-weight:bold;">{{ $record->bmi_value }}</td>
                        <td style="padding:var(--space-3) 0;">
                            @if($record->category == 'Normal')
                                <span style="color:#22C55E;">Normal</span>
                            @elseif($record->category == 'Underweight')
                                <span style="color:#3B82F6;">Kurang</span>
                            @else
                                <span style="color:#F59E0B;">Berlebih</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-muted" style="font-size:var(--fs-sm);text-align:center;padding:var(--space-6) 0;">Belum ada riwayat BMI.</p>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentBmiData = null;
    const form = document.getElementById('bmiForm');
    
    // Auto calculate initially if data exists
    calculateBMI();
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        calculateBMI();
    });

    function calculateBMI() {
        const w = parseFloat(document.getElementById('bmiWeight').value);
        const h = parseFloat(document.getElementById('bmiHeight').value) / 100;
        if (!w || !h) return;
        
        const bmi = (w / (h * h)).toFixed(1);
        document.getElementById('bmiValue').textContent = bmi;
        
        let cat = 'Normal', color = '#22C55E', pos = 45;
        if (bmi < 18.5) { cat = 'Underweight'; color = '#3B82F6'; pos = 12; }
        else if (bmi < 25) { cat = 'Normal'; color = '#22C55E'; pos = 35; }
        else if (bmi < 30) { cat = 'Overweight'; color = '#F59E0B'; pos = 65; }
        else { cat = 'Obese'; color = '#EF4444'; pos = 88; }
        
        const badge = document.getElementById('bmiCategory');
        badge.textContent = translateBmiCategory(cat); 
        badge.style.background = color + '22'; 
        badge.style.color = color;
        document.getElementById('bmiIndicator').style.left = pos + '%';
        
        const activity = parseFloat(document.getElementById('bmiActivity').value);
        const age = parseInt(document.getElementById('bmiAge').value);
        const gender = document.getElementById('bmiGender').value;
        let bmr = gender === 'male' ? 10*w + 6.25*(h*100) - 5*age + 5 : 10*w + 6.25*(h*100) - 5*age - 161;
        const cal = Math.round(bmr * activity);
        
        document.getElementById('bmiCalories').textContent = cal.toLocaleString() + ' kcal';
        document.getElementById('bmiFatLoss').textContent = Math.round(cal - 500).toLocaleString() + ' kcal';
        document.getElementById('bmiMuscleGain').textContent = Math.round(cal + 300).toLocaleString() + ' kcal';

        let advice = "";
        if (cat === 'Underweight') {
            advice = "Fokus pada Surplus Kalori (Muscle Gain). Tingkatkan asupan protein harian dan lakukan latihan beban terstruktur (seperti program Strength di Kelas kami) untuk membangun massa otot secara sehat.";
        } else if (cat === 'Normal') {
            advice = "Luar biasa! Pertahankan berat badan idealmu. Gunakan kalori pemeliharaan (TDEE) dan tetap aktif dengan olahraga kardio & angkat beban secara seimbang.";
        } else if (cat === 'Overweight') {
            advice = "Gunakan target Kalori Fat Loss (Defisit). Sangat disarankan untuk mencoba kelas HIIT Dynamics atau BodyCombat 3x seminggu untuk membakar lemak dengan cepat.";
        } else {
            advice = "Fokus utama pada Defisit Kalori konsisten. Mulailah dengan olahraga low-impact seperti Yoga atau Spinning. Sangat disarankan untuk menyewa Personal Trainer kami untuk panduan yang aman dan efektif.";
        }
        document.getElementById('bmiAdviceText').textContent = advice;

        currentBmiData = {
            weight: w,
            height: h * 100,
            age: age,
            gender: gender,
            activity: activity,
            bmi: bmi,
            category: cat,
            calories: cal,
        };
    }

    document.getElementById('saveBmiBtn').addEventListener('click', function() {
        if (!currentBmiData) {
            Swal.fire({
                title: 'Belum Dihitung',
                text: 'Silakan isi form dan tekan Hitung BMI terlebih dahulu!',
                icon: 'warning'
            });
            return;
        }
        
        const btn = this;
        const originalText = btn.innerHTML;
        btn.innerHTML = 'Menyimpan...';
        btn.disabled = true;

        fetch('{{ route('dashboard.bmi.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(currentBmiData)
        })
        .then(response => response.json())
        .then(data => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            
            if(data.success) {
                Swal.fire({
                    title: 'Tersimpan!',
                    text: data.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Gagal',
                    text: data.message,
                    icon: 'error'
                });
            }
        })
        .catch(err => {
            Swal.fire({
                title: 'Gagal',
                text: 'Koneksi ke server terputus.',
                icon: 'error'
            });
        });
    });

    // Helper function for Indonesian translation
    function translateBmiCategory(category) {
        switch(category) {
            case 'Underweight': return 'Kurang Berat Badan';
            case 'Normal': return 'Normal';
            case 'Overweight': return 'Kelebihan Berat Badan';
            case 'Obese': return 'Obesitas';
            case 'Extremely Obese': return 'Obesitas Ekstrem';
            default: return category;
        }
    }

    // Chart Configuration
    const recordDates = {!! json_encode($chartLabels ?? []) !!};
    const bmiData = {!! json_encode($chartData ?? []) !!};
    const weightDataArr = {!! json_encode($weightData ?? []) !!};
    const targetWeight = {{ $targetWeight ?? 'null' }};
    
    // Fix empty graph visualization
    const chartLabels = recordDates.length > 0 ? recordDates : ['Belum ada data'];
    const chartWeightData = weightDataArr.length > 0 ? weightDataArr : [0];
    
    const datasets = [{
        label:'Berat Badan (kg)',
        data: chartWeightData,
        borderColor:'#FF6B2C',
        backgroundColor:'rgba(255,107,44,0.1)',
        fill:true,
        tension:.4,
        pointRadius:5,
        pointHoverRadius:7
    }];

    // Add Target Line if user has set a target weight
    if (targetWeight && recordDates.length > 0) {
        datasets.push({
            label:'Target Berat (kg)',
            data: Array(chartLabels.length).fill(targetWeight),
            borderColor: '#22C55E', // green
            borderDash: [5, 5], // dashed line
            borderWidth: 2,
            fill: false,
            pointRadius: 0,
            pointHoverRadius: 0
        });
    }

    new Chart(document.getElementById('bmiHistoryChart'), { 
        type:'line', 
        data:{
            labels: chartLabels,
            datasets: datasets
        }, 
        options:{
            responsive:true,
            maintainAspectRatio:false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins:{
                legend:{display:true, position:'bottom'},
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) { label += ': '; }
                            if (context.parsed.y !== null) { label += context.parsed.y + ' kg'; }
                            return label;
                        },
                        afterLabel: function(context) {
                            if(context.datasetIndex === 0 && bmiData[context.dataIndex]) {
                                return 'BMI: ' + bmiData[context.dataIndex];
                            }
                            return null;
                        }
                    }
                }
            },
            scales:{
                y:{
                    title: { display: true, text: 'Berat Badan (kg)' },
                    beginAtZero:false,
                    grid:{color:'#F0F0F2'}
                },
                x:{grid:{display:false}}
            }
        } 
    });

    // ==========================================
    // INTERACTIVE ONBOARDING TOUR (BMI)
    // ==========================================
    if (typeof initPageTour === 'function') {
        initPageTour([
            {
                target: '#tour-step-1',
                title: 'Data Diri & Aktivitas 👤',
                text: 'Masukkan berat, tinggi, usia, dan tingkat aktivitas harianmu dengan jujur agar kalkulasi kalori akurat.',
                position: 'bottom'
            },
            {
                target: '#bmiResult',
                title: 'Hasil Analisis & Rekomendasi 📊',
                text: 'Di sini kamu bisa melihat status BMI-mu, target kalori untuk mencapai *goals*, dan saran medis langsung dari AI.',
                position: 'bottom'
            },
            {
                target: '#tour-step-3',
                title: 'Pantau Perkembanganmu 📈',
                text: 'Setiap kali kamu menyimpan hasil (Simpan ke Profil), grafiknya akan muncul di sini agar kamu bisa memantau *progress* dari bulan ke bulan!',
                position: 'top'
            }
        ], 'fitnessValsTourCompleted_bmi');
    }
});
</script>
@endsection
