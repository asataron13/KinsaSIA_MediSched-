@extends('layouts.app')

@section('content')

<div style="max-width:460px;margin:52px auto;">
    <div class="card">
        <h2 style="font-size:1.5rem;font-weight:800;margin-bottom:6px;">Create an Account</h2>
        <p style="color:#5a8a6e;margin-bottom:28px;font-size:0.92rem;">Sign up to start booking appointments</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Juan dela Cruz" required autofocus/>
                @error('name') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Email <span style="font-weight:400;text-transform:none;color:#5a8a6e;">(optional if phone is provided)</span></label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="juan@email.com"/>
                @error('email') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Phone <span style="font-weight:400;text-transform:none;color:#5a8a6e;">(optional if email is provided)</span></label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="09171234567"/>
                @error('phone') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Password *</label>
                <input type="password" name="password" required/>
                @error('password') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Confirm Password *</label>
                <input type="password" name="password_confirmation" required/>
            </div>

            <button type="submit" class="btn btn-green" style="width:100%;padding:12px;font-size:1rem;">Create Account</button>
        </form>

        <p style="text-align:center;margin-top:20px;font-size:0.92rem;color:#5a8a6e;">
            Already have an account? <a href="{{ route('login') }}" style="color:#27ae60;font-weight:700;">Log in</a>
        </p>
    </div>
</div>

@endsection