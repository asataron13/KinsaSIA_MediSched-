@extends('layouts.app')

@section('content')

<div style="max-width:1200px;margin:0 auto;display:flex;flex-direction:column;gap:36px;">

    {{-- HERO --}}
    <div style="
        text-align:center;
        padding:80px 28px;
        background:linear-gradient(135deg,#27ae60 0%,#1a7a42 100%);
        border-radius:22px;
        color:white;
        position:relative;
        overflow:hidden;
        box-shadow:0 20px 40px rgba(0,0,0,0.08);
    ">
        <p style="font-size:0.85rem;letter-spacing:0.18em;text-transform:uppercase;opacity:0.8;margin-bottom:12px;">
            MediSched General Hospital
        </p>

        <h1 style="font-size:2.7rem;font-weight:800;line-height:1.2;max-width:640px;margin:0 auto;">
            Your Health, Scheduled with Care
        </h1>

        <p style="margin-top:16px;opacity:0.9;font-size:1.05rem;max-width:520px;margin-left:auto;margin-right:auto;">
            Book consultations with our specialist doctors online — fast, easy, and from anywhere.
        </p>

        <div style="margin-top:36px;display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
            @auth
                @if(Auth::user()->role === 'doctor')
                    <a href="{{ route('doctor.queue') }}" class="btn btn-green" style="background:white;color:#1a7a42;">
                        View Patient Queue
                    </a>
                    <a href="{{ route('doctors.index') }}" class="btn"
                       style="
                           border:2px solid white;
                           color:white !important;
                           background:transparent;
                           min-width:180px;
                           font-weight:700;
                       ">
                        Doctors List
                    </a>
                @else
                    <a href="{{ route('appointments.book') }}" class="btn btn-green" style="background:white;color:#1a7a42;">
                        Book an Appointment
                    </a>
                    <a href="{{ route('doctors.index') }}" class="btn"
                       style="
                           border:2px solid white;
                           color:white !important;
                           background:transparent;
                           min-width:180px;
                           font-weight:700;
                       ">
                        View Doctors
                    </a>
                @endif
            @else
                <a href="{{ route('appointments.book') }}" class="btn btn-green" style="background:white;color:#1a7a42;">
                    Book an Appointment
                </a>
                <a href="{{ route('doctors.index') }}" class="btn"
                   style="
                       border:2px solid white;
                       color:white !important;
                       background:transparent;
                       min-width:180px;
                       font-weight:700;
                   ">
                    View Doctors
                </a>
            @endauth
        </div>
    </div>

    {{-- HOW IT WORKS --}}
    <div>
        <h2 style="font-size:1.6rem;font-weight:800;text-align:center;margin-bottom:26px;">
            How It Works
        </h2>

        <div class="grid-steps">
            @foreach([
                ['01','Create an Account','Sign up with your email or phone number.'],
                ['02','Choose a Doctor','Find the right specialist for you.'],
                ['03','Book a Slot','Pick your preferred schedule.'],
                ['04','Get Confirmed','Receive confirmation instantly.'],
            ] as [$num,$title,$desc])
            <div class="card step-card">
                <div class="step-circle">{{ $num }}</div>
                <div class="step-title">{{ $title }}</div>
                <div class="step-desc">{{ $desc }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- DEPARTMENTS --}}
    @if($departments->isNotEmpty())
    <div>
        <h2 style="font-size:1.6rem;font-weight:800;margin-bottom:18px;">
            Our Departments
        </h2>

        <div class="grid-dept">
            @foreach($departments as $dept)
            <div class="card dept-card">
                <div class="dept-name">{{ $dept->name }}</div>
                @if($dept->description)
                    <div class="dept-desc">{{ $dept->description }}</div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- DOCTORS --}}
    @if($doctors->isNotEmpty())
    <div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;">
            <h2 style="font-size:1.6rem;font-weight:800;">Meet Our Doctors</h2>
            <a href="{{ route('doctors.index') }}" class="btn btn-outline btn-sm">See all →</a>
        </div>

        <div class="grid-doctors">
            @foreach($doctors->take(4) as $doctor)
            <div class="card doctor-card">

                <div class="doctor-avatar">
                    {{ strtoupper(substr($doctor->first_name,0,1).substr($doctor->last_name,0,1)) }}
                </div>

                <div class="doctor-name">
                    Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                </div>

                <div class="doctor-dept">
                    {{ $doctor->department->name }}
                </div>

                <div class="doctor-meta">
                    {{ $doctor->experience_years }} yrs · ⭐ {{ number_format($doctor->rating,1) }}
                </div>

                <div style="margin-top:14px;">
                    <a href="{{ route('appointments.book') }}" class="btn btn-green btn-sm">
                        Book
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

<style>
.grid-steps{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}

.step-card{
    text-align:center;
    padding:26px;
    transition:transform .2s ease, box-shadow .2s ease;
}
.step-card:hover{
    transform:translateY(-4px);
    box-shadow:0 14px 28px rgba(0,0,0,0.08);
}
.step-circle{
    width:46px;height:46px;
    border-radius:50%;
    background:#eafaf1;
    color:#27ae60;
    font-weight:800;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 12px;
}
.step-title{font-weight:700;}
.step-desc{font-size:0.85rem;color:#5a8a6e;margin-top:6px;}

.grid-dept{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
    gap:16px;
}
.dept-card{
    padding:20px;
}
.dept-name{font-weight:700;margin-bottom:6px;}
.dept-desc{font-size:0.85rem;color:#5a8a6e;}

.grid-doctors{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(230px,1fr));
    gap:18px;
}
.doctor-card{
    text-align:center;
    padding:26px;
}
.doctor-avatar{
    width:62px;height:62px;
    border-radius:50%;
    background:#27ae60;
    color:white;
    font-weight:800;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 12px;
}
.doctor-name{font-weight:700;}
.doctor-dept{color:#27ae60;font-size:0.85rem;}
.doctor-meta{font-size:0.82rem;color:#5a8a6e;margin-top:4px;}
</style>

@endsection
