@extends('layouts.dashboard')
@section('title', 'Jadwal Kelas')
@section('page_title', 'Jadwal Kelas')

@section('content')
<div class="flex-between" id="tour-step-1" style="margin-bottom:var(--space-6);flex-wrap:wrap;gap:var(--space-4);">
    <div class="flex-gap">
        <a href="{{ route('dashboard.schedule', ['week' => $offset - 1]) }}" class="btn btn-outline btn-sm"><i class="bi bi-chevron-left"></i> Minggu Lalu</a>
        <button class="btn btn-primary btn-sm">Minggu Ini</button>
        <a href="{{ route('dashboard.schedule', ['week' => $offset + 1]) }}" class="btn btn-outline btn-sm">Minggu Depan <i class="bi bi-chevron-right"></i></a>
    </div>
    <div class="flex-gap">
        <select id="filterCategory" class="form-input form-select" style="width:auto;padding:8px 36px 8px 12px;font-size:var(--fs-sm);">
            <option value="all">Semua Kategori</option>
            <option value="strength">Strength</option>
            <option value="cardio">Cardio</option>
            <option value="yoga">Yoga</option>
            <option value="hiit">HIIT</option>
            <option value="dance">Dance (Zumba, dll)</option>
        </select>
        <select id="filterInstructor" class="form-input form-select" style="width:auto;padding:8px 36px 8px 12px;font-size:var(--fs-sm);">
            <option value="all">Semua Instruktur</option>
            @foreach($instructors as $inst)
            <option value="{{ strtolower($inst) }}">{{ $inst }}</option>
            @endforeach
        </select>
    </div>
</div>

{{-- CALENDAR GRID --}}
<div class="card" id="tour-step-2" style="padding:0;overflow-x:auto;margin-bottom:var(--space-6);">
    <div class="schedule-grid">
        <div class="schedule-header"></div>
        @foreach($days as $dateStr)
        <div class="schedule-header">{{ \Carbon\Carbon::parse($dateStr)->format('D, d M') }}</div>
        @endforeach

        @foreach(['07:00','08:00','09:00','10:00','16:00','17:00','18:00'] as $time)
        <div class="schedule-time">{{ $time }}</div>
        @foreach($days as $dateStr)
        <div class="schedule-slot">
            @if(isset($matrix[$dateStr][$time]))
                @php 
                    $sch = $matrix[$dateStr][$time];
                    $isBooked = in_array($sch->id, $userBookings);
                    $cls = strtolower($sch->gymClass->type);
                    $icon = 'bi-activity';
                    if($cls=='strength') $icon='bi-trophy';
                    if($cls=='cardio') $icon='bi-person-walking';
                    if($cls=='hiit') $icon='bi-fire';
                    if(str_contains($cls,'yoga')) $icon='bi-heart-pulse';
                    if($cls=='dance') $icon='bi-music-note-beamed';
                    
                    $isPast = \Carbon\Carbon::parse($sch->start_time)->isPast();
                    $descEscaped = htmlspecialchars($sch->gymClass->description ?? 'Tidak ada deskripsi tambahan untuk kelas ini.', ENT_QUOTES);
                @endphp
                <div class="schedule-class {{ $cls }} {{ $isPast ? 'past-class' : '' }}" 
                     @if($isPast)
                     style="opacity:0.6; filter:grayscale(0.8); cursor:not-allowed;"
                     @else
                     {!! $isBooked ? 'style="background:rgba(255,107,44,0.15);color:#D4510F;border-left-color:var(--color-primary);"' : 'data-modal="classBookModal" onclick="openBookModal('.$sch->id.', \''.$sch->gymClass->name.'\', \''.\Carbon\Carbon::parse($sch->start_time)->format('l, d M H:i').'\', \''.$sch->trainer_name.'\', '.$sch->current_bookings.', '.$sch->gymClass->max_capacity.', '.$sch->gymClass->duration_minutes.', \''.$cls.'\', \''.$icon.'\', \''.$descEscaped.'\')"' !!} 
                     @endif
                     id="class-schedule-{{ $sch->id }}"
                     data-category="{{ $cls }}"
                     data-instructor="{{ strtolower($sch->trainer_name) }}">
                    <i class="bi {{ $icon }}"></i> {{ $sch->gymClass->name }}<br>
                    <small>{{ $sch->trainer_name }} • 
                        @if($isPast)
                            <i class="bi bi-clock-history"></i> Selesai
                        @elseif($isBooked)
                            <i class="bi bi-check-lg"></i> Terdaftar
                        @else
                            <span id="capacity-{{ $sch->id }}">{{ $sch->current_bookings }}</span>/{{ $sch->gymClass->max_capacity }}
                        @endif
                    </small>
                </div>
            @endif
        </div>
        @endforeach
        @endforeach
    </div>
