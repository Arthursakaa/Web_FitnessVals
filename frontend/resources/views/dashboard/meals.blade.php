@extends('layouts.dashboard')
@section('title', 'Rekomendasi Makanan')
@section('page_title', 'AI Meal Recommendations')
@section('page_subtitle', 'Personalized nutrition powered by AI')

@section('content')
{{-- PREFERENCES CARD --}}
<div class="card" id="tour-step-1" style="margin-bottom:var(--space-6);border-left:4px solid var(--color-accent);">
    <div class="flex-between">
        <div>
            <h4>Profil Nutrisi Kamu</h4>
            <p class="text-muted" style="font-size:var(--fs-sm);">
                Tujuan: <strong>{{ $profile?->fitness_goal ?? 'Normal' }}</strong> • 
                Preferensi: <strong>{{ $profile?->dietary_preference ?? 'Normal' }}</strong> • 
                Alergi/Medis: <strong>{{ $profile && is_array($profile->medical_history) && count($profile->medical_history) > 0 ? implode(', ', $profile->medical_history) : 'Tidak Ada' }}</strong> • 
                Target: <strong>{{ number_format($targetCalories) }} kcal/hari</strong>
            </p>
        </div>
        <button class="btn btn-outline btn-sm" data-modal="prefModal">Edit Preferences</button>
    </div>
</div>

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({ title: 'Berhasil!', text: "{{ session('success') }}", icon: 'success', confirmButtonColor: 'var(--color-primary)' });
});
</script>
@endif

<div class="flex-gap" id="tour-step-2" style="margin-bottom:var(--space-6);flex-wrap:wrap;">
    <button class="btn btn-primary btn-sm">Semua</button>
    <div style="margin-left:auto;"><a href="{{ route('dashboard.meals') }}" class="btn btn-outline btn-sm"><i class="bi bi-arrow-repeat"></i> Generate Ulang</a></div>
</div>

{{-- MEAL SECTIONS --}}
@foreach([
    ['time'=>'<i class="bi bi-sunrise"></i> Sarapan','meals'=>$breakfastMeals],
    ['time'=>'<i class="bi bi-sun"></i> Makan Siang','meals'=>$lunchMeals],
    ['time'=>'<i class="bi bi-moon"></i> Makan Malam','meals'=>$dinnerMeals],
] as $section)
@if($section['meals']->count() > 0)
<div style="margin-bottom:var(--space-8);">
    <h4 style="margin-bottom:var(--space-4);">{!! $section['time'] !!}</h4>
    <div class="grid-2" style="gap:var(--space-5);">
        @foreach($section['meals'] as $meal)
        <div class="card" style="padding:0;overflow:hidden;">
            <div style="display:flex;">
                <div style="width:140px;min-height:160px;flex-shrink:0;">
                    <img src="{{ $meal->image_url ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=200&h=200&fit=crop' }}" style="width:100%;height:100%;object-fit:cover;" alt="">
                </div>
                <div style="padding:var(--space-4);flex:1; display:flex; flex-direction:column; justify-content:space-between;">
                    <div>
                        <h5 style="margin-bottom:var(--space-2);">{{ $meal->name }}</h5>
                        <div style="display:flex;gap:var(--space-3);margin-bottom:var(--space-2);font-size:var(--fs-xs);">
                            <span><strong>{{ $meal->calories }}</strong> kcal</span>
                            <span>P: {{ $meal->protein_g }}g</span>
                            <span>C: {{ $meal->carbs_g }}g</span>
                            <span>F: {{ $meal->fat_g }}g</span>
                        </div>
                        <div style="display:flex;gap:var(--space-1);flex-wrap:wrap;margin-bottom:var(--space-3);">
                            @if(is_array($meal->dietary_tags))
                                @foreach($meal->dietary_tags as $tag)
                                <span class="badge badge-accent" style="font-size:9px;padding:2px 8px;">{{ $tag }}</span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    
                    <form action="{{ route('dashboard.nutrition.log') }}" method="POST">
                        @csrf
                        @php
                            $hour = date('H');
                            $autoMealType = 'Makan Malam';
                            if ($hour >= 4 && $hour < 11) $autoMealType = 'Sarapan';
                            elseif ($hour >= 11 && $hour < 15) $autoMealType = 'Makan Siang';
                            elseif ($hour >= 15 && $hour < 18) $autoMealType = 'Snack';
                        @endphp
                        <input type="hidden" name="name" value="{{ $meal->name }}">
                        <input type="hidden" name="meal_type" value="{{ $autoMealType }}">
                        <input type="hidden" name="calories" value="{{ $meal->calories }}">
                        <input type="hidden" name="protein_g" value="{{ $meal->protein_g }}">
                        <input type="hidden" name="carbs_g" value="{{ $meal->carbs_g }}">
                        <input type="hidden" name="fat_g" value="{{ $meal->fat_g }}">
                        <button type="submit" class="btn btn-outline btn-sm" style="font-size:var(--fs-xs);width:100%;">+ Tambah ke Log</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
