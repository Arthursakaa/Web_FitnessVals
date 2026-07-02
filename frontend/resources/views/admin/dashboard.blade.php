@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard')

@section('content')
{{-- STATS --}}
<div class="grid-4" style="margin-bottom:var(--space-6);">
    <div class="card" style="border-top:3px solid var(--color-primary);">
        <p class="text-muted" style="font-size:var(--fs-sm);margin-bottom:var(--space-2);">Total Pendapatan</p>
        <h3 style="font-size:var(--fs-2xl);">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</h3>
        <p style="font-size:var(--fs-xs);color:var(--color-success);margin-top:var(--space-2);"><i class="bi bi-arrow-up-right"></i> +12.5% dari bulan lalu</p>
    </div>
    <div class="card" style="border-top:3px solid var(--color-accent);">
        <p class="text-muted" style="font-size:var(--fs-sm);margin-bottom:var(--space-2);">Total Member Aktif</p>
        <h3 style="font-size:var(--fs-2xl);">{{ $memberCount }}</h3>
        <p style="font-size:var(--fs-xs);color:var(--color-success);margin-top:var(--space-2);"><i class="bi bi-arrow-up-right"></i> +45 pendaftaran baru</p>
    </div>
    <div class="card" style="border-top:3px solid #22D3EE;">
        <p class="text-muted" style="font-size:var(--fs-sm);margin-bottom:var(--space-2);">Kelas Aktif</p>
        <h3 style="font-size:var(--fs-2xl);">{{ $classCount }}</h3>
        <p style="font-size:var(--fs-xs);color:var(--color-text-muted);margin-top:var(--space-2);">Rata-rata kehadiran 85%</p>
    </div>
    <div class="card" style="border-top:3px solid #F59E0B;">
        <p class="text-muted" style="font-size:var(--fs-sm);margin-bottom:var(--space-2);">Total Booking Kelas</p>
        <h3 style="font-size:var(--fs-2xl);">{{ $bookingCount }}</h3>
        <p style="font-size:var(--fs-xs);color:var(--color-danger);margin-top:var(--space-2);"><i class="bi bi-arrow-down-right"></i> -2% (Kapasitas hampir penuh)</p>
    </div>
</div>

<div class="grid-2" style="gap:var(--space-6);margin-bottom:var(--space-6);">
    <div class="chart-card"><h4>Pertumbuhan Member</h4><div class="chart-container"><canvas id="memberGrowth"></canvas></div></div>
    <div class="chart-card"><h4>Distribusi Paket</h4><div class="chart-container"><canvas id="packageDist"></canvas></div></div>
</div>

{{-- PENDING APPROVALS --}}
<div class="card" style="margin-bottom:var(--space-6);">
    <div class="flex-between" style="margin-bottom:var(--space-4);">
        <h4>Pendaftar Baru</h4>
        <a href="{{ route('admin.members') }}" class="btn btn-ghost btn-sm">Lihat Semua</a>
    </div>
    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Member</th><th>Paket</th><th>Siklus Tagihan</th><th>Tanggal Daftar</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($recentMembers as $m)
                <tr>
                    <td><div class="flex-gap"><div class="avatar"><img src="https://ui-avatars.com/api/?name={{ urlencode($m['name'] ?? '') }}&background=FF6B2C&color=fff&size=40" alt=""></div><div><strong style="font-size:var(--fs-sm);">{{ $m['name'] ?? '' }}</strong><br><span style="font-size:var(--fs-xs);color:var(--color-text-muted);">{{ $m['email'] ?? '' }}</span></div></div></td>
                    <td>
                        <span class="badge {{ ($m['plan'] ?? '') == 'elite' ? 'badge-dark' : (($m['plan'] ?? '') == 'pro' ? 'badge-accent' : 'badge-outline') }}">
                            {{ ucfirst($m['plan'] ?? 'Basic') }}
                        </span>
                    </td>
                    <td>
                        @if(($m['billing_cycle'] ?? '') == 'yearly') <span class="badge badge-success">Tahunan</span>
                        @elseif(($m['billing_cycle'] ?? '') == 'monthly') <span class="badge badge-outline">Bulanan</span>
                        @else -
                        @endif
                    </td>
                    <td style="font-size:var(--fs-sm);">{{ \Carbon\Carbon::parse($m['created_at'] ?? now())->format('d M Y') }}</td>
                    <td>
                        <div class="flex-gap">
                            <a href="{{ route('admin.member.detail', $m['id'] ?? 0) }}" class="btn btn-ghost btn-sm">Review</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- EXPIRING ALERT --}}
<div class="card" style="border-left:4px solid var(--color-warning);">
    <h4 style="margin-bottom:var(--space-3);"><i class="bi bi-exclamation-triangle"></i> Membership Hampir Expired</h4>
    <p class="text-muted" style="font-size:var(--fs-sm);margin-bottom:var(--space-3);">5 member dengan membership yang akan expired dalam 7 hari ke depan.</p>
    <a href="{{ route('admin.members') }}" class="btn btn-outline btn-sm">Lihat Detail</a>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    new Chart(document.getElementById('memberGrowth'), { type:'bar', data:{labels:['Jan','Feb','Mar','Apr','Mei'],datasets:[{data:[180,210,245,290,340],backgroundColor:'#FF6B2C',borderRadius:6}]}, options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{grid:{color:'#F0F0F2'}},x:{grid:{display:false}}}} });
    new Chart(document.getElementById('packageDist'), { type:'doughnut', data:{labels:['Basic','Pro','Elite'],datasets:[{data:[{{ $basicCount }},{{ $proCount }},{{ $eliteCount }}],backgroundColor:['#E5E5E7','#FF6B2C','#1C1C1E'],borderWidth:0}]}, options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom'}},cutout:'65%'} });
});
</script>
@endsection