</div>

{{-- MY CLASSES --}}
<div class="grid-2" style="gap:var(--space-6);">
    <div class="card" id="tour-step-3">
        <h4 style="margin-bottom:var(--space-4);"><i class="bi bi-clipboard-check"></i> Kelas Terdaftar</h4>
        @forelse($myBookings as $b)
        <div style="display:flex;align-items:center;gap:var(--space-3);padding:var(--space-3) 0;border-bottom:1px solid var(--color-border-light);">
            <div style="width:6px;height:40px;border-radius:3px;background:var(--color-accent);"></div>
            <div style="flex:1;"><strong style="font-size:var(--fs-sm);">{{ $b->gymClass->name }}</strong><br><span style="font-size:var(--fs-xs);color:var(--color-text-muted);">{{ \Carbon\Carbon::parse($b->start_time)->format('l, H:i') }} • {{ $b->trainer_name }}</span></div>
            <button onclick="cancelBooking({{ $b->id }})" class="btn btn-sm" style="color:var(--color-danger);font-size:var(--fs-xs);">Batalkan</button>
        </div>
        @empty
        <p class="text-muted" style="font-size:var(--fs-sm);padding:var(--space-3) 0;">Belum ada kelas yang didaftar.</p>
        @endforelse
    </div>
    <div class="card">
        <h4 style="margin-bottom:var(--space-4);">📜 Riwayat Kelas</h4>
        @forelse($pastBookings ?? [] as $h)
        <div style="display:flex;align-items:center;gap:var(--space-3);padding:var(--space-3) 0;border-bottom:1px solid var(--color-border-light);">
            <span style="color:var(--color-accent-2);"><i class="bi bi-check-lg"></i></span>
            <div style="flex:1;"><strong style="font-size:var(--fs-sm);">{{ $h->gymClass->name }}</strong><br><span style="font-size:var(--fs-xs);color:var(--color-text-muted);">{{ \Carbon\Carbon::parse($h->start_time)->format('d M') }} • {{ $h->trainer_name }}</span></div>
        </div>
        @empty
        <p class="text-muted" style="font-size:var(--fs-sm);padding:var(--space-3) 0;">Belum ada riwayat kelas.</p>
        @endforelse
    </div>
</div>

<div class="modal-overlay" id="classBookModal">
    <div class="modal">
        <div class="modal-header"><h3>Detail Booking Kelas</h3><button data-close-modal style="font-size:20px;"><i class="bi bi-x-lg"></i></button></div>
        <div style="text-align:center;padding:var(--space-4) 0;border-bottom:1px solid var(--color-border-light);margin-bottom:var(--space-4);">
            <div style="font-size:48px;margin-bottom:var(--space-2);color:var(--color-primary);"><i class="bi bi-trophy" id="modalIcon"></i></div>
            <h3 id="modalClassName" style="margin-bottom:var(--space-2);">--</h3>
            <span class="badge badge-accent" id="modalCategoryBadge" style="margin-bottom:var(--space-3);text-transform:capitalize;">--</span>
            <p style="font-size:var(--fs-md);margin-bottom:var(--space-1);"><i class="bi bi-calendar-event"></i> <strong id="modalTimeTrainer">--</strong></p>
            <p class="text-muted" style="font-size:var(--fs-sm);"><i class="bi bi-clock"></i> Durasi: <span id="modalDuration">--</span> Menit</p>
        </div>
        <div style="margin-bottom:var(--space-5);">
            <strong>Deskripsi Kelas:</strong>
            <p class="text-muted" style="font-size:var(--fs-sm);margin-top:var(--space-2);" id="modalDescription">--</p>
        </div>
        <div class="flex-between" style="margin-bottom:var(--space-4);background:var(--color-bg-alt);padding:var(--space-3);border-radius:var(--radius-md);">
            <span style="font-size:var(--fs-sm);"><strong>Sisa Kuota:</strong></span>
            <strong id="modalCapacity" style="color:var(--color-accent-2);">--/-- tersedia</strong>
        </div>
        <button id="btnConfirmBook" class="btn btn-primary btn-block btn-lg" style="margin-bottom:var(--space-3);">Konfirmasi Booking</button>
        <button class="btn btn-ghost btn-block" data-close-modal id="btnCloseModal">Batal</button>
    </div>
</div>
@endsection

