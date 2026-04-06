@extends('layouts.app')

@section('title', 'Log In')

@section('content')

<div style="max-width: 460px; margin: 56px auto;">
    <div class="card" style="padding: 34px 30px;">
        <div style="text-align: center; margin-bottom: 28px;">
            <div style="margin: 0 auto 16px; display: flex; justify-content: center;">
                <img
                    src="{{ asset('images/logo.png') }}"
                    alt="MediSched Logo"
                    style="width: 78px; height: 78px; object-fit: contain;"
                >
            </div>

            <h2 style="font-size: 1.8rem; font-weight: 800; margin-bottom: 8px; color: var(--text-dark);">
                Welcome back
            </h2>

            <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6;">
                Log in with your email or phone number
            </p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label>Email or Phone</label>
                <input
                    type="text"
                    name="login"
                    value="{{ old('login') }}"
                    placeholder="juan@email.com or 09171234567"
                    required
                    autofocus
                />
                @error('login')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Password</label>
                <input
                    type="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                />
                @error('password')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div style="
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                margin: 8px 0 24px;
                flex-wrap: wrap;
            ">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <input
                        type="checkbox"
                        name="remember"
                        id="remember"
                        style="width: auto; accent-color: var(--green-main);"
                    />
                    <label
                        for="remember"
                        style="
                            margin: 0;
                            text-transform: none;
                            font-size: 0.92rem;
                            font-weight: 500;
                            color: #3a5a48;
                            letter-spacing: 0;
                        "
                    >
                        Remember me
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a
                        href="{{ route('password.request') }}"
                        style="
                            font-size: 0.9rem;
                            font-weight: 700;
                            color: var(--green-main);
                        "
                    >
                        Forgot password?
                    </a>
                @endif
            </div>

            <button
                type="submit"
                class="btn btn-green"
                style="width: 100%; padding: 13px; font-size: 1rem;"
            >
                Log In
            </button>
        </form>

        <div style="
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid var(--green-soft);
            text-align: center;
        ">
            <p style="font-size: 0.94rem; color: var(--text-muted);">
                Don’t have an account?
                <a
                    href="{{ route('register') }}"
                    style="color: var(--green-main); font-weight: 800;"
                >
                    Sign up
                </a>
            </p>
        </div>
    </div>
</div>

@endsection