@endforeach

{{-- WARNING --}}
@if($profile && is_array($profile->medical_history) && count(array_filter($profile->medical_history, fn($x) => $x !== 'Tidak Ada')) > 0)
<div class="card" style="border-left:4px solid var(--color-danger);margin-bottom:var(--space-6);">
    <div class="flex-gap">
        <span style="font-size:20px;"><i class="bi bi-exclamation-triangle"></i></span>
        <div>
            <strong style="color:var(--color-danger);">Filter Kondisi Medis Aktif</strong>
            <p class="text-muted" style="font-size:var(--fs-sm);">Berdasarkan profil kamu, AI merekomendasikan makanan yang aman untuk: <strong>{{ implode(', ', array_filter($profile->medical_history, fn($x) => $x !== 'Tidak Ada')) }}</strong>.</p>
        </div>
    </div>
</div>
@endif

{{-- PREFERENCES MODAL --}}
<div class="modal-overlay" id="prefModal">
    <div class="modal">
        <div class="modal-header"><h3>Edit Preferensi Nutrisi</h3><button data-close-modal style="font-size:20px;"><i class="bi bi-x-lg"></i></button></div>
        <form action="{{ route('dashboard.profile.health') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Tujuan</label>
                <select name="fitness_goal" class="form-input form-select">
                    <option {{ ($profile?->fitness_goal ?? '') == 'Fat Loss' ? 'selected' : '' }}>Fat Loss</option>
                    <option {{ ($profile?->fitness_goal ?? '') == 'Muscle Gain' ? 'selected' : '' }}>Muscle Gain</option>
                    <option {{ ($profile?->fitness_goal ?? '') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option {{ ($profile?->fitness_goal ?? '') == 'Endurance' ? 'selected' : '' }}>Endurance</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Preferensi Diet</label>
                <select name="dietary_preference" class="form-input form-select">
                    <option value="Normal" {{ ($profile?->dietary_preference ?? '') == 'Normal' ? 'selected' : '' }}>Normal</option>
                    <option value="Vegan" {{ ($profile?->dietary_preference ?? '') == 'Vegan' ? 'selected' : '' }}>Vegan</option>
                    <option value="Vegetarian" {{ ($profile?->dietary_preference ?? '') == 'Vegetarian' ? 'selected' : '' }}>Vegetarian</option>
                    <option value="Gluten-Free" {{ ($profile?->dietary_preference ?? '') == 'Gluten-Free' ? 'selected' : '' }}>Gluten-Free</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Riwayat Medis & Alergi</label>
                <div style="display:flex;flex-wrap:wrap;gap:var(--space-2);">
                    @php $currentMed = $profile && is_array($profile->medical_history) ? $profile->medical_history : []; @endphp
                    @foreach(['Tidak Ada', 'Diabetes', 'Hipertensi', 'Kolesterol', 'Penyakit Jantung', 'Alergi Kacang', 'Alergi Seafood', 'Alergi Susu (Laktosa)'] as $d)
                    <label class="badge badge-outline" style="cursor:pointer;">
                        <input type="checkbox" name="medical_history[]" value="{{ $d }}" style="margin-right:4px;" {{ in_array($d, $currentMed) ? 'checked' : '' }}> {{ $d }}
                    </label>
                    @endforeach
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Simpan Preferensi</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ==========================================
    // INTERACTIVE ONBOARDING TOUR (MEALS)
    // ==========================================
    if (typeof initPageTour === 'function') {
        initPageTour([
            {
                target: '#tour-step-1',
                title: 'Profil Nutrisimu 🧬',
                text: 'AI meracik makanan khusus untukmu berdasarkan Tujuan (Fat Loss/Muscle Gain) dan pantangan medismu. Pastikan klik Edit Preferences jika ada perubahan.',
                position: 'bottom'
            },
            {
                target: '#tour-step-2',
                title: 'Variasi Tanpa Batas 🔄',
                text: 'Bosan dengan menu ini? Klik Generate Ulang kapan saja untuk mendapatkan variasi menu baru yang setara secara nutrisi!',
                position: 'bottom'
            }
        ], 'fitnessValsTourCompleted_meals');
    }
});
</script>
@endsection
