@extends('layouts.admin')
@section('title', 'Daftar Member')
@section('page_title', 'Daftar Member')
@section('page_badge')<p style="margin-top:2px;">Total: {{ $members->total() }} member terdaftar</p>@endsection

@section('content')
<div class="card" style="margin-bottom:var(--space-6); padding:var(--space-4); border:none; box-shadow:var(--shadow-sm);">
    <form action="{{ route('admin.members') }}" method="GET" class="filter-bar" style="margin-bottom:0;">
        <div class="search-input" style="background:var(--color-bg); border:none;"><i class="bi bi-search" style="color:var(--color-text-muted);"></i> <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."></div>
        <select name="date" class="filter-select" style="background:var(--color-bg); border:none;"><option {{ request('date') == 'Any Date' ? 'selected' : '' }}>📅 Kapan Saja</option><option {{ request('date') == 'Today' ? 'selected' : '' }}>Hari Ini</option><option {{ request('date') == 'This Week' ? 'selected' : '' }}>Minggu Ini</option><option {{ request('date') == 'This Month' ? 'selected' : '' }}>Bulan Ini</option></select>
        <select name="plan" class="filter-select" style="background:var(--color-bg); border:none;"><option value="">💎 Semua Plan</option><option value="basic" {{ request('plan') == 'basic' ? 'selected' : '' }}>Basic</option><option value="pro" {{ request('plan') == 'pro' ? 'selected' : '' }}>Pro</option><option value="elite" {{ request('plan') == 'elite' ? 'selected' : '' }}>Elite</option></select>
        <select name="status" class="filter-select" style="background:var(--color-bg); border:none;"><option value="">🟢 Semua Status</option><option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option><option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option><option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option></select>
        <input type="hidden" name="tab" value="{{ request('tab', 'semua') }}">
        <button type="submit" class="btn btn-primary btn-sm" style="border-radius:var(--radius-lg);">Terapkan</button>
    </form>
</div>

<div style="margin-bottom:var(--space-6);">
    <div class="flex-gap" style="background:var(--color-bg); padding:4px; border-radius:var(--radius-lg); display:inline-flex;">
        <a href="{{ route('admin.members', array_merge(request()->query(), ['tab'=>'semua'])) }}" class="btn btn-sm {{ $currentTab == 'semua' ? 'btn-primary' : 'btn-ghost' }}" style="border:none;">Semua</a>
        <a href="{{ route('admin.members', array_merge(request()->query(), ['tab'=>'active'])) }}" class="btn btn-sm {{ $currentTab == 'active' ? 'btn-primary' : 'btn-ghost' }}" style="border:none;">Aktif</a>
        <a href="{{ route('admin.members', array_merge(request()->query(), ['tab'=>'expired'])) }}" class="btn btn-sm {{ $currentTab == 'expired' ? 'btn-primary' : 'btn-ghost' }}" style="border:none;">Expired</a>
        <a href="{{ route('admin.members', array_merge(request()->query(), ['tab'=>'suspended'])) }}" class="btn btn-sm {{ $currentTab == 'suspended' ? 'btn-primary' : 'btn-ghost' }}" style="border:none;">Suspended</a>
    </div>
</div>