@section('scripts')
<script type="module">
    let selectedScheduleId = null;

    // Filter Logic
    const filterCat = document.getElementById('filterCategory');
    const filterInst = document.getElementById('filterInstructor');
    const classCards = document.querySelectorAll('.schedule-class');

    function applyFilters() {
        const cat = filterCat.value;
        const inst = filterInst.value;
        classCards.forEach(card => {
            const cardCat = card.getAttribute('data-category');
            const cardInst = card.getAttribute('data-instructor');
            let show = true;
            if(cat !== 'all' && cardCat !== cat) show = false;
            if(inst !== 'all' && cardInst !== inst) show = false;
            card.style.display = show ? 'block' : 'none';
        });
    }

    if(filterCat) filterCat.addEventListener('change', applyFilters);
    if(filterInst) filterInst.addEventListener('change', applyFilters);

    window.openBookModal = function(id, name, time, trainer, current, max, duration, category, icon, desc) {
        selectedScheduleId = id;
        document.getElementById('modalClassName').innerText = name;
        document.getElementById('modalTimeTrainer').innerText = time + ' • ' + trainer;
        document.getElementById('modalCapacity').innerText = (max - current) + ' Kursi Tersisa (' + current + '/' + max + ')';
        document.getElementById('modalDuration').innerText = duration;
        document.getElementById('modalCategoryBadge').innerText = category;
        document.getElementById('modalIcon').className = 'bi ' + icon;
        document.getElementById('modalDescription').innerText = desc;
    }

    document.getElementById('btnConfirmBook').addEventListener('click', function() {
        if(!selectedScheduleId) return;
        
        // Add loading state
        const btn = document.getElementById('btnConfirmBook');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
        btn.disabled = true;
        
        fetch('{{ route('dashboard.schedule.book') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ schedule_id: selectedScheduleId })
        })
        .then(res => res.json())
        .then(data => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            if(data.success) {
                // Close modal first
                const modalOverlay = document.getElementById('classBookModal');
                modalOverlay.classList.remove('active');
                
                Swal.fire({
                    toast: true,
                    position: 'bottom-end',
                    icon: 'success',
                    title: 'Berhasil Booking!',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.message,
                    confirmButtonColor: '#FF6B2C'
                });
            }
        }).catch(err => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    });

    window.cancelBooking = function(scheduleId) {
        Swal.fire({
            title: 'Batalkan Kelas?',
            text: "Anda yakin ingin membatalkan pendaftaran kelas ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Batalkan!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ route('dashboard.schedule.cancel') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ schedule_id: scheduleId })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        Swal.fire({
                            toast: true,
                            position: 'bottom-end',
                            icon: 'success',
                            title: 'Berhasil Dibatalkan!',
                            showConfirmButton: false,
                            timer: 2000
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Gagal', data.message, 'error');
                    }
                });
            }
        });
    }

    if (window.Echo) {
        window.Echo.channel('gym-classes')
            .listen('ClassBooked', (e) => {
                const capacityEl = document.getElementById('capacity-' + e.scheduleId);
                if (capacityEl) {
                    capacityEl.innerText = e.currentBookings;
                    // Highlight the update
                    let parent = capacityEl.closest('.schedule-class');
                    if(parent) {
                        parent.style.transition = 'all 0.3s ease';
                        parent.style.transform = 'scale(1.05)';
                        parent.style.borderColor = '#FF6B2C';
                        setTimeout(() => { 
                            parent.style.transform = 'scale(1)'; 
                            parent.style.borderColor = '';
                        }, 600);
                    }
                }
            });
    }

    // ==========================================
    // INTERACTIVE ONBOARDING TOUR (SCHEDULE)
    // ==========================================
    if (typeof initPageTour === 'function') {
        initPageTour([
            {
                target: '#tour-step-1',
                title: 'Filter Jadwal Pintar 🔍',
                text: 'Cari kelas berdasarkan Kategori (Yoga, Cardio, dll) atau Instruktur favoritmu di sini.',
                position: 'bottom'
            },
            {
                target: '#tour-step-2',
                title: 'Kalender Interaktif 🗓️',
                text: 'Klik pada salah satu blok kelas yang tersedia untuk melihat detail dan melakukan Booking (pendaftaran). Sisa kuota akan selalu update secara real-time!',
                position: 'bottom'
            },
            {
                target: '#tour-step-3',
                title: 'Kelas Terdaftarmu ✅',
                text: 'Semua kelas yang telah kamu booking akan muncul di sini. Jika berhalangan hadir, klik Batalkan agar kuota bisa diberikan ke member lain.',
                position: 'right'
            }
        ], 'fitnessValsTourCompleted_schedule');
    }
</script>
@endsection
