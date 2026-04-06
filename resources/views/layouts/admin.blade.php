<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>MediSched Admin</title>

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4faf6;
            color: #1c2b22;
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #1a7a42, #145c31);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100%;
        }

        .sidebar-brand {
            padding: 22px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-brand img {
            width: 38px;
        }

        .sidebar-brand .brand-name {
            color: white;
            font-weight: 800;
            font-size: 1.3rem;
        }

        .sidebar-brand span {
            color: #a8f0c6;
        }

        .sidebar-nav {
            padding: 14px;
            flex: 1;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 8px;
            color: rgba(255,255,255,0.8);
            margin-bottom: 6px;
            font-weight: 600;
            transition: 0.2s;
        }

        .sidebar-nav a:hover {
            background: rgba(255,255,255,0.15);
            transform: translateX(3px);
        }

        .sidebar-nav a.active {
            background: white;
            color: #1a7a42;
        }

        /* FOOTER ADMIN */
        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .admin-avatar {
            width: 34px;
            height: 34px;
            background: white;
            color: #1a7a42;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .admin-name { color: white; font-size: 0.9rem; }
        .admin-role { color: rgba(255,255,255,0.6); font-size: 0.75rem; }

        .sidebar-footer button {
            width: 100%;
            padding: 8px;
            border-radius: 8px;
            border: none;
            background: rgba(255,255,255,0.2);
            color: white;
            cursor: pointer;
        }

        .sidebar-footer button:hover {
            background: rgba(255,255,255,0.3);
        }

        /* MAIN */
        .admin-main {
            margin-left: 250px;
            flex: 1;
        }

        /* TOPBAR */
        .topbar {
            background: white;
            height: 60px;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }

        .topbar-title {
            font-weight: 800;
            font-size: 1.1rem;
        }

        .topbar-badge {
            background: #eafaf1;
            color: #1a7a42;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        /* CONTENT */
        .admin-content {
            padding: 28px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success { background: #d5f5e3; color: #1a5c33; }
        .alert-error { background: #fdecea; color: #a93226; }

    </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">

    <div class="sidebar-brand">
        <img src="{{ asset('images/logo.png') }}">
        <div class="brand-name">Medi<span>Sched</span></div>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            📊 Dashboard
        </a>

        <a href="{{ route('admin.appointments.index') }}" class="{{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">
            📅 Appointments
        </a>

        <a href="{{ route('admin.patients.index') }}" class="{{ request()->routeIs('admin.patients.*') ? 'active' : '' }}">
            🧑‍⚕️ Patients
        </a>

        <a href="{{ route('admin.doctors.index') }}" class="{{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">
            👨‍⚕️ Doctors
        </a>

        <a href="{{ route('admin.records.index') }}" class="{{ request()->routeIs('admin.records.*') ? 'active' : '' }}">
            📋 Records
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="admin-info">
            <div class="admin-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div>
                <div class="admin-name">{{ Auth::user()->name }}</div>
                <div class="admin-role">Admin</div>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>

</aside>

<!-- MAIN -->
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
