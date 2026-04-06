<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'MediSched')</title>

    <style>
        :root {
            --green-dark: #1a7a42;
            --green-main: #27ae60;
            --green-light: #d5f5e3;
            --green-bg: #f4faf6;
            --green-soft: #eafaf1;
            --green-accent: #a8f0c6;
            --text-dark: #1c2b22;
            --text-muted: #5a8a6e;
            --white: #ffffff;
            --red: #e74c3c;
            --blue: #2980b9;
            --gray: #f2f3f4;
            --border: #c8e6d4;
            --shadow-sm: 0 4px 14px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 10px 28px rgba(0, 0, 0, 0.10);
            --shadow-lg: 0 18px 40px rgba(26, 122, 66, 0.16);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 18px;
            --radius-xl: 24px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(213, 245, 227, 0.55), transparent 28%),
                radial-gradient(circle at top right, rgba(168, 240, 198, 0.25), transparent 24%),
                var(--green-bg);
            color: var(--text-dark);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        img {
            display: block;
            max-width: 100%;
        }

        /* NAVBAR */
        nav {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: rgba(26, 122, 66, 0.96);
            backdrop-filter: blur(10px);
            box-shadow: 0 3px 14px rgba(0, 0, 0, 0.10);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .nav-inner {
            max-width: 1280px;
            margin: 0 auto;
            min-height: 78px;
            padding: 14px 26px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .brand img {
            width: 44px;
            height: 44px;
            object-fit: contain;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.15));
        }

        .brand-text {
            color: var(--white);
            font-weight: 800;
            font-size: 1.8rem;
            letter-spacing: -0.6px;
            line-height: 1;
        }

        .brand-text span {
            color: var(--green-accent);
        }

        .nav-links {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
            gap: 8px;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: rgba(255,255,255,0.92);
            padding: 10px 16px;
            border-radius: 999px;
            font-size: 0.96rem;
            font-weight: 700;
            transition: 0.2s ease;
        }

        .nav-links a:hover,
        .nav-links a.active {
            background: rgba(255,255,255,0.15);
            color: var(--white);
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .nav-user {
            color: rgba(255,255,255,0.95);
            font-size: 0.92rem;
            font-weight: 600;
            background: rgba(255,255,255,0.10);
            padding: 8px 12px;
            border-radius: 999px;
        }

        .btn-nav-login,
        .btn-nav-signup,
        .btn-logout {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 10px 16px;
            font-size: 0.93rem;
            font-weight: 800;
            transition: 0.2s ease;
        }

        .btn-nav-login,
        .btn-logout {
            border: 1px solid rgba(255,255,255,0.35);
            color: var(--white);
            background: rgba(255,255,255,0.08);
        }

        .btn-nav-login:hover,
        .btn-logout:hover {
            background: rgba(255,255,255,0.18);
        }

        .btn-nav-signup {
            background: var(--white);
            color: var(--green-dark);
            box-shadow: 0 6px 16px rgba(0,0,0,0.10);
        }

        .btn-nav-signup:hover {
            transform: translateY(-1px);
        }

        /* PAGE WRAP */
        .page-shell {
            flex: 1;
            width: 100%;
        }

        .container {
            max-width: 1220px;
            margin: 0 auto;
            padding: 42px 22px 56px;
        }

        /* ALERTS */
        .alert {
            padding: 15px 18px;
            border-radius: 14px;
            margin-bottom: 24px;
            font-size: 0.95rem;
            font-weight: 600;
            box-shadow: var(--shadow-sm);
        }

        .alert-success {
            background: var(--green-light);
            border: 1px solid var(--green-main);
            color: var(--green-dark);
        }

        .alert-error {
            background: #fdecea;
            border: 1px solid var(--red);
            color: #a93226;
        }

        /* BUTTONS */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 20px;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 800;
            border: none;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-green {
            background: linear-gradient(135deg, var(--green-main), var(--green-dark));
            color: var(--white);
            box-shadow: 0 10px 24px rgba(39, 174, 96, 0.22);
        }

        .btn-red {
            background: var(--red);
            color: var(--white);
        }

        .btn-blue {
            background: var(--blue);
            color: var(--white);
        }

        .btn-outline {
            background: var(--white);
            color: var(--green-dark);
            border: 1.5px solid var(--green-main);
        }

        .btn-gray {
            background: var(--gray);
            color: #777;
            cursor: not-allowed;
        }

        .btn-sm {
            padding: 8px 14px;
            font-size: 0.86rem;
            border-radius: 10px;
        }

        /* CARDS */
        .card {
            background: rgba(255,255,255,0.94);
            border: 1px solid rgba(26, 122, 66, 0.08);
            border-radius: var(--radius-lg);
            padding: 28px;
            box-shadow: var(--shadow-sm);
            backdrop-filter: blur(8px);
        }

        /* HEADINGS */
        .page-header {
            margin-bottom: 28px;
        }

        .page-header h1 {
            font-size: 2rem;
            line-height: 1.15;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .page-header p {
            color: var(--text-muted);
            font-size: 0.98rem;
        }

        /* FORMS */
        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.83rem;
            font-weight: 800;
            color: #365445;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            background: #fff;
            font-size: 0.95rem;
            outline: none;
            color: var(--text-dark);
            transition: 0.18s ease;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--green-main);
            box-shadow: 0 0 0 4px rgba(39, 174, 96, 0.12);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .error-msg {
            color: #c0392b;
            font-size: 0.83rem;
            margin-top: 6px;
        }

        /* TABLES */
        .table-wrap {
            overflow-x: auto;
            background: rgba(255,255,255,0.95);
            border-radius: 18px;
            border: 1px solid rgba(26, 122, 66, 0.08);
            box-shadow: var(--shadow-sm);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: transparent;
        }

        th {
            background: var(--green-soft);
            padding: 15px 18px;
            text-align: left;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #355545;
            font-weight: 800;
            white-space: nowrap;
        }

        td {
            padding: 15px 18px;
            font-size: 0.94rem;
            border-bottom: 1px solid var(--green-soft);
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover td {
            background: #f9fffb;
        }

        /* BADGES */
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.76rem;
            font-weight: 800;
        }

        .badge-pending    { background: #fef9e7; color: #9a7d0a; }
        .badge-confirmed  { background: var(--green-light); color: var(--green-dark); }
        .badge-inprogress { background: #eaf4fb; color: #1a5276; }
        .badge-completed  { background: #f2f3f4; color: #555; }
        .badge-cancelled  { background: #fdecea; color: #c0392b; }
        .badge-available  { background: var(--green-light); color: var(--green-dark); }
        .badge-insession  { background: #eaf4fb; color: #1a5276; }
        .badge-dayoff     { background: #fef9e7; color: #9a7d0a; }
        .badge-active     { background: var(--green-light); color: var(--green-dark); }
        .badge-inactive   { background: #f2f3f4; color: #555; }

        .text-muted {
            color: var(--text-muted);
        }

        .empty-state {
            text-align: center;
            padding: 60px 24px;
        }

        .empty-state .empty-icon {
            font-size: 2.7rem;
            margin-bottom: 12px;
        }

        /* FOOTER */
        footer {
            margin-top: auto;
            background: linear-gradient(135deg, var(--green-dark), #15653a);
            color: rgba(255,255,255,0.95);
            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .footer-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 24px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            flex-wrap: wrap;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-brand img {
            width: 34px;
            height: 34px;
            object-fit: contain;
        }

        .footer-brand strong {
            font-size: 1rem;
            font-weight: 800;
        }

        .footer-text {
            font-size: 0.92rem;
            opacity: 0.92;
        }

        /* RESPONSIVE */
        @media (max-width: 992px) {
            .nav-inner {
                flex-direction: column;
                align-items: stretch;
                padding: 16px 18px;
            }

            .brand {
                justify-content: center;
            }

            .nav-links {
                justify-content: center;
            }

            .nav-right {
                justify-content: center;
            }

            .container {
                padding: 30px 16px 44px;
            }

            .footer-inner {
                flex-direction: column;
                text-align: center;
            }
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .card {
                padding: 22px;
            }

            .page-header h1 {
                font-size: 1.65rem;
            }

            th, td {
                padding: 12px 14px;
            }
        }

        @media (max-width: 576px) {
            .nav-links {
                flex-direction: column;
                width: 100%;
            }

            .nav-links a,
            .btn-nav-login,
            .btn-nav-signup,
            .btn-logout {
                width: 100%;
                text-align: center;
            }

            .nav-right {
                flex-direction: column;
                width: 100%;
            }

            .nav-user {
                width: 100%;
                text-align: center;
            }

            .brand-text {
                font-size: 1.55rem;
            }
        }
    </style>
</head>
<body>

<nav>
    <div class="nav-inner">
        <a class="brand" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="MediSched Logo">
            <div class="brand-text">Medi<span>Sched</span></div>
        </a>

        <div class="nav-links">
            @auth
                @if(Auth::user()->role === 'doctor')
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                    <a href="{{ route('doctors.index') }}" class="{{ request()->routeIs('doctors.index') ? 'active' : '' }}">Doctors List</a>
                    <a href="{{ route('doctor.queue') }}" class="{{ request()->routeIs('doctor.queue') ? 'active' : '' }}">Patient Queue</a>
                    <a href="{{ route('doctor.accepted') }}" class="{{ request()->routeIs('doctor.accepted') ? 'active' : '' }}">My Patients</a>
                @else
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                    <a href="{{ route('appointments.book') }}" class="{{ request()->routeIs('appointments.book') ? 'active' : '' }}">Book Appointment</a>
                    <a href="{{ route('doctors.index') }}" class="{{ request()->routeIs('doctors.index') ? 'active' : '' }}">Doctors List</a>
                    <a href="{{ route('appointments.my') }}" class="{{ request()->routeIs('appointments.my') ? 'active' : '' }}">My Appointments</a>
                @endif
            @else
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                <a href="{{ route('appointments.book') }}" class="{{ request()->routeIs('appointments.book') ? 'active' : '' }}">Book Appointment</a>
                <a href="{{ route('doctors.index') }}" class="{{ request()->routeIs('doctors.index') ? 'active' : '' }}">Doctors List</a>
            @endauth
        </div>

        <div class="nav-right">
            @auth
                <span class="nav-user">{{ Auth::user()->name }}</span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-nav-login">Log In</a>
                <a href="{{ route('register') }}" class="btn-nav-signup">Sign Up</a>
            @endauth
        </div>
    </div>
</nav>

<div class="page-shell">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</div>

<footer>
    <div class="footer-inner">
        <div class="footer-brand">
            <img src="{{ asset('images/logo.png') }}" alt="MediSched Logo">
            <strong>MediSched</strong>
        </div>

        <div class="footer-text">
            © {{ date('Y') }} MediSched. Smart scheduling for better care.
        </div>
    </div>
</footer>

</body>
</html>
