@extends('layouts.dashboard')
@section('title', 'Kalori & Nutrisi')
@section('page_title', 'Nutrition Intelligence')
@section('page_subtitle', 'Rekomendasi Pintar Sesuai Latihanmu')

@section('content')
<div class="grid-2" style="gap:var(--space-6);margin-bottom:var(--space-6);grid-template-columns:1fr 1fr;">
    {{-- DAILY FUEL TARGETS --}}
    <div class="card" id="tour-step-1" style="min-width:0;">
        <div class="flex-between" style="margin-bottom:var(--space-4);">
            <h4>Daily Fuel Targets</h4>
            <span class="badge badge-success">{{ number_format($targetCalories) }} kcal Target</span>
        </div>
        <div class="macros-row" style="display:flex; justify-content:space-around; align-items:center; flex-wrap:wrap; gap:10px;">
            @foreach([
                ['val'=>round($consumedProtein),'max'=>round($targetProtein),'color'=>'#FF6B2C','label'=>'Protein'],
                ['val'=>round($consumedCarbs),'max'=>round($targetCarbs),'color'=>'#22D3EE','label'=>'Carbs'],
                ['val'=>round($consumedFat),'max'=>round($targetFat),'color'=>'#9CA3AF','label'=>'Fats']
            ] as $m)
            <div style="display:flex; flex-direction:column; align-items:center;">
                <div class="macro-ring">
                    <svg width="80" height="80" viewBox="0 0 80 80">
                        <circle cx="40" cy="40" r="34" fill="none" stroke="#F0F0F2" stroke-width="6"/>
                        <circle cx="40" cy="40" r="34" fill="none" stroke="{{ $m['color'] }}" stroke-width="6" stroke-dasharray="{{ 2*3.14159*34*($m['val'] == 0 ? 0.05 : $m['val']/$m['max']) }} {{ 2*3.14159*34 }}" stroke-linecap="round"/>
                    </svg>
                    <div class="macro-ring-value">
                        <span>{{ $m['val'] }}</span>
                        <span class="macro-ring-label">/{{ $m['max'] }}g</span>
                    </div>
                </div>
                <div style="text-align:center;margin-top:8px;"><strong>{{ $m['label'] }}</strong></div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- WEEKLY CONSISTENCY --}}
    <div class="card" style="min-width:0;">
        <h4 style="margin-bottom:var(--space-4);">Weekly Consistency</h4>
        <div class="chart-container" style="height:180px; width:100%; position:relative;"><canvas id="weeklyChart"></canvas></div>
    </div>
</div>

{{-- QUICK ADD --}}
<div class="card" id="tour-step-2" style="margin-bottom:var(--space-6);">
    <h4 style="margin-bottom:var(--space-4);">Quick Add</h4>
    <div class="quick-add-grid">
        @foreach([
            ['icon'=>'bi-cup-straw','name'=>'Protein Shake','cal'=>120, 'pro'=>24, 'car'=>3, 'fat'=>1],
            ['icon'=>'bi-egg','name'=>'Oatmeal','cal'=>150, 'pro'=>5, 'car'=>27, 'fat'=>2],
            ['icon'=>'bi-cup-hot','name'=>'Greek Yogurt','cal'=>100, 'pro'=>10, 'car'=>3, 'fat'=>0]
        ] as $q)
        <form action="{{ route('dashboard.nutrition.log') }}" method="POST" style="margin:0;">
            @csrf
            <input type="hidden" name="name" value="{{ $q['name'] }}">
            <input type="hidden" name="meal_type" value="Snack">
            <input type="hidden" name="calories" value="{{ $q['cal'] }}">
            <input type="hidden" name="protein_g" value="{{ $q['pro'] }}">
            <input type="hidden" name="carbs_g" value="{{ $q['car'] }}">
            <input type="hidden" name="fat_g" value="{{ $q['fat'] }}">
            <div class="quick-add-item" onclick="this.closest('form').submit()" style="cursor:pointer;">
                <div class="quick-add-icon"><i class="bi {{ $q['icon'] }}"></i></div>
                <div class="quick-add-info"><h5>{{ $q['name'] }}</h5><span>{{ $q['cal'] }} KCAL</span></div>
                <button type="button" class="quick-add-btn" style="border:none; cursor:pointer; background:none;">+</button>
            </div>
        </form>
        @endforeach
    </div>
</div>

<div class="grid-2" style="gap:var(--space-6);grid-template-columns:1fr 1fr;">
    {{-- INTAKE LOG --}}
    <div class="card" id="tour-step-3" style="min-width:0;">
        <div class="flex-between" style="margin-bottom:var(--space-4);">
            <h4>Riwayat Makanan</h4>
            <button class="btn btn-primary btn-sm" data-modal="addMealModal">⊕ Log Meal</button>
        </div>
        
        <div style="max-height: 500px; overflow-y: auto; padding-right: 5px;">
        @forelse($intakeHistory as $date => $items)
            <div style="margin-bottom: var(--space-4);">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom: var(--space-3);">
                    <div style="height:1px; background:var(--color-border-light); flex:1;"></div>
                    <span style="font-size:var(--fs-xs); color:var(--color-text-muted); font-weight:600; text-transform:uppercase;">
                        {{ \Carbon\Carbon::parse($date)->isToday() ? 'Hari Ini' : \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                    </span>
                    <div style="height:1px; background:var(--color-border-light); flex:1;"></div>
                </div>

                @foreach($items as $item)
                <div class="intake-item">
                    <div class="intake-icon"><i class="bi bi-fire"></i></div>
                    <div class="intake-info">
                        <h5>{{ $item->name }}</h5>
                        <p>{{ $item->meal_type }} <span style="font-size:10px; color:var(--color-text-muted); margin-left:5px;"><i class="bi bi-clock"></i> {{ $item->created_at->format('H:i') }}</span></p>
                        <span style="font-size:10px;color:var(--color-primary);font-weight:600;">P: {{ $item->protein_g }}g  C: {{ $item->carbs_g }}g  F: {{ $item->fat_g }}g</span>
                    </div>
                    <div class="intake-kcal">{{ $item->calories }}<span>kcal</span></div>
                </div>
                @endforeach
            </div>
        @empty
            <div class="text-center" style="padding:var(--space-6) 0;">
                <div style="font-size:48px;color:var(--color-text-muted);margin-bottom:var(--space-3);"><i class="bi bi-basket3"></i></div>
                <p class="text-muted" style="font-size:var(--fs-sm);margin-bottom:var(--space-3);">Belum ada riwayat kalori masuk.<br>Yuk catat makananmu sekarang untuk mencapai target hari ini!</p>
                <button class="btn btn-outline btn-sm" data-modal="addMealModal">Mulai Mencatat</button>
            </div>
        @endforelse
        </div>
    </div>

    {{-- AI RECOMMENDED MEAL --}}
    <div class="ai-meal-card" id="tour-step-4" style="min-width:0; padding:var(--space-5);">
        <div class="flex-between" style="margin-bottom: var(--space-4);">
            <div class="ai-meal-tag" style="margin-bottom: 0;">🤖 WORKOUT-BASED AI RECIPE</div>
        </div>
        
        <div style="background: rgba(255,107,44,0.1); border-left: 3px solid var(--color-primary); padding: var(--space-3); border-radius: var(--radius-sm); margin-bottom: var(--space-4);">
            <p style="font-size: var(--fs-xs);">
                <strong><i class="bi bi-activity"></i> Mode Rekomendasi: {{ strtoupper($workoutIntensity) }}</strong><br>
                <span class="text-muted">
                    Sistem mendeteksi latihan Anda hari ini ({{ $workoutIntensity }}), dan memfilter makanan sesuai dengan kondisi medis Anda.
                </span>
            </p>
        </div>

        @if($recommendedMeals->count() > 0)
        <div style="display:grid; grid-template-columns:1fr; gap:var(--space-4);">
            @foreach($recommendedMeals as $meal)
            <div style="border: 1px solid var(--color-border-light); border-radius:var(--radius-md); overflow:hidden;">
                <div class="ai-meal-img">
                    <img src="{{ $meal->image_url ?? 'https://images.unsplash.com/photo-1467003909585-2f8a72700288?w=400&h=200&fit=crop' }}" alt="{{ $meal->name }}" style="width:100%; height:150px; object-fit:cover;">
                    <div class="ai-meal-badge">MATCH ({{ $meal->match_rate }}%)</div>
                </div>
                <div class="ai-meal-body" style="padding:var(--space-3);">
                    <h4 style="font-size:var(--fs-md); margin-bottom:var(--space-2);">{{ $meal->name }}</h4>
                    <div class="ai-meal-macros" style="margin-bottom:var(--space-3); display:flex; gap:10px; flex-wrap:wrap;">
                        <div style="flex:1;"><div class="val">{{ $meal->calories }}</div><div class="lbl">KCAL</div></div>
                        <div style="flex:1;"><div class="val">{{ $meal->protein_g }}g</div><div class="lbl">PROT</div></div>
                        <div style="flex:1;"><div class="val">{{ $meal->carbs_g }}g</div><div class="lbl">CARB</div></div>
                        <div style="flex:1;"><div class="val">{{ $meal->fat_g }}g</div><div class="lbl">FAT</div></div>
                    </div>
                    <p style="font-size:var(--fs-xs);color:var(--color-text-muted);margin-bottom:var(--space-4);">{{ $meal->description }}</p>
                    
                    <form action="{{ route('dashboard.nutrition.log') }}" method="POST">
                        @csrf
                        <input type="hidden" name="name" value="{{ $meal->name }}">
                        <input type="hidden" name="meal_type" value="{{ $autoMealType }}">
                        <input type="hidden" name="calories" value="{{ $meal->calories }}">
                        <input type="hidden" name="protein_g" value="{{ $meal->protein_g }}">
                        <input type="hidden" name="carbs_g" value="{{ $meal->carbs_g }}">
                        <input type="hidden" name="fat_g" value="{{ $meal->fat_g }}">
                        <button type="submit" class="btn btn-primary btn-block btn-sm">⊕ Log Makanan</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center text-muted">Tidak ada rekomendasi yang sesuai.</div>
        @endif
    </div>
</div>

{{-- FILTER PREFERENCES MODAL --}}
<div class="modal-overlay" id="filterPreferencesModal">
    <div class="modal">
        <div class="modal-header"><h3>Filter AI Rekomendasi</h3><button data-close-modal style="font-size:20px;"><i class="bi bi-x-lg"></i></button></div>
        <p style="font-size: var(--fs-sm); color: var(--color-text-muted); margin-bottom: var(--space-4);">Sesuaikan rekomendasi makanan dengan kondisi medis dan preferensimu.</p>
        <div class="form-group">
            <label class="form-label">Riwayat Medis / Alergi</label>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <label style="font-size: var(--fs-sm);"><input type="checkbox" checked> Diabetes (Low Sugar)</label>
                <label style="font-size: var(--fs-sm);"><input type="checkbox"> Hipertensi (Low Sodium)</label>
                <label style="font-size: var(--fs-sm);"><input type="checkbox"> Alergi Kacang</label>
                <label style="font-size: var(--fs-sm);"><input type="checkbox"> Alergi Seafood</label>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Preferensi Diet</label>
            <select class="form-input form-select">
                <option>Tidak Ada Spesifik</option>
                <option>Vegan</option>
                <option>Vegetarian</option>
                <option>Keto</option>
                <option>Gluten-Free</option>
            </select>
        </div>
        <button class="btn btn-primary btn-block">Terapkan Filter</button>
    </div>
</div>

{{-- ADD MEAL MODAL --}}
<div class="modal-overlay" id="addMealModal">
    <div class="modal" style="width:550px;">
        <div class="modal-header"><h3>Tambah Makanan Manual</h3><button data-close-modal style="font-size:20px;"><i class="bi bi-x-lg"></i></button></div>
        <form action="{{ route('dashboard.nutrition.log') }}" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group" style="flex:1;">
                    <label class="form-label">Waktu Makan</label>
                    <select name="meal_type" class="form-input form-select">
                        <option>Sarapan</option><option>Makan Siang</option><option>Makan Malam</option><option>Snack</option>
                    </select>
                </div>
                <div class="form-group" style="flex:2;">
                    <label class="form-label">Makanan Utama</label>
                    <div style="display:flex; gap:10px;">
                        <select id="mainFoodSelect" class="form-input form-select" style="flex:2;">
                            <option value="" data-cal="0" data-pro="0" data-car="0" data-fat="0">-- Pilih Makanan --</option>
                            <option value="Nasi Putih" data-cal="1.3" data-pro="0.027" data-car="0.28" data-fat="0.003">Nasi Putih</option>
                            <option value="Nasi Merah" data-cal="1.1" data-pro="0.025" data-car="0.23" data-fat="0.009">Nasi Merah</option>
                            <option value="Dada Ayam Rebus" data-cal="1.65" data-pro="0.31" data-car="0" data-fat="0.036">Dada Ayam Rebus</option>
                            <option value="Dada Ayam Bakar" data-cal="1.85" data-pro="0.30" data-car="0" data-fat="0.05">Dada Ayam Bakar</option>
                            <option value="Paha Ayam Goreng" data-cal="2.9" data-pro="0.25" data-car="0.1" data-fat="0.15">Paha Ayam Goreng</option>
                            <option value="Daging Sapi Panggang" data-cal="2.5" data-pro="0.26" data-car="0" data-fat="0.15">Daging Sapi Panggang</option>
                            <option value="Ikan Salmon Panggang" data-cal="2.06" data-pro="0.22" data-car="0" data-fat="0.13">Ikan Salmon Panggang</option>
                            <option value="Ikan Nila Goreng" data-cal="2.0" data-pro="0.20" data-car="0" data-fat="0.10">Ikan Nila Goreng</option>
                            <option value="Oatmeal Matang" data-cal="0.68" data-pro="0.024" data-car="0.12" data-fat="0.014">Oatmeal Matang</option>
                            <option value="Kentang Rebus" data-cal="0.87" data-pro="0.019" data-car="0.20" data-fat="0.001">Kentang Rebus</option>
                            <option value="Ubi Jalar Rebus" data-cal="0.86" data-pro="0.016" data-car="0.20" data-fat="0.001">Ubi Jalar Rebus</option>
                            <option value="Roti Gandum" data-cal="2.5" data-pro="0.13" data-car="0.41" data-fat="0.04">Roti Gandum</option>
                            <option value="Mie Instan Goreng" data-cal="4.0" data-pro="0.10" data-car="0.60" data-fat="0.15">Mie Instan Goreng</option>
                            <option value="Salad Sayur Polos" data-cal="0.5" data-pro="0.02" data-car="0.10" data-fat="0">Salad Sayur Polos</option>
                        </select>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <input type="number" id="mainFoodWeight" class="form-input" value="100" min="0" style="width: 80px; padding: 8px;" title="Estimasi Berat (gram)">
                            <span style="font-size:12px; color:var(--color-text-muted); font-weight:600;">gr</span>
                        </div>
                    </div>
                    <span class="text-muted" style="font-size:10px; margin-top:4px; display:block;">Isi estimasi berat. Biarkan 100gr jika tidak tahu.</span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Tambahan Lauk / Snack (Pilih bisa lebih dari 1)</label>
                <div style="display:flex; flex-wrap:wrap; gap:var(--space-2); background:var(--color-bg-alt); padding:var(--space-3); border-radius:var(--radius-md); border:1px solid var(--color-border-light); max-height: 120px; overflow-y: auto;">
                    <label class="badge badge-outline" style="cursor:pointer;padding:6px 10px;margin:0;"><input type="checkbox" class="side-food-cb" value="Telur Rebus" data-cal="78" data-pro="6" data-car="0.6" data-fat="5" style="margin-right:4px;"> Telur Rebus (1 btr)</label>
                    <label class="badge badge-outline" style="cursor:pointer;padding:6px 10px;margin:0;"><input type="checkbox" class="side-food-cb" value="Telur Mata Sapi" data-cal="90" data-pro="6" data-car="1" data-fat="7" style="margin-right:4px;"> Telur Mata Sapi</label>
                    <label class="badge badge-outline" style="cursor:pointer;padding:6px 10px;margin:0;"><input type="checkbox" class="side-food-cb" value="Tempe Goreng" data-cal="193" data-pro="19" data-car="9" data-fat="11" style="margin-right:4px;"> Tempe Goreng</label>
                    <label class="badge badge-outline" style="cursor:pointer;padding:6px 10px;margin:0;"><input type="checkbox" class="side-food-cb" value="Tahu Rebus" data-cal="76" data-pro="8" data-car="2" data-fat="5" style="margin-right:4px;"> Tahu Rebus</label>
                    <label class="badge badge-outline" style="cursor:pointer;padding:6px 10px;margin:0;"><input type="checkbox" class="side-food-cb" value="Tahu Goreng" data-cal="115" data-pro="10" data-car="3" data-fat="7" style="margin-right:4px;"> Tahu Goreng</label>
                    <label class="badge badge-outline" style="cursor:pointer;padding:6px 10px;margin:0;"><input type="checkbox" class="side-food-cb" value="Brokoli Rebus" data-cal="35" data-pro="2.4" data-car="7" data-fat="0.4" style="margin-right:4px;"> Brokoli Rebus</label>
                    <label class="badge badge-outline" style="cursor:pointer;padding:6px 10px;margin:0;"><input type="checkbox" class="side-food-cb" value="Bayam Rebus" data-cal="23" data-pro="3" data-car="4" data-fat="0" style="margin-right:4px;"> Bayam Rebus</label>
                    <label class="badge badge-outline" style="cursor:pointer;padding:6px 10px;margin:0;"><input type="checkbox" class="side-food-cb" value="Kerupuk Udang" data-cal="35" data-pro="0.5" data-car="4" data-fat="2" style="margin-right:4px;"> Kerupuk Udang</label>
                    <label class="badge badge-outline" style="cursor:pointer;padding:6px 10px;margin:0;"><input type="checkbox" class="side-food-cb" value="Sambal" data-cal="15" data-pro="0" data-car="3" data-fat="0" style="margin-right:4px;"> Sambal (1 sdm)</label>
                    <label class="badge badge-outline" style="cursor:pointer;padding:6px 10px;margin:0;"><input type="checkbox" class="side-food-cb" value="Susu Protein" data-cal="120" data-pro="24" data-car="3" data-fat="1" style="margin-right:4px;"> Susu Protein</label>
                    <label class="badge badge-outline" style="cursor:pointer;padding:6px 10px;margin:0;"><input type="checkbox" class="side-food-cb" value="Pisang" data-cal="89" data-pro="1" data-car="23" data-fat="0.3" style="margin-right:4px;"> Pisang (1 bh)</label>
                    <label class="badge badge-outline" style="cursor:pointer;padding:6px 10px;margin:0;"><input type="checkbox" class="side-food-cb" value="Apel" data-cal="52" data-pro="0.3" data-car="14" data-fat="0.2" style="margin-right:4px;"> Apel (1 bh)</label>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Hidangan (Bisa diedit)</label>
                <input type="text" name="name" id="mealNameInput" class="form-input" placeholder="Misal: Nasi Putih + Telur Rebus" required>
            </div>
            
            <h5 style="margin-bottom:var(--space-2); font-size:var(--fs-sm);">Kandungan Nutrisi (Auto Calculated)</h5>
            <div class="form-row">
                <div class="form-group"><label class="form-label text-muted" style="font-size:10px;">Kalori (kcal)</label><input type="number" step="0.1" name="calories" id="mealCalInput" class="form-input" placeholder="kcal" required></div>
                <div class="form-group"><label class="form-label text-muted" style="font-size:10px;">Protein (g)</label><input type="number" step="0.1" name="protein_g" id="mealProInput" class="form-input" required></div>
                <div class="form-group"><label class="form-label text-muted" style="font-size:10px;">Karbo (g)</label><input type="number" step="0.1" name="carbs_g" id="mealCarInput" class="form-input" required></div>
                <div class="form-group"><label class="form-label text-muted" style="font-size:10px;">Lemak (g)</label><input type="number" step="0.1" name="fat_g" id="mealFatInput" class="form-input" required></div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block" style="margin-top:var(--space-3);">Simpan Makanan</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    new Chart(document.getElementById('weeklyChart'), {
        type: 'bar', data: { labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
        datasets: [{ data: [2200,2450,2100,2380,2500,1800,900], backgroundColor: ['#FF6B2C','#FF6B2C','#FF6B2C','#FF6B2C','#22D3EE','#9CA3AF','#E5E5E7'], borderRadius: 6 }] },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { display: false }, x: { grid: { display: false } } } }
    });

    // Auto Fill Logic for Add Meal Modal
    const mainFoodSelect = document.getElementById('mainFoodSelect');
    const mainFoodWeight = document.getElementById('mainFoodWeight');
    const sideFoodCbs = document.querySelectorAll('.side-food-cb');
    
    const mealNameInput = document.getElementById('mealNameInput');
    const mealCalInput = document.getElementById('mealCalInput');
    const mealProInput = document.getElementById('mealProInput');
    const mealCarInput = document.getElementById('mealCarInput');
    const mealFatInput = document.getElementById('mealFatInput');

    function calculateNutrition() {
        let nameParts = [];
        let totalCal = 0, totalPro = 0, totalCar = 0, totalFat = 0;

        // Weight Multiplier
        let weight = parseFloat(mainFoodWeight?.value) || 0;
        let weightLabel = weight > 0 && weight !== 100 ? ` (${weight}g)` : '';

        // Add Main Food (Multiplier logic: data values are per 1 gram)
        if (mainFoodSelect && mainFoodSelect.value) {
            const selectedOpt = mainFoodSelect.options[mainFoodSelect.selectedIndex];
            nameParts.push(selectedOpt.value + weightLabel);
            
            totalCal += (parseFloat(selectedOpt.getAttribute('data-cal')) || 0) * weight;
            totalPro += (parseFloat(selectedOpt.getAttribute('data-pro')) || 0) * weight;
            totalCar += (parseFloat(selectedOpt.getAttribute('data-car')) || 0) * weight;
            totalFat += (parseFloat(selectedOpt.getAttribute('data-fat')) || 0) * weight;
        }

        // Add Side Foods (Fixed portions)
        if(sideFoodCbs) {
            sideFoodCbs.forEach(cb => {
                if (cb.checked) {
                    nameParts.push(cb.value);
                    totalCal += parseFloat(cb.getAttribute('data-cal')) || 0;
                    totalPro += parseFloat(cb.getAttribute('data-pro')) || 0;
                    totalCar += parseFloat(cb.getAttribute('data-car')) || 0;
                    totalFat += parseFloat(cb.getAttribute('data-fat')) || 0;
                }
            });
        }

        // Set Values
        if(mealNameInput) mealNameInput.value = nameParts.join(' + ');
        if(mealCalInput) mealCalInput.value = totalCal.toFixed(1);
        if(mealProInput) mealProInput.value = totalPro.toFixed(1);
        if(mealCarInput) mealCarInput.value = totalCar.toFixed(1);
        if(mealFatInput) mealFatInput.value = totalFat.toFixed(1);
    }

    if(mainFoodSelect) mainFoodSelect.addEventListener('change', calculateNutrition);
    if(mainFoodWeight) mainFoodWeight.addEventListener('input', calculateNutrition);
    if(sideFoodCbs) {
        sideFoodCbs.forEach(cb => {
            cb.addEventListener('change', calculateNutrition);
        });
    }

    // ==========================================
    // INTERACTIVE ONBOARDING TOUR (NUTRITION)
    // ==========================================
    if (typeof initPageTour === 'function') {
        initPageTour([
            {
                target: '#tour-step-1',
                title: 'Target Makro Nutrisi 🎯',
                text: 'Pantau asupan Protein, Karbohidrat, dan Lemak harianmu di sini. Pastikan lingkarannya terisi penuh!',
                position: 'bottom'
            },
            {
                target: '#tour-step-2',
                title: 'Quick Add Cepat ⚡',
                text: 'Sering makan makanan ini? Klik logo plus untuk mencatat kalori dalam satu detik tanpa perlu repot mengisi form.',
                position: 'bottom'
            },
            {
                target: '#tour-step-3',
                title: 'Riwayat & Log Manual 📝',
                text: 'Gunakan tombol ⊕ Log Meal di sini jika makanan yang kamu konsumsi tidak ada di Quick Add.',
                position: 'right'
            },
            {
                target: '#tour-step-4',
                title: 'Rekomendasi Pintar AI 🤖',
                text: 'Makan apa hari ini? Fitur khusus member Pro & Elite ini merekomendasikan hidangan berdasarkan targetmu. Langsung klik log makanan!',
                position: 'left'
            }
        ], 'fitnessValsTourCompleted_nutrition');
    }
});
</script>
@endsection
