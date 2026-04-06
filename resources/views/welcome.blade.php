@extends('layouts.app')

@section('title', 'Welcome')

@section('content')

<div style="display: flex; flex-direction: column; gap: 36px;">

    <!-- HERO SECTION -->
    <section class="card" style="
        background: linear-gradient(135deg, var(--green-main), var(--green-dark));
        color: white;
        text-align: center;
        padding: 70px 30px;
        border-radius: 22px;
        box-shadow: 0 10px 30px rgba(26, 122, 66, 0.18);
    ">
        <div style="max-width: 760px; margin: 0 auto;">
            <img
                src="{{ asset('images/logo.png') }}"
                alt="MediSched Logo"
                style="width: 78px; height: 78px; object-fit: contain; margin: 0 auto 18px;"
            >

            <p style="
                letter-spacing: 3px;
                font-size: 0.88rem;
                opacity: 0.92;
                margin-bottom: 14px;
            ">
                MEDISCHED GENERAL HOSPITAL
            </p>

            <h1 style="
                font-size: clamp(2.2rem, 5vw, 3.4rem);
                font-weight: 800;
                line-height: 1.15;
                margin-bottom: 18px;
            ">
                Your Health, Scheduled with Care
            </h1>

            <p style="
                max-width: 620px;
                margin: 0 auto 34px;
                font-size: 1.05rem;
                line-height: 1.7;
                opacity: 0.94;
            ">
                Book consultations with our specialist doctors online — fast, easy,
                and from anywhere.
            </p>

            <div style="
                display: flex;
                justify-content: center;
                gap: 14px;
                flex-wrap: wrap;
            ">
                <a
                    href="{{ route('appointments.book') }}"
                    class="btn"
                    style="
                        background: white;
                        color: var(--green-dark);
                        font-weight: 800;
                        min-width: 210px;
                    "
                >
                    Book an Appointment
                </a>

                <a
                    href="{{ route('doctors.index') }}"
                    class="btn"
                    style="
                        border: 2px solid rgba(255,255,255,0.85);
                        color: white;
                        min-width: 180px;
                    "
                >
                    View Doctors
                </a>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section>
        <div style="text-align: center; margin-bottom: 24px;">
            <h2 style="
                font-size: 2rem;
                font-weight: 800;
                color: var(--text-dark);
                margin-bottom: 8px;
            ">
                How It Works
            </h2>
            <p style="
                color: var(--text-muted);
                font-size: 1rem;
                max-width: 560px;
                margin: 0 auto;
            ">
                A simple and convenient way to schedule your appointment with the right specialist.
            </p>
        </div>

        <div style="
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 22px;
        ">
            <div class="card" style="text-align: center; border-top: 4px solid var(--green-main);">
                <div style="
                    background: var(--green-light);
                    color: var(--green-dark);
                    width: 52px;
                    height: 52px;
                    margin: 0 auto 16px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: 800;
                    font-size: 1rem;
                ">
                    01
                </div>
                <h3 style="font-size: 1.1rem; font-weight: 800; margin-bottom: 10px;">Create an Account</h3>
                <p style="color: var(--text-muted); line-height: 1.7; font-size: 0.96rem;">
                    Sign up using your email in seconds and access the appointment system anytime.
                </p>
            </div>

            <div class="card" style="text-align: center; border-top: 4px solid var(--green-main);">
                <div style="
                    background: var(--green-light);
                    color: var(--green-dark);
                    width: 52px;
                    height: 52px;
                    margin: 0 auto 16px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: 800;
                    font-size: 1rem;
                ">
                    02
                </div>
                <h3 style="font-size: 1.1rem; font-weight: 800; margin-bottom: 10px;">Choose a Doctor</h3>
                <p style="color: var(--text-muted); line-height: 1.7; font-size: 0.96rem;">
                    Browse available specialists and find the doctor that best fits your needs.
                </p>
            </div>

            <div class="card" style="text-align: center; border-top: 4px solid var(--green-main);">
                <div style="
                    background: var(--green-light);
                    color: var(--green-dark);
                    width: 52px;
                    height: 52px;
                    margin: 0 auto 16px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: 800;
                    font-size: 1rem;
                ">
                    03
                </div>
                <h3 style="font-size: 1.1rem; font-weight: 800; margin-bottom: 10px;">Book a Slot</h3>
                <p style="color: var(--text-muted); line-height: 1.7; font-size: 0.96rem;">
                    Select your preferred date and time for a faster and smoother consultation process.
                </p>
            </div>

            <div class="card" style="text-align: center; border-top: 4px solid var(--green-main);">
                <div style="
                    background: var(--green-light);
                    color: var(--green-dark);
                    width: 52px;
                    height: 52px;
                    margin: 0 auto 16px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: 800;
                    font-size: 1rem;
                ">
                    04
                </div>
                <h3 style="font-size: 1.1rem; font-weight: 800; margin-bottom: 10px;">Get Confirmed</h3>
                <p style="color: var(--text-muted); line-height: 1.7; font-size: 0.96rem;">
                    Receive confirmation and stay updated on your scheduled appointment.
                </p>
            </div>
        </div>
    </section>

</div>

@endsection
