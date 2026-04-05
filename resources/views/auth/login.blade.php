@extends('layouts.app')

@section('content')

<div style="max-width:420px;margin:52px auto;">
    <div class="card">
        <h2 style="font-size:1.5rem;font-weight:800;margin-bottom:6px;">Welcome back</h2>
        <p style="color:#5a8a6e;margin-bottom:28px;font-size:0.92rem;">Log in with your email or phone number</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label>Email or Phone</label>
                <input type="text" name="login" value="{{ old('login') }}" placeholder="juan@email.com or 09171234567" required autofocus/>
                @error('login') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required/>
                @error('password') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="form-group" style="display:flex;align-items:center;gap:8px;">
                <input type="checkbox" name="remember" id="remember" style="width:auto;"/>
                <label for="remember" style="margin:0;text-transform:none;font-size:0.9rem;font-weight:400;color:#3a5a48;">Remember me</label>
            </div>

            <button type="submit" class="btn btn-green" style="width:100%;padding:12px;font-size:1rem;">Log In</button>
        </form>

        <p style="text-align:center;margin-top:20px;font-size:0.92rem;color:#5a8a6e;">
            Don't have an account? <a href="{{ route('register') }}" style="color:#27ae60;font-weight:700;">Sign up</a>
        </p>
    </div>
</div>

@endsection