{{-- MEMBER TABLE --}}
    <div class="card" style="padding:0; overflow:hidden; border:none; box-shadow:var(--shadow-card);">
        <div style="padding:var(--space-4) var(--space-5);border-bottom:1px solid var(--color-border);display:flex;justify-content:space-between;align-items:center;background:#fff;">
            <div class="flex-gap"><a href="{{ route('admin.members.export') }}" class="btn btn-ghost btn-sm" style="color:var(--color-primary); background:rgba(255,107,44,0.1);">📥 Export CSV</a></div>
        </div>
        
        <form id="bulkForm" action="{{ route('admin.members.bulk_destroy') }}" method="POST">
            @csrf
            @method('DELETE')
            
            <div id="bulkActionBar" style="display:none; position:fixed; bottom:30px; left:50%; transform:translateX(-50%); z-index:99; background:#1f2937; padding:12px 24px; border-radius:100px; box-shadow:0 10px 25px rgba(0,0,0,0.2); align-items:center; gap:20px;">
                <span style="font-size:var(--fs-sm); font-weight:var(--fw-medium); color:#fff;"><span id="bulkCount" style="background:var(--color-primary); padding:2px 8px; border-radius:12px; margin-right:8px; font-weight:bold;">0</span> member terpilih</span>
                <button type="submit" style="background:rgba(255,255,255,0.1); color:#fca5a5; border:1px solid rgba(255,255,255,0.2); padding:6px 16px; border-radius:100px; cursor:pointer; font-size:13px; font-weight:600; transition:all 0.2s;" onclick="return confirm('Yakin ingin menghapus member yang dipilih secara massal?')" onmouseover="this.style.background='#fca5a5';this.style.color='#1f2937';" onmouseout="this.style.background='rgba(255,255,255,0.1)';this.style.color='#fca5a5';">⚠️ Hapus Massal</button>
            </div>
        <table class="table admin-table" style="margin:0;">
            <thead><tr style="background:var(--color-bg);"><th style="width:40px; padding-left:var(--space-5);"><input type="checkbox" id="selectAll" style="accent-color:var(--color-primary);"></th><th>Member Profil</th><th>Tgl Bergabung</th><th>Paket Langganan</th><th>Status</th><th style="text-align:right; padding-right:var(--space-5);">Aksi</th></tr></thead>
            <tbody>
                @forelse($members as $m)
                <tr style="transition:all 0.2s;">
                    <td style="padding-left:var(--space-5);"><input type="checkbox" name="ids[]" value="{{ $m['id'] }}" class="member-checkbox" style="accent-color:var(--color-primary);"></td>
                    <td>
                        <div class="flex-gap">
                            <div class="avatar" style="box-shadow:var(--shadow-sm); border:2px solid #fff;"><img src="https://ui-avatars.com/api/?name={{ urlencode($m['name']) }}&background=FF6B2C&color=fff&size=40" alt=""></div>
                            <div><strong style="font-size:14px; color:var(--color-text);">{{ $m['name'] }}</strong><br><span style="font-size:12px;color:var(--color-text-muted);">{{ $m['email'] }}</span></div>
                        </div>
                    </td>
                    <td><span style="font-size:13px; font-weight:500;">{{ \Carbon\Carbon::parse($m['created_at'])->format('d M Y') }}</span><br><small class="text-muted" style="font-size:11px;">Pkl {{ \Carbon\Carbon::parse($m['created_at'])->format('H:i') }}</small></td>
                    <td>
                        <span class="badge {{ ($m['plan'] ?? '') == 'elite' ? 'badge-dark' : (($m['plan'] ?? '') == 'pro' ? 'badge-accent' : 'badge-outline') }}" style="margin-bottom:4px; box-shadow:0 2px 4px rgba(0,0,0,0.05);">
                            {{ ucfirst($m['plan'] ?? 'Basic') }}
                        </span>
                        <br>
                        @if(($m['billing_cycle'] ?? '') == 'yearly') <span style="font-size:11px; color:#10b981; font-weight:600;">🗓️ Tahunan</span>
                        @elseif(($m['billing_cycle'] ?? '') == 'monthly') <span style="font-size:11px; color:var(--color-text-muted); font-weight:500;">📅 Bulanan</span>
                        @endif
                    </td>
                    <td>
                        @if($m['status'] == 'active') <span class="status-badge approved">🟢 Aktif</span>
                        @elseif($m['status'] == 'expired') <span class="status-badge rejected">🔴 Expired</span>
                        @elseif($m['status'] == 'suspended') <span class="status-badge pending">🟠 Suspended</span>
                        @else <span class="status-badge">{{ ucfirst($m['status']) }}</span>
                        @endif
                        @if(!empty($m['expires_at']))
                        <br><small class="text-muted" style="font-size:11px;">s/d {{ \Carbon\Carbon::parse($m['expires_at'])->format('d M Y') }}</small>
                        @endif
                    </td>
                    <td style="text-align:right; padding-right:var(--space-5);">
                        <div class="flex-gap" style="justify-content:flex-end;">
                            <a href="{{ route('admin.member.detail', $m['id']) }}" class="btn btn-ghost" style="padding:6px 10px; font-size:16px; border-radius:var(--radius-md);" title="Lihat Profil">📄</a>
                            <form action="{{ route('admin.members.destroy', $m['id']) }}" method="POST" style="display:inline-block;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-ghost" style="padding:6px 10px; font-size:16px; border-radius:var(--radius-md);" title="Hapus" onclick="return confirm('Yakin ingin menghapus member ini?')">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:var(--space-6);">Belum ada member yang terdaftar.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </form>
    <div style="padding:var(--space-4) var(--space-5);border-top:1px solid var(--color-border);display:flex;justify-content:space-between;align-items:center;">
        <span class="text-muted" style="font-size:var(--fs-sm);">Showing {{ $members->firstItem() ?? 0 }} to {{ $members->lastItem() ?? 0 }} of {{ $members->total() }} members</span>
        <div class="pagination">
            {{ $members->links('pagination::simple-default') }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.member-checkbox');
    const bulkActionBar = document.getElementById('bulkActionBar');
    const bulkCount = document.getElementById('bulkCount');

    function updateBulkAction() {
        const checkedCount = document.querySelectorAll('.member-checkbox:checked').length;
        if(checkedCount > 0) {
            bulkActionBar.style.display = 'flex';
            bulkCount.textContent = checkedCount;
        } else {
            bulkActionBar.style.display = 'none';
        }
        
        if(checkedCount === checkboxes.length && checkboxes.length > 0) {
            selectAll.checked = true;
        } else {
            selectAll.checked = false;
        }
    }

    if(selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => {
                cb.checked = this.checked;
            });
            updateBulkAction();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateBulkAction);
    });
</script>
@endsection
