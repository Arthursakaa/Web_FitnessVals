@extends('layouts.admin')
@section('title', 'Laporan & Statistik')
@section('page_title', 'Laporan & Statistik')

@section('content')
<div class="flex-between" style="margin-bottom:var(--space-6);">
    <div class="flex-gap" style="background: var(--color-bg); padding: 4px; border-radius: var(--radius-lg); display: inline-flex;">
        <button class="btn btn-primary btn-sm tab-btn active" data-tab="tabMember" style="border:none;">Member</button>
        <button class="btn btn-ghost btn-sm tab-btn" data-tab="tabKelas" style="border:none;">Kelas</button>
        <button class="btn btn-ghost btn-sm tab-btn" data-tab="tabPendapatan" style="border:none;">Pendapatan</button>
    </div>
    <div class="flex-gap">
        <select class="form-input form-select" style="width:auto;padding:8px 36px 8px 12px;font-size:var(--fs-sm);" onchange="window.location.href='?period='+this.value">
            <option value="6m" {{ $period == '6m' ? 'selected' : '' }}>6 Bulan Terakhir</option>
            <option value="1y" {{ $period == '1y' ? 'selected' : '' }}>1 Tahun</option>
        </select>
        <button class="btn btn-outline btn-sm"><i class="bi bi-file-earmark-text"></i> Export PDF</button>
        <button class="btn btn-outline btn-sm"><i class="bi bi-bar-chart-line"></i> Export CSV</button>
    </div>
</div>

{{-- TAB: MEMBER --}}
<div class="tab-content" id="tabMember">
    <div class="admin-stats" style="margin-bottom:var(--space-6);">
        <div class="admin-stat-card">
            <div class="admin-stat-icon primary"><i class="bi bi-people-fill"></i></div>
            <div class="admin-stat-content">
                <h4>Total Member</h4>
                <div class="stat-value">{{ number_format($totalMembers) }}</div>
                <div class="stat-trend up"><i class="bi bi-check-circle-fill"></i> {{ $activeMembers }} Aktif</div>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-icon success"><i class="bi bi-arrow-repeat"></i></div>
            <div class="admin-stat-content">
                <h4>Avg. Retention</h4>
                <div class="stat-value">{{ $avgRetention }}</div>
                <div class="stat-trend up"><i class="bi bi-graph-up-arrow"></i> Nyata</div>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-icon warning"><i class="bi bi-person-x-fill"></i></div>
            <div class="admin-stat-content">
                <h4>Churn Rate</h4>
                <div class="stat-value">{{ $churnRate }}</div>
                <div class="stat-trend down"><i class="bi bi-graph-down-arrow"></i> Bulan ini</div>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-icon info"><i class="bi bi-wallet2"></i></div>
            <div class="admin-stat-content">
                <h4>Revenue/Member</h4>
                <div class="stat-value" style="font-size:24px;">{{ $revenuePerMember }}</div>
                <div class="stat-trend up"><i class="bi bi-cash"></i> Rata-rata</div>
            </div>
        </div>
    </div>

    <div class="chart-card" style="margin-bottom:var(--space-6);">
        <div class="flex-between" style="margin-bottom:var(--space-4);">
            <div>
                <h4 style="margin:0;">Pertumbuhan Member</h4>
                <p class="text-muted" style="font-size:var(--fs-xs);">Tren pendaftaran member baru selama {{ $period == '1y' ? '1 tahun' : '6 bulan' }} terakhir.</p>
            </div>
        </div>
        <div class="chart-container" style="height:300px;"><canvas id="growthChart"></canvas></div>
    </div>

    <div class="grid-2" style="gap:var(--space-6);margin-bottom:var(--space-6);">
        <div class="chart-card">
            <h4 style="margin-bottom:2px;">Distribusi Usia Member</h4>
            <p class="text-muted" style="font-size:var(--fs-xs);margin-bottom:var(--space-4);">Demografi rentang usia member terdaftar.</p>
            <div class="chart-container"><canvas id="ageChart"></canvas></div>
        </div>
        <div class="chart-card">
            <h4 style="margin-bottom:2px;">Paket Membership</h4>
            <p class="text-muted" style="font-size:var(--fs-xs);margin-bottom:var(--space-4);">Persentase pengguna berdasarkan pilihan paket.</p>
            <div class="chart-container"><canvas id="pkgChart"></canvas></div>
        </div>
    </div>
