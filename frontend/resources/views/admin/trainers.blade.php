@extends('layouts.admin')
@section('title', 'Kelola Trainer')
@section('page_title', 'Kelola Trainer')

@section('content')
<div class="card" style="margin-bottom:var(--space-6); padding:var(--space-4); border:none; box-shadow:var(--shadow-sm);">
    <div class="flex-between" style="margin-bottom:var(--space-4);">
        <p class="text-muted" style="margin:0; font-weight:500;">Total: <strong style="color:var(--color-text);">{{ $trainers->total() }}</strong> trainer aktif</p>
        <button class="btn btn-primary btn-sm" onclick="openTrainerModal()" style="border-radius:var(--radius-lg);">+ Tambah Trainer</button>
    </div>
    
    <form action="{{ route('admin.trainers') }}" method="GET" class="filter-bar" style="margin-bottom:0; background:var(--color-bg); padding:var(--space-3); border-radius:var(--radius-lg);">
        <div class="search-input" style="background:#fff; border:1px solid var(--color-border);"><i class="bi bi-search" style="color:var(--color-text-muted);"></i> <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama trainer..."></div>
        <select name="specialty" class="filter-select" style="background:#fff; border:1px solid var(--color-border);">
            <option value="">🌟 Semua Spesialisasi</option>
            @foreach(['Strength & Conditioning', 'HIIT & Fat Loss', 'Yoga & Mobility', 'Athletic Performance', 'Bodybuilding'] as $s)
            <option value="{{ $s }}" {{ request('specialty') == $s ? 'selected' : '' }}>{{ $s }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary btn-sm" style="border-radius:var(--radius-md);">Terapkan</button>
    </form>
</div>

<div class="card" style="padding:0; overflow:hidden; border:none; box-shadow:var(--shadow-card);">
    <table class="table admin-table" style="margin:0;">
        <thead><tr style="background:var(--color-bg);"><th>Profil Trainer</th><th>Spesialisasi</th><th>Harga/Sesi</th><th>Rating</th><th>Ketersediaan</th><th style="text-align:right; padding-right:var(--space-5);">Aksi</th></tr></thead>
        <tbody>
            @forelse($trainers as $t)
            @php
                $specialty = $t['specialty'] ?? 'General';
                $color = 'var(--color-primary)';
                if(str_contains(strtolower($specialty), 'yoga')) $color = '#8b5cf6';
                if(str_contains(strtolower($specialty), 'hiit') || str_contains(strtolower($specialty), 'fat')) $color = '#ef4444';
                if(str_contains(strtolower($specialty), 'athletic')) $color = '#10b981';
            @endphp
            <tr style="transition:all 0.2s;">
                <td>
                    <div class="flex-gap">
                        <div class="avatar" style="box-shadow:var(--shadow-sm); border:2px solid #fff;"><img src="{{ $t['photo_url'] ?? 'https://ui-avatars.com/api/?name='.urlencode($t['name'] ?? '').'&background=FF6B2C&color=fff&size=40' }}" alt=""></div>
                        <div>
                            <strong style="font-size:14px; color:var(--color-text);">{{ $t['name'] ?? '' }}</strong><br>
                            <span style="font-size:12px;color:var(--color-text-muted);">{{ \Illuminate\Support\Str::limit($t['bio'] ?? '', 40) }}</span>
                        </div>
                    </div>
                </td>
                <td><span class="badge" style="background:{{ $color }}15; color:{{ $color }}; border:1px solid {{ $color }}30;">{{ $specialty }}</span></td>
                <td style="font-size:13px;font-weight:600;color:var(--color-text);">Rp {{ number_format($t['price_per_session'] ?? 0, 0, ',', '.') }}</td>
                <td><span style="font-size:13px; font-weight:600;"><span style="color:#f59e0b;">⭐</span> {{ number_format($t['rating'] ?? 5.0, 1) }}</span></td>
                <td><span style="font-size:12px; color:var(--color-text-secondary); background:var(--color-bg); padding:4px 8px; border-radius:100px;">📅 {{ $t['availability'] ?? 'Setiap Hari' }}</span></td>
                <td style="text-align:right; padding-right:var(--space-5);">
                    <div class="flex-gap" style="justify-content:flex-end;">
                        <button onclick="editTrainer({{ $t['id'] ?? 0 }}, '{{ addslashes($t['name'] ?? '') }}', '{{ $t['price_per_session'] ?? 0 }}', '{{ addslashes($t['bio'] ?? '') }}', '{{ addslashes($specialty) }}', '{{ $t['whatsapp'] ?? '' }}', '{{ addslashes($t['availability'] ?? '') }}', '{{ $t['rating'] ?? 5.0 }}')" class="btn btn-ghost" style="padding:6px 10px; font-size:16px; border-radius:var(--radius-md);" title="Edit Trainer">✏️</button>
                        <form action="{{ route('admin.trainers.destroy', $t['id'] ?? 0) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-ghost" style="padding:6px 10px; font-size:16px; border-radius:var(--radius-md);" title="Hapus" onclick="return confirm('Yakin hapus trainer ini?')">🗑️</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:var(--space-6);">Belum ada trainer terdaftar.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($trainers->hasPages())
    <div style="padding:var(--space-4) var(--space-5);border-top:1px solid var(--color-border);">
        {{ $trainers->links('pagination::simple-default') }}
    </div>
    @endif
</div>

<div class="modal-overlay" id="trainerModal" style="display:none;">
    <div class="modal">
        <form id="trainerForm" action="{{ route('admin.trainers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="modal-header">
                <h3 id="modalTitle">Tambah Trainer Baru</h3>
                <button type="button" onclick="closeTrainerModal()" style="font-size:20px; background:none; border:none; cursor:pointer;"><i class="bi bi-x-lg"></i></button>
            </div>
            
            <div class="form-row">
                <div class="form-group"><label class="form-label">Nama Lengkap</label><input type="text" id="inpName" name="name" class="form-input" required></div>
                <div class="form-group"><label class="form-label">Harga per Sesi (Rp)</label><input type="number" id="inpPrice" name="price_per_session" class="form-input" placeholder="350000" required></div>
            </div>
            <div class="form-group"><label class="form-label">Bio Singkat</label><textarea id="inpBio" name="bio" class="form-input" rows="2" placeholder="Pengalaman dan latar belakang..."></textarea></div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Spesialisasi</label>
                    <select id="inpSpec" name="specialty" class="form-input form-select">
                        <option value="Strength & Conditioning">Strength & Conditioning</option>
                        <option value="HIIT & Fat Loss">HIIT & Fat Loss</option>
                        <option value="Yoga & Mobility">Yoga & Mobility</option>
                        <option value="Athletic Performance">Athletic Performance</option>
                        <option value="Bodybuilding">Bodybuilding</option>
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Rating (1-5)</label><input type="number" id="inpRating" step="0.1" name="rating" class="form-input" value="5.0" min="1" max="5"></div>
            </div>

            <div class="form-row">
                <div class="form-group"><label class="form-label">Jadwal / Ketersediaan</label><input type="text" id="inpAvail" name="availability" class="form-input" placeholder="Senin, Rabu, Jumat"></div>
                <div class="form-group"><label class="form-label">No. WhatsApp</label><input type="text" id="inpWa" name="whatsapp" class="form-input" placeholder="628..."></div>
            </div>
            
            <button type="submit" id="btnSubmit" class="btn btn-primary btn-block">Simpan Trainer</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const modal = document.getElementById('trainerModal');
    const form = document.getElementById('trainerForm');
    const modalTitle = document.getElementById('modalTitle');
    const methodInp = document.getElementById('formMethod');
    const btnSubmit = document.getElementById('btnSubmit');

    function openTrainerModal() {
        modalTitle.textContent = "Tambah Trainer Baru";
        form.action = "{{ route('admin.trainers.store') }}";
        methodInp.value = "POST";
        btnSubmit.textContent = "Tambahkan Trainer";
        
        document.getElementById('inpName').value = "";
        document.getElementById('inpPrice').value = "";
        document.getElementById('inpBio').value = "";
        document.getElementById('inpSpec').value = "Strength & Conditioning";
        document.getElementById('inpWa').value = "";
        document.getElementById('inpAvail').value = "";
        document.getElementById('inpRating').value = "5.0";
        
        modal.style.display = 'flex';
    }

    function editTrainer(id, name, price, bio, spec, wa, avail, rating) {
        modalTitle.textContent = "Edit Trainer";
        form.action = "/admin/trainers/" + id;
        methodInp.value = "PUT";
        btnSubmit.textContent = "Simpan Perubahan";
        
        document.getElementById('inpName').value = name;
        document.getElementById('inpPrice').value = price;
        document.getElementById('inpBio').value = bio;
        document.getElementById('inpSpec').value = spec || "Strength & Conditioning";
        document.getElementById('inpWa').value = wa;
        document.getElementById('inpAvail').value = avail;
        document.getElementById('inpRating').value = rating || "5.0";
        
        modal.style.display = 'flex';
    }

    function closeTrainerModal() {
        modal.style.display = 'none';
    }
</script>
@endsection
