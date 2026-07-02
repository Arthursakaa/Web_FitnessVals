@extends('layouts.admin')
@section('title', 'Kelola Kelas')
@section('page_title', 'Kelola Kelas & Program')

@section('content')
<div class="card" style="margin-bottom:var(--space-6);border-left:4px solid var(--color-accent-2); box-shadow:var(--shadow-sm);">
    <div class="flex-between">
        <div><h5 style="margin-bottom:4px;">Fitur Kelas & Program</h5><p class="text-muted" style="font-size:var(--fs-xs);">Toggle untuk mengaktifkan/menonaktifkan fitur kelas di website</p></div>
        <label class="toggle"><input type="checkbox" checked><span class="toggle-slider"></span></label>
    </div>
</div>

<div class="card" style="margin-bottom:var(--space-6); padding:var(--space-4); border:none; box-shadow:var(--shadow-sm);">
    <form action="{{ route('admin.classes') }}" method="GET" class="filter-bar" style="margin-bottom:0;">
        <div class="search-input" style="background:var(--color-bg); border:none;">
            <i class="bi bi-search" style="color:var(--color-text-muted);"></i> 
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kelas...">
        </div>
        <select name="type" class="filter-select" style="background:var(--color-bg); border:none;">
            <option value="">🧩 Semua Kategori</option>
            <option value="Strength" {{ request('type') == 'Strength' ? 'selected' : '' }}>Strength</option>
            <option value="Cardio" {{ request('type') == 'Cardio' ? 'selected' : '' }}>Cardio</option>
            <option value="HIIT" {{ request('type') == 'HIIT' ? 'selected' : '' }}>HIIT</option>
            <option value="Yoga" {{ request('type') == 'Yoga' ? 'selected' : '' }}>Yoga</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm" style="border-radius:var(--radius-lg);">Terapkan</button>
    </form>
</div>

<div class="flex-between" style="margin-bottom:var(--space-6);">
    <div class="flex-gap">
        <button class="btn btn-primary btn-sm" onclick="openClassModal()" style="border-radius:var(--radius-lg);">+ Tambah Kelas</button>
        <button class="btn btn-ghost btn-sm" style="border-radius:var(--radius-lg);">Kelola Kategori</button>
    </div>
</div>

