@extends('layouts.app')

@section('content')

{{-- Hero --}}
<div style="text-align:center;padding:72px 24px;background:linear-gradient(135deg,#27ae60 0%,#1a7a42 100%);border-radius:18px;color:white;margin-bottom:48px;">
    <p style="font-size:0.9rem;letter-spacing:0.12em;text-transform:uppercase;opacity:0.8;margin-bottom:12px;">MediSched General Hospital</p>
    <h1 style="font-size:2.6rem;font-weight:800;line-height:1.2;max-width:620px;margin:0 auto;">Your Health, Scheduled with Care</h1>
    <p style="margin-top:16px;opacity:0.88;font-size:1.05rem;max-width:500px;margin-left:auto;margin-right:auto;">
        Book consultations with our specialist doctors online — fast, easy, and from anywhere.
    </p>
    <div style="margin-top:32px;display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
        @auth
            @if(Auth::user()->role === 'doctor')
                {{-- Doctors see their queue instead --}}
                <a href="{{ route('doctor.queue') }}" style="background:white;color:#1a7a42;padding:12px 32px;border-radius:8px;font-weight:700;font-size:1rem;text-decoration:none;">
                    View Patient Queue
                </a>
                <a href="{{ route('doctors.index') }}" style="background:rgba(255,255,255,0.18);color:white;border:2px solid rgba(255,255,255,0.6);padding:12px 32px;border-radius:8px;font-weight:700;font-size:1rem;text-decoration:none;">
                    Doctors List
                </a>
            @else
                {{-- Patients --}}
                <a href="{{ route('appointments.book') }}" style="background:white;color:#1a7a42;padding:12px 32px;border-radius:8px;font-weight:700;font-size:1rem;text-decoration:none;">
                    Book an Appointment
                </a>
                <a href="{{ route('doctors.index') }}" style="background:rgba(255,255,255,0.18);color:white;border:2px solid rgba(255,255,255,0.6);padding:12px 32px;border-radius:8px;font-weight:700;font-size:1rem;text-decoration:none;">
                    View Doctors
                </a>
            @endif
        @else
            {{-- Guests --}}
            <a href="{{ route('appointments.book') }}" style="background:white;color:#1a7a42;padding:12px 32px;border-radius:8px;font-weight:700;font-size:1rem;text-decoration:none;">
                Book an Appointment
            </a>
            <a href="{{ route('doctors.index') }}" style="background:rgba(255,255,255,0.18);color:white;border:2px solid rgba(255,255,255,0.6);padding:12px 32px;border-radius:8px;font-weight:700;font-size:1rem;text-decoration:none;">
                View Doctors
            </a>
        @endauth
    </div>
</div>

{{-- How it works --}}
<div style="margin-bottom:52px;">
    <h2 style="font-size:1.5rem;font-weight:800;margin-bottom:24px;text-align:center;">How It Works</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;">
        @foreach([
            ['01', 'Create an Account', 'Sign up with your email or phone number in seconds.'],
            ['02', 'Choose a Doctor',   'Browse our specialists and pick the right one for you.'],
            ['03', 'Book a Slot',       'Select your preferred date and type of consultation.'],
            ['04', 'Get Confirmed',     'Our team reviews and confirms your appointment.'],
        ] as [$num, $title, $desc])
        <div class="card" style="text-align:center;padding:28px 20px;">
            <div style="width:44px;height:44px;border-radius:50%;background:#eafaf1;color:#27ae60;font-weight:800;font-size:1rem;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">{{ $num }}</div>
            <div style="font-weight:700;font-size:1rem;margin-bottom:6px;">{{ $title }}</div>
            <div style="font-size:0.86rem;color:#5a8a6e;">{{ $desc }}</div>
        </div>
        @endforeach
    </div>
</div>

{{-- Departments --}}
@if($departments->isNotEmpty())
<div style="margin-bottom:52px;">
    <h2 style="font-size:1.5rem;font-weight:800;margin-bottom:22px;">Our Departments</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;">
        @foreach($departments as $dept)
        <div class="card" style="padding:20px;margin-bottom:0;">
            <div style="font-weight:700;margin-bottom:4px;">{{ $dept->name }}</div>
            @if($dept->description)
                <div style="font-size:0.83rem;color:#5a8a6e;">{{ $dept->description }}</div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Featured Doctors --}}
@if($doctors->isNotEmpty())
<div>
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:22px;">
        <h2 style="font-size:1.5rem;font-weight:800;">Meet Our Doctors</h2>
        <a href="{{ route('doctors.index') }}" class="btn btn-outline" style="font-size:0.88rem;padding:7px 18px;">See all →</a>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:18px;">
        @foreach($doctors->take(4) as $doctor)
        <div class="card" style="text-align:center;padding:28px 20px;margin-bottom:0;">
            <div style="width:62px;height:62px;border-radius:50%;background:#27ae60;color:white;font-weight:800;font-size:1.15rem;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                {{ strtoupper(substr($doctor->first_name,0,1).substr($doctor->last_name,0,1)) }}
            </div>
            <div style="font-weight:700;font-size:1rem;">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</div>
            <div style="font-size:0.85rem;color:#27ae60;font-weight:600;margin-top:3px;">{{ $doctor->department->name }}</div>
            <div style="font-size:0.82rem;color:#5a8a6e;margin-top:4px;">{{ $doctor->experience_years }} yrs · ⭐ {{ number_format($doctor->rating,1) }}</div>
            @auth
                @if(Auth::user()->role !== 'doctor')
                    <div style="margin-top:16px;">
                        <a href="{{ route('appointments.book') }}" class="btn btn-green" style="font-size:0.85rem;padding:7px 20px;">Book</a>
                    </div>
                @endif
            @else
                <div style="margin-top:16px;">
                    <a href="{{ route('appointments.book') }}" class="btn btn-green" style="font-size:0.85rem;padding:7px 20px;">Book</a>
                </div>
            @endauth
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection