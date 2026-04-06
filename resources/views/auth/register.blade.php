@extends('layouts.app')

@section('title', 'Register')

@section('content')

<div style="max-width: 500px; margin: 56px auto;">
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
                Create an Account
            </h2>

            <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6;">
                Sign up to start booking appointments
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label>Full Name *</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Juan dela Cruz"
                    required
                    autofocus
                />
                @error('name')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>
                    Email
                    <span style="font-weight: 400; text-transform: none; color: var(--text-muted);">
                        (optional if phone is provided)
                    </span>
                </label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="juan@email.com"
                />
                @error('email')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>
                    Phone
                    <span style="font-weight: 400; text-transform: none; color: var(--text-muted);">
                        (optional if email is provided)
                    </span>
                </label>
                <input
                    type="text"
                    name="phone"
                    value="{{ old('phone') }}"
                    placeholder="09171234567"
                />
                @error('phone')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Password *</label>
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

            <div class="form-group">
                <label>Confirm Password *</label>
                <input
                    type="password"
                    name="password_confirmation"
                    placeholder="Confirm your password"
                    required
                />
            </div>

            <button
                type="submit"
                class="btn btn-green"
                style="width: 100%; padding: 13px; font-size: 1rem; margin-top: 6px;"
            >
                Create Account
            </button>
        </form>

        <div style="
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid var(--green-soft);
            text-align: center;
        ">
            <p style="font-size: 0.94rem; color: var(--text-muted);">
                Already have an account?
                <a
                    href="{{ route('login') }}"
                    style="color: var(--green-main); font-weight: 800;"
                >
                    Log in
                </a>
            </p>
        </div>
    </div>
</div>

@endsection
