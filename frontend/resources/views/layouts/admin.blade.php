<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — Fitness Vals Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @yield('styles')
</head>
<body>
<div class="dash-layout">
    <aside class="dash-sidebar" id="dashSidebar">
        <div class="dash-sidebar-header">
            <div class="dash-sidebar-brand"><i class="bi bi-shield-check"></i> Admin Console</div>
            <div class="dash-sidebar-sub">Fitness Vals Management</div>
        </div>
        <div class="dash-sidebar-cta">
            <a href="{{ route('admin.member.detail', 'new') }}" class="btn btn-cta btn-block btn-sm"><i class="bi bi-plus-lg"></i> Add New Member</a>
        </div>
        <nav class="dash-nav">
            <div class="dash-nav-label">Overview</div>
            <a href="{{ route('admin.dashboard') }}" class="dash-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2"></i> Dashboard</a>
            <a href="{{ route('admin.members') }}" class="dash-nav-item {{ request()->routeIs('admin.members') ? 'active' : '' }}"><i class="bi bi-people"></i> Daftar Member</a>
            <a href="{{ route('admin.reports') }}" class="dash-nav-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}"><i class="bi bi-bar-chart-line"></i> Laporan & Statistik</a>

            <div class="dash-nav-label">Management</div>
            <a href="{{ route('admin.classes') }}" class="dash-nav-item {{ request()->routeIs('admin.classes') ? 'active' : '' }}"><i class="bi bi-calendar-event"></i> Kelas & Program</a>
            <a href="{{ route('admin.trainers') }}" class="dash-nav-item {{ request()->routeIs('admin.trainers') ? 'active' : '' }}"><i class="bi bi-person-badge"></i> Trainer</a>

            <div class="dash-nav-label">CMS & Settings</div>
            <a href="{{ route('admin.content') }}" class="dash-nav-item {{ request()->routeIs('admin.content') ? 'active' : '' }}"><i class="bi bi-file-earmark-text"></i> Konten Website</a>
            <a href="{{ route('admin.appearance') }}" class="dash-nav-item {{ request()->routeIs('admin.appearance') ? 'active' : '' }}"><i class="bi bi-palette"></i> Tampilan</a>
            <a href="{{ route('admin.features') }}" class="dash-nav-item {{ request()->routeIs('admin.features') ? 'active' : '' }}"><i class="bi bi-toggles"></i> Fitur Toggle</a>
            <a href="{{ route('admin.settings') }}" class="dash-nav-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}"><i class="bi bi-gear"></i> Pengaturan</a>
        </nav>
        <div class="dash-sidebar-footer">
            <a href="{{ route('home') }}" class="dash-nav-item"><i class="bi bi-globe2"></i> Kembali ke Website</a>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="dash-nav-item" style="color:rgba(239,68,68,0.7);background:transparent;border:none;width:100%;text-align:left;cursor:pointer;">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="dash-main">
        <header class="dash-header">
            <div class="dash-header-left">
                <h2>@yield('page_title', 'Dashboard')</h2>
                @yield('page_badge')
            </div>
            <div class="dash-header-right">
                <button class="btn-icon"><i class="bi bi-bell"></i></button>
                <button class="btn-icon"><i class="bi bi-search"></i></button>
            </div>
        </header>
        <div class="dash-content">
            @yield('content')
        </div>
    </div>
</div>
<script src="{{ asset('js/app.js') }}"></script>
@yield('scripts')
</body>
</html>
