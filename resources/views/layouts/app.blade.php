<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>MediSched</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4faf6; color: #1c2b22; }

        nav {
            background: #1a7a42; padding: 0 40px; height: 60px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
            position: sticky; top: 0; z-index: 100;
        }
        nav .brand { color: white; font-weight: 800; font-size: 1.3rem; text-decoration: none; letter-spacing: -0.5px; }
        nav .brand span { color: #a8f0c6; }
        nav .nav-links { display: flex; align-items: center; gap: 4px; }
        nav .nav-links a {
            color: rgba(255,255,255,0.85); text-decoration: none;
            padding: 6px 14px; border-radius: 6px; font-size: 0.92rem; font-weight: 500;
            transition: background 0.15s;
        }
        nav .nav-links a:hover,
        nav .nav-links a.active { background: rgba(255,255,255,0.15); color: white; }
        nav .nav-right { display: flex; align-items: center; gap: 10px; }
        nav .user-name { color: rgba(255,255,255,0.9); font-size: 0.88rem; }
        nav .btn-logout {
            background: rgba(255,255,255,0.18); border: 1px solid rgba(255,255,255,0.35);
            color: white; padding: 6px 16px; border-radius: 6px;
            font-size: 0.88rem; font-weight: 600; cursor: pointer; transition: background 0.15s;
        }
        nav .btn-logout:hover { background: rgba(255,255,255,0.28); }
        nav .btn-nav-login {
            border: 1.5px solid rgba(255,255,255,0.6); color: white; background: transparent;
            padding: 6px 18px; border-radius: 6px; font-size: 0.9rem; font-weight: 600;
            text-decoration: none; transition: background 0.15s;
        }
        nav .btn-nav-login:hover { background: rgba(255,255,255,0.12); }
        nav .btn-nav-signup {
            background: white; color: #1a7a42; padding: 6px 18px; border-radius: 6px;
            font-size: 0.9rem; font-weight: 700; text-decoration: none; transition: opacity 0.15s;
        }
        nav .btn-nav-signup:hover { opacity: 0.88; }
        .nav-role-badge {
            background: rgba(255,255,255,0.2); color: white; font-size: 0.75rem;
            font-weight: 700; padding: 3px 10px; border-radius: 99px;
            letter-spacing: 0.04em; text-transform: uppercase;
        }

        .container { max-width: 1140px; margin: 0 auto; padding: 36px 24px; }

        .alert { padding: 13px 18px; border-radius: 8px; margin-bottom: 22px; font-size: 0.93rem; font-weight: 500; }
        .alert-success { background: #d5f5e3; border: 1px solid #27ae60; color: #1a5c33; }
        .alert-error   { background: #fdecea; border: 1px solid #e74c3c; color: #a93226; }

        .btn {
            display: inline-block; padding: 9px 22px; border-radius: 8px;
            font-size: 0.92rem; font-weight: 600; cursor: pointer; border: none;
            text-decoration: none; transition: opacity 0.15s, background 0.15s;
        }
        .btn:hover { opacity: 0.88; }
        .btn-green   { background: #27ae60; color: white; }
        .btn-red     { background: #e74c3c; color: white; }
        .btn-outline { background: white; color: #27ae60; border: 1.5px solid #27ae60; }
        .btn-outline:hover { background: #eafaf1; opacity: 1; }
        .btn-blue    { background: #2980b9; color: white; }
        .btn-gray    { background: #f2f3f4; color: #888; cursor: not-allowed; }

        .card { background: white; border-radius: 14px; padding: 28px; box-shadow: 0 2px 14px rgba(0,0,0,0.07); margin-bottom: 22px; }
        .page-header { margin-bottom: 28px; }
        .page-header h1 { font-size: 1.7rem; font-weight: 800; color: #1a2e1f; }
        .page-header p  { color: #5a8a6e; font-size: 0.95rem; margin-top: 5px; }

        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 0.84rem; font-weight: 700; margin-bottom: 6px; color: #3a5a48; text-transform: uppercase; letter-spacing: 0.03em; }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 10px 14px; border: 1.5px solid #c8e6d4; border-radius: 8px;
            font-size: 0.94rem; background: white; outline: none; transition: border-color 0.15s;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            border-color: #27ae60; box-shadow: 0 0 0 3px rgba(39,174,96,0.12);
        }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .error-msg { color: #c0392b; font-size: 0.82rem; margin-top: 5px; }

        .table-wrap { overflow-x: auto; border-radius: 12px; box-shadow: 0 2px 14px rgba(0,0,0,0.07); }
        table { width: 100%; border-collapse: collapse; background: white; }
        th { background: #eafaf1; padding: 13px 18px; text-align: left; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.05em; color: #3a5a48; font-weight: 700; }
        td { padding: 14px 18px; border-bottom: 1px solid #eafaf1; font-size: 0.92rem; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f9fffb; }

        .badge { display: inline-block; padding: 4px 12px; border-radius: 99px; font-size: 0.78rem; font-weight: 700; letter-spacing: 0.02em; }
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

        .empty-state { text-align: center; padding: 64px 24px; }
        .empty-state .empty-icon { font-size: 2.8rem; margin-bottom: 14px; }
        .empty-state p { color: #5a8a6e; font-size: 1rem; }
    </style>
</head>
<body>

<nav>
    <a class="brand" href="{{ route('home') }}">Medi<span>Sched</span></a>

    <div class="nav-links">
        @auth
            @if(Auth::user()->role === 'doctor')
                <a href="{{ route('home') }}"            class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                <a href="{{ route('doctors.index') }}"   class="{{ request()->routeIs('doctors.index') ? 'active' : '' }}">Doctors List</a>
                <a href="{{ route('doctor.queue') }}"    class="{{ request()->routeIs('doctor.queue') ? 'active' : '' }}">Patient Queue</a>
                <a href="{{ route('doctor.accepted') }}" class="{{ request()->routeIs('doctor.accepted') ? 'active' : '' }}">My Patients</a>
            @else
                {{-- Patient nav --}}
                <a href="{{ route('home') }}"              class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                <a href="{{ route('appointments.book') }}" class="{{ request()->routeIs('appointments.book') ? 'active' : '' }}">Book Appointment</a>
                <a href="{{ route('doctors.index') }}"     class="{{ request()->routeIs('doctors.index') ? 'active' : '' }}">Doctors List</a>
                <a href="{{ route('appointments.my') }}"   class="{{ request()->routeIs('appointments.my') ? 'active' : '' }}">My Appointments</a>
            @endif
        @else
            {{-- Guest nav --}}
            <a href="{{ route('home') }}"              class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('appointments.book') }}" class="{{ request()->routeIs('appointments.book') ? 'active' : '' }}">Book Appointment</a>
            <a href="{{ route('doctors.index') }}"     class="{{ request()->routeIs('doctors.index') ? 'active' : '' }}">Doctors List</a>
        @endguest
    </div>

    <div class="nav-right">
        @auth
            @if(Auth::user()->role === 'doctor')
                <span class="nav-role-badge">Doctor</span>
            @endif
            <span class="user-name">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}"    class="btn-nav-login">Log In</a>
            <a href="{{ route('register') }}" class="btn-nav-signup">Sign Up</a>
        @endauth
    </div>
</nav>

<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    @yield('content')
</div>

</body>
</html>