</div>

{{-- TAB: KELAS --}}
<div class="tab-content" id="tabKelas" style="display:none;">
    <div class="grid-2" style="gap:var(--space-6);margin-bottom:var(--space-6);">
        <div class="admin-stat-card">
            <div class="admin-stat-icon primary"><i class="bi bi-calendar-check"></i></div>
            <div class="admin-stat-content">
                <h4>Total Kelas Tersedia</h4>
                <div class="stat-value">{{ count($classStats) }}</div>
                <div class="stat-trend up"><i class="bi bi-activity"></i> Program Aktif</div>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-icon success"><i class="bi bi-graph-up"></i></div>
            <div class="admin-stat-content">
                <h4>Rata-rata Kehadiran</h4>
                <div class="stat-value">{{ count($classStats) > 0 ? round(collect($classStats)->avg('attendance_val')) : 0 }}%</div>
                <div class="stat-trend up"><i class="bi bi-check-circle-fill"></i> Sangat Baik</div>
            </div>
        </div>
    </div>

    <div class="chart-card" style="margin-bottom:var(--space-6);">
        <div class="flex-between" style="margin-bottom:var(--space-4);">
            <div>
                <h4 style="margin:0;">Kelas Terpopuler</h4>
                <p class="text-muted" style="font-size:var(--fs-xs);">Persentase kehadiran berdasarkan jenis kelas.</p>
            </div>
        </div>
        <div class="chart-container" style="height:250px;"><canvas id="classChartActual"></canvas></div>
    </div>
        
    <div class="card" style="padding:0;">
        <div style="padding:var(--space-5); border-bottom:1px solid var(--color-border);">
            <h4 style="margin:0;">Rincian Kinerja Kelas</h4>
            <p class="text-muted" style="font-size:var(--fs-xs);">Analisa detail perbandingan kapasitas dan tingkat kehadiran peserta per kelas.</p>
        </div>
        <div class="table-wrap">
            <table class="table admin-table" style="margin-bottom:0;">
                <thead><tr><th>Nama Kelas</th><th>Total Sesi</th><th>Peserta / Kapasitas</th><th>Tingkat Kehadiran</th><th>Status Tren</th></tr></thead>
                <tbody>
                    @foreach($classStats as $c)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:12px;">
                                <div style="width:32px;height:32px;border-radius:8px;background:var(--color-primary-light);display:flex;align-items:center;justify-content:center;color:var(--color-primary);"><i class="bi bi-collection-play"></i></div>
                                <strong style="font-size:var(--fs-sm);">{{ $c['name'] }}</strong>
                            </div>
                        </td>
                        <td style="font-size:var(--fs-sm);">{{ $c['sessions'] }} sesi</td>
                        <td style="font-size:var(--fs-sm);font-weight:600;">{{ $c['avg'] }}</td>
                        <td style="width:250px;">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div class="progress-bar" style="flex:1;height:6px;background:var(--color-border);border-radius:4px;overflow:hidden;"><div class="progress-fill" style="height:100%;background:{{ $c['attendance_val'] >= 70 ? 'var(--color-mint)' : ($c['attendance_val'] >= 40 ? '#f59e0b' : 'var(--color-primary)') }};width:{{ $c['rate'] }};"></div></div>
                                <span style="font-size:12px;font-weight:600;min-width:35px;">{{ $c['rate'] }}</span>
                            </div>
                        </td>
                        <td><span class="status-badge {{ $c['trend']=='up'?'approved':'rejected' }}"><i class="bi {{ $c['trend']=='up'?'bi-arrow-up-right':'bi-arrow-down-right' }}"></i> {{ $c['trend']=='up'?'Optimal':'Perlu Atensi' }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- TAB: PENDAPATAN --}}
