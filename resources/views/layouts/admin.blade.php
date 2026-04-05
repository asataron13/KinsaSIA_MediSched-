<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>MediSched Admin</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f4f8; color: #1c2b22; display: flex; min-height: 100vh; }

        /* ── Sidebar ── */
        .sidebar {
            width: 240px; min-height: 100vh; background: #1a2e1f;
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; z-index: 200;
        }
        .sidebar-brand {
            padding: 24px 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar-brand .brand-name {
            color: white; font-size: 1.25rem; font-weight: 800; text-decoration: none; letter-spacing: -0.5px;
        }
        .sidebar-brand .brand-name span { color: #a8f0c6; }
        .sidebar-brand .brand-sub {
            color: rgba(255,255,255,0.4); font-size: 0.72rem; text-transform: uppercase;
            letter-spacing: 0.1em; margin-top: 3px;
        }

        .sidebar-nav { padding: 16px 12px; flex: 1; }
        .sidebar-nav .nav-label {
            color: rgba(255,255,255,0.3); font-size: 0.68rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.1em;
            padding: 0 12px; margin: 18px 0 6px;
        }
        .sidebar-nav a {
            display: flex; align-items: center; gap: 10px;
            color: rgba(255,255,255,0.65); text-decoration: none;
            padding: 9px 14px; border-radius: 8px; font-size: 0.92rem; font-weight: 500;
            transition: background 0.15s, color 0.15s; margin-bottom: 2px;
        }
        .sidebar-nav a:hover { background: rgba(255,255,255,0.08); color: white; }
        .sidebar-nav a.active { background: #27ae60; color: white; font-weight: 700; }
        .sidebar-nav a .nav-icon { font-size: 1rem; width: 20px; text-align: center; }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar-footer .admin-info {
            display: flex; align-items: center; gap: 10px; padding: 8px 12px; margin-bottom: 8px;
        }
        .sidebar-footer .admin-avatar {
            width: 34px; height: 34px; border-radius: 50%; background: #27ae60;
            color: white; font-weight: 700; font-size: 0.88rem;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .sidebar-footer .admin-name { color: white; font-size: 0.88rem; font-weight: 600; }
        .sidebar-footer .admin-role { color: rgba(255,255,255,0.4); font-size: 0.75rem; }
        .sidebar-footer form button {
            width: 100%; background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.7); padding: 8px 14px; border-radius: 8px;
            font-size: 0.88rem; font-weight: 600; cursor: pointer;
            transition: background 0.15s; text-align: left;
        }
        .sidebar-footer form button:hover { background: rgba(255,255,255,0.14); color: white; }

        /* ── Main content ── */
        .admin-main {
            margin-left: 240px; flex: 1; display: flex; flex-direction: column; min-height: 100vh;
        }

        /* ── Top bar ── */
        .topbar {
            background: white; border-bottom: 1px solid #e8f0eb;
            padding: 0 32px; height: 60px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }
        .topbar-title { font-size: 1.05rem; font-weight: 700; color: #1a2e1f; }
        .topbar-badge {
            background: #eafaf1; color: #1a7a42; font-size: 0.75rem;
            font-weight: 700; padding: 3px 10px; border-radius: 99px;
            text-transform: uppercase; letter-spacing: 0.04em;
        }

        /* ── Page content ── */
        .admin-content { padding: 32px; flex: 1; }

        /* ── Alerts ── */
        .alert { padding: 13px 18px; border-radius: 8px; margin-bottom: 22px; font-size: 0.93rem; font-weight: 500; }
        .alert-success { background: #d5f5e3; border: 1px solid #27ae60; color: #1a5c33; }
        .alert-error   { background: #fdecea; border: 1px solid #e74c3c; color: #a93226; }

        /* ── Stat cards ── */
        .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px,1fr)); gap: 18px; margin-bottom: 28px; }
        .stat-card {
            background: white; border-radius: 12px; padding: 22px 24px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            border-top: 4px solid #27ae60;
        }
        .stat-card .stat-value { font-size: 2rem; font-weight: 800; line-height: 1; }
        .stat-card .stat-label { font-size: 0.82rem; color: #5a8a6e; margin-top: 5px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; }

        /* ── Buttons ── */
        .btn {
            display: inline-block; padding: 9px 20px; border-radius: 8px;
            font-size: 0.9rem; font-weight: 600; cursor: pointer; border: none;
            text-decoration: none; transition: opacity 0.15s;
        }
        .btn:hover { opacity: 0.88; }
        .btn-green   { background: #27ae60; color: white; }
        .btn-red     { background: #e74c3c; color: white; }
        .btn-outline { background: white; color: #27ae60; border: 1.5px solid #27ae60; }
        .btn-outline:hover { background: #eafaf1; opacity: 1; }
        .btn-blue    { background: #2980b9; color: white; }
        .btn-sm      { padding: 5px 14px; font-size: 0.82rem; }

        /* ── Cards ── */
        .card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); margin-bottom: 22px; }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; }
        .card-header h3 { font-size: 1rem; font-weight: 700; }

        /* ── Page header ── */
        .page-header { margin-bottom: 24px; }
        .page-header h1 { font-size: 1.6rem; font-weight: 800; color: #1a2e1f; }
        .page-header p  { color: #5a8a6e; font-size: 0.92rem; margin-top: 4px; }

        /* ── Forms ── */
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 0.82rem; font-weight: 700; margin-bottom: 6px; color: #3a5a48; text-transform: uppercase; letter-spacing: 0.03em; }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 10px 14px; border: 1.5px solid #d4e6da; border-radius: 8px;
            font-size: 0.93rem; background: white; outline: none; transition: border-color 0.15s;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            border-color: #27ae60; box-shadow: 0 0 0 3px rgba(39,174,96,0.1);
        }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
        .error-msg { color: #c0392b; font-size: 0.82rem; margin-top: 5px; }

        /* ── Tables ── */
        .table-wrap { overflow-x: auto; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); }
        table { width: 100%; border-collapse: collapse; background: white; }
        th { background: #f4faf6; padding: 12px 16px; text-align: left; font-size: 0.76rem; text-transform: uppercase; letter-spacing: 0.05em; color: #3a5a48; font-weight: 700; white-space: nowrap; }
        td { padding: 13px 16px; border-bottom: 1px solid #eef4f0; font-size: 0.9rem; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f9fffb; }

        /* ── Badges ── */
        .badge { display: inline-block; padding: 3px 11px; border-radius: 99px; font-size: 0.76rem; font-weight: 700; }
        .badge-pending    { background: #fef9e7; color: #9a7d0a; }
        .badge-confirmed  { background: #d5f5e3; color: #1a7a42; }
        .badge-inprogress { background: #eaf4fb; color: #1a5276; }
        .badge-completed  { background: #f2f3f4; color: #555; }
        .badge-cancelled  { background: #fdecea; color: #c0392b; }
        .badge-available  { background: #d5f5e3; color: #1a7a42; }
        .badge-insession  { background: #eaf4fb; color: #1a5276; }
        .badge-dayoff     { background: #fef9e7; color: #9a7d0a; }
        .badge-active     { background: #d5f5e3; color: #1a7a42; }
        .badge-inactive   { background: #f2f3f4; color: #555; }
        .badge-pending-p  { background: #fef9e7; color: #9a7d0a; }

        /* ── Filter bar ── */
        .filter-bar { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px; align-items: center; }
        .filter-bar input, .filter-bar select {
            padding: 8px 14px; border: 1.5px solid #d4e6da; border-radius: 8px;
            font-size: 0.9rem; outline: none; background: white;
        }
        .filter-bar input:focus, .filter-bar select:focus { border-color: #27ae60; }

        /* ── Pagination ── */
        .pagination { display: flex; gap: 6px; justify-content: center; margin-top: 24px; flex-wrap: wrap; }
        .pagination a, .pagination span {
            padding: 7px 13px; border-radius: 7px; font-size: 0.88rem; font-weight: 600;
            border: 1.5px solid #d4e6da; background: white; color: #3a5a48; text-decoration: none;
            transition: background 0.15s;
        }
        .pagination a:hover { background: #eafaf1; }
        .pagination .active span, .pagination span[aria-current] {
            background: #27ae60; color: white; border-color: #27ae60;
        }

        /* ── Detail info grid ── */
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .info-item .info-label { font-size: 0.76rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; color: #5a8a6e; margin-bottom: 3px; }
        .info-item .info-value { font-size: 0.95rem; font-weight: 600; color: #1c2b22; }

        /* ── Empty state ── */
        .empty-state { text-align: center; padding: 52px 24px; }
        .empty-state .empty-icon { font-size: 2.4rem; margin-bottom: 12px; }
        .empty-state p { color: #5a8a6e; }
    </style>
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}" class="brand-name">Medi<span>Sched</span></a>
        <div class="brand-sub">Admin Panel</div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Main</div>

        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">📊</span> Dashboard
        </a>
        <a href="{{ route('admin.appointments.index') }}" class="{{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">
            <span class="nav-icon">📅</span> Appointments
        </a>
        <a href="{{ route('admin.patients.index') }}" class="{{ request()->routeIs('admin.patients.*') ? 'active' : '' }}">
            <span class="nav-icon">🧑‍⚕️</span> Patients
        </a>
        <a href="{{ route('admin.doctors.index') }}" class="{{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">
            <span class="nav-icon">👨‍⚕️</span> Doctors
        </a>
        <a href="{{ route('admin.records.index') }}" class="{{ request()->routeIs('admin.records.*') ? 'active' : '' }}">
            <span class="nav-icon">📋</span> Records
        </a>

        <div class="nav-label">System</div>
        <a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}">
            <span class="nav-icon">⚙️</span> Settings
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="admin-info">
            <div class="admin-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div>
                <div class="admin-name">{{ Auth::user()->name }}</div>
                <div class="admin-role">Administrator</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">🚪 &nbsp;Logout</button>
        </form>
    </div>
</aside>

<!-- Main -->
<div class="admin-main">
    <div class="topbar">
        <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
        <span class="topbar-badge">Admin</span>
    </div>

    <div class="admin-content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</div>

</body>
</html>