<div class="card" style="padding:0; overflow:hidden; border:none; box-shadow:var(--shadow-card);">
    <table class="table admin-table" style="margin:0;">
        <thead><tr style="background:var(--color-bg);"><th>Nama Kelas</th><th>Kategori</th><th>Instruktur</th><th>Kapasitas</th><th>Status</th><th style="text-align:right; padding-right:var(--space-5);">Aksi</th></tr></thead>
        <tbody>
            @forelse($classes as $c)
            @php
                $type = $c['type'] ?? 'General';
                $color = 'var(--color-primary)';
                if($type == 'Yoga') $color = '#8b5cf6';
                if($type == 'HIIT') $color = '#ef4444';
                if($type == 'Cardio') $color = '#10b981';
            @endphp
            <tr style="transition:all 0.2s;">
                <td>
                    <div class="flex-gap">
                        <div style="width:40px; height:40px; border-radius:10px; background:{{ $color }}20; color:{{ $color }}; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:18px;">
                            {{ substr($c['name'] ?? '', 0, 1) }}
                        </div>
                        <div>
                            <strong style="font-size:14px; color:var(--color-text);">{{ $c['name'] ?? '' }}</strong><br>
                            <span style="font-size:12px;color:var(--color-text-muted);">{{ \Illuminate\Support\Str::limit($c['description'] ?? '', 35) }}</span>
                        </div>
                    </div>
                </td>
                <td><span class="badge" style="background:{{ $color }}15; color:{{ $color }}; border:1px solid {{ $color }}30;">{{ $type }}</span></td>
                <td><span style="font-size:12px; font-weight:500; color:var(--color-text-secondary);">👥 Tergantung Jadwal</span><br><small style="font-size:11px;color:var(--color-text-muted);">{{ count($c['schedules'] ?? []) }} Sesi Aktif</small></td>
                <td><span style="font-size:13px; font-weight:600;">Maks {{ $c['max_capacity'] ?? 0 }}</span><br><small style="font-size:11px;color:var(--color-text-muted);">⏱️ {{ $c['duration_minutes'] ?? 0 }} mnt</small></td>
                <td>
                    <label class="toggle" style="transform:scale(0.8); margin:0;">
                        <input type="checkbox" checked onchange="alert('Status kelas disimpan!')">
                        <span class="toggle-slider"></span>
                    </label>
                </td>
                <td style="text-align:right; padding-right:var(--space-5);">
                    <div class="flex-gap" style="justify-content:flex-end;">
                        <button onclick="editClass({{ $c['id'] ?? 0 }}, '{{ addslashes($c['name'] ?? '') }}', '{{ addslashes($c['description'] ?? '') }}', '{{ $type }}', {{ $c['max_capacity'] ?? 0 }}, {{ $c['duration_minutes'] ?? 0 }})" class="btn btn-ghost" style="padding:6px 10px; font-size:16px; border-radius:var(--radius-md);" title="Edit Kelas">✏️</button>
                        <form action="{{ route('admin.classes.destroy', $c['id'] ?? 0) }}" method="POST" style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-ghost" style="padding:6px 10px; font-size:16px; border-radius:var(--radius-md);" title="Hapus" onclick="return confirm('Yakin ingin menghapus kelas ini beserta seluruh jadwalnya?')">🗑️</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:var(--space-6);">Belum ada kelas yang dibuat.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="modal-overlay" id="classModal" style="display:none;">
    <div class="modal">
        <form id="classForm" action="{{ route('admin.classes.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="modal-header">
                <h3 id="modalTitle">Tambah Kelas Baru</h3>
                <button type="button" onclick="closeClassModal()" style="font-size:20px; background:none; border:none; cursor:pointer;"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="form-group"><label class="form-label">Nama Kelas</label><input type="text" id="inpName" name="name" class="form-input" placeholder="Nama kelas..." required></div>
            <div class="form-group"><label class="form-label">Deskripsi Singkat</label><textarea id="inpDesc" name="description" class="form-input" rows="2" placeholder="Deskripsi latihan..."></textarea></div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select id="inpType" name="type" class="form-input form-select">
                        <option value="Strength">Strength</option>
                        <option value="Cardio">Cardio</option>
                        <option value="HIIT">HIIT</option>
                        <option value="Yoga">Yoga</option>
                        <option value="Mind & Body">Mind & Body</option>
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Maksimal Kapasitas</label><input type="number" id="inpCap" name="max_capacity" class="form-input" value="20" required></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Durasi (menit)</label><input type="number" id="inpDur" name="duration_minutes" class="form-input" value="45"></div>
            </div>
            <button type="submit" id="btnSubmit" class="btn btn-primary btn-block">Simpan Kelas</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const modal = document.getElementById('classModal');
    const form = document.getElementById('classForm');
    const modalTitle = document.getElementById('modalTitle');
    const methodInp = document.getElementById('formMethod');
    const btnSubmit = document.getElementById('btnSubmit');

    function openClassModal() {
        modalTitle.textContent = "Tambah Kelas Baru";
        form.action = "{{ route('admin.classes.store') }}";
        methodInp.value = "POST";
        btnSubmit.textContent = "Tambahkan Kelas";
        
        document.getElementById('inpName').value = "";
        document.getElementById('inpDesc').value = "";
        document.getElementById('inpType').value = "Strength";
        document.getElementById('inpCap').value = 20;
        document.getElementById('inpDur').value = 45;
        
        modal.style.display = 'flex';
    }

    function editClass(id, name, desc, type, cap, dur) {
        modalTitle.textContent = "Edit Kelas";
        form.action = "/admin/classes/" + id;
        methodInp.value = "PUT";
        btnSubmit.textContent = "Simpan Perubahan";
        
        document.getElementById('inpName').value = name;
        document.getElementById('inpDesc').value = desc;
        document.getElementById('inpType').value = type || "Strength";
        document.getElementById('inpCap').value = cap;
        document.getElementById('inpDur').value = dur;
        
        modal.style.display = 'flex';
    }

    function closeClassModal() {
        modal.style.display = 'none';
    }
</script>
@endsection