<div class="tab-content" id="tabPendapatan" style="display:none;">
    <div class="grid-2" style="gap:var(--space-6);margin-bottom:var(--space-6);">
        <div class="admin-stat-card">
            <div class="admin-stat-icon success"><i class="bi bi-wallet2"></i></div>
            <div class="admin-stat-content">
                <h4>Total Pendapatan (Estimasi)</h4>
                <div class="stat-value">{{ $totalRevFormatted }}</div>
                <div class="stat-trend up"><i class="bi bi-arrow-up-right"></i> Berdasarkan Membership Aktif</div>
            </div>
        </div>
        <div class="admin-stat-card">
            <div class="admin-stat-icon info"><i class="bi bi-credit-card"></i></div>
            <div class="admin-stat-content">
                <h4>Status Integrasi Payment</h4>
                <div class="stat-value" style="font-size:24px; color:var(--color-primary);">Belum Aktif</div>
                <div class="stat-trend down"><i class="bi bi-info-circle"></i> Menunggu Setup Gateway</div>
            </div>
        </div>
    </div>

    <div class="grid-2" style="gap:var(--space-6);margin-bottom:var(--space-6);">
        <div class="chart-card">
            <h4 style="margin-bottom:2px;">Sumber Pendapatan Paket</h4>
            <p class="text-muted" style="font-size:var(--fs-xs);margin-bottom:var(--space-4);">Kontribusi pendapatan (Rp) dari tiap jenis membership.</p>
            <div class="chart-container"><canvas id="revChart"></canvas></div>
        </div>
        <div class="card" style="display:flex;flex-direction:column;justify-content:center;align-items:center;text-align:center;padding:var(--space-6);">
            <div style="font-size:48px;color:var(--color-border);margin-bottom:var(--space-3);"><i class="bi bi-bar-chart-line-fill"></i></div>
            <h4 style="margin-bottom:var(--space-2);">Grafik Transaksi Harian</h4>
            <p class="text-muted" style="font-size:var(--fs-sm);margin-bottom:var(--space-4);">Data transaksi aktual belum tersedia karena modul Payment Gateway belum terintegrasi.</p>
            <button class="btn btn-outline">Pelajari Integrasi</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab Switching Logic
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            tabBtns.forEach(b => {
                b.classList.remove('active', 'btn-primary');
                b.classList.add('btn-ghost');
            });
            tabContents.forEach(c => c.style.display = 'none');
            
            btn.classList.remove('btn-ghost');
            btn.classList.add('active', 'btn-primary');
            document.getElementById(btn.dataset.tab).style.display = 'block';
        });
    });

    // Chart logic
    new Chart(document.getElementById('growthChart'), { type:'line', data:{labels:@json($growthLabels),datasets:[{label:'Members',data:@json($growthData),borderColor:'#FF6B2C',backgroundColor:'rgba(255,107,44,0.1)',fill:true,tension:.4,pointRadius:4}]}, options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{grid:{color:'#F0F0F2'}, ticks: {stepSize: 1, precision: 0}},x:{grid:{display:false}}}} });
    new Chart(document.getElementById('ageChart'), { type:'bar', data:{labels:['18-24','25-34','35-44','45-54','55+'],datasets:[{data:[{{ $ageDistribution['18-24'] }}, {{ $ageDistribution['25-34'] }}, {{ $ageDistribution['35-44'] }}, {{ $ageDistribution['45-54'] }}, {{ $ageDistribution['55+'] }}],backgroundColor:['#FF6B2C','#A8E63D','#3B82F6','#F59E0B','#6B7280'],borderRadius:6}]}, options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{grid:{color:'#F0F0F2'}, ticks: {stepSize: 1, precision: 0}},x:{grid:{display:false}}}} });
    new Chart(document.getElementById('pkgChart'), { type:'doughnut', data:{labels:@json($pkgLabels),datasets:[{data:@json($pkgData),backgroundColor:@json($pkgColors),borderWidth:0}]}, options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom'}},cutout:'65%'} });
    new Chart(document.getElementById('classChartActual'), { type:'bar', data:{labels:@json($classLabels),datasets:[{data:@json($classData),backgroundColor:'#FF6B2C',borderRadius:6}]}, options:{responsive:true,maintainAspectRatio:false,indexAxis:'y',plugins:{legend:{display:false}},scales:{x:{grid:{color:'#F0F0F2'}},y:{grid:{display:false}}}} });
    new Chart(document.getElementById('revChart'), { type:'pie', data:{labels:@json($pkgLabels),datasets:[{data:@json($revData),backgroundColor:@json($pkgColors),borderWidth:0}]}, options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom'}, tooltip: { callbacks: { label: function(context) { let value = context.raw; return ' Rp ' + value.toLocaleString('id-ID'); } } } } } });
});
</script>
@endsection
