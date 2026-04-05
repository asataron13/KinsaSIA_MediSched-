@extends('layouts.app')

@section('content')

<div style="max-width:580px;margin:40px auto;">
    <div class="card" style="text-align:center;padding:48px 40px;">

        {{-- Check icon --}}
        <div style="width:76px;height:76px;border-radius:50%;background:#d5f5e3;display:flex;align-items:center;justify-content:center;margin:0 auto 22px;">
            <svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="#27ae60" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
        </div>

        <h2 style="font-size:1.7rem;font-weight:800;margin-bottom:8px;">Appointment Submitted!</h2>
        <p style="color:#5a8a6e;margin-bottom:32px;font-size:0.96rem;">
            Your appointment is now <strong>pending review</strong>. Our team will confirm it shortly.
        </p>

        {{-- Summary --}}
        <div style="background:#f4faf6;border-radius:12px;padding:24px;text-align:left;margin-bottom:30px;">
            <h4 style="font-size:0.9rem;text-transform:uppercase;letter-spacing:0.06em;color:#3a5a48;margin-bottom:18px;font-weight:800;">Appointment Summary</h4>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">

                <div>
                    <div style="font-size:0.75rem;text-transform:uppercase;color:#5a8a6e;font-weight:700;margin-bottom:3px;">Patient</div>
                    <div style="font-weight:700;">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</div>
                </div>

                <div>
                    <div style="font-size:0.75rem;text-transform:uppercase;color:#5a8a6e;font-weight:700;margin-bottom:3px;">Patient Code</div>
                    <div style="font-weight:700;color:#27ae60;">{{ $appointment->patient->patient_code }}</div>
                </div>

                <div>
                    <div style="font-size:0.75rem;text-transform:uppercase;color:#5a8a6e;font-weight:700;margin-bottom:3px;">Doctor</div>
                    <div style="font-weight:700;">Dr. {{ $appointment->doctor->first_name }} {{ $appointment->doctor->last_name }}</div>
                </div>

                <div>
                    <div style="font-size:0.75rem;text-transform:uppercase;color:#5a8a6e;font-weight:700;margin-bottom:3px;">Department</div>
                    <div style="font-weight:700;">{{ $appointment->department->name }}</div>
                </div>

                <div>
                    <div style="font-size:0.75rem;text-transform:uppercase;color:#5a8a6e;font-weight:700;margin-bottom:3px;">Date</div>
                    <div style="font-weight:700;">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}</div>
                </div>

                <div>
                    <div style="font-size:0.75rem;text-transform:uppercase;color:#5a8a6e;font-weight:700;margin-bottom:3px;">Type</div>
                    <div style="font-weight:700;">{{ $appointment->type }}</div>
                </div>

            </div>

            <div style="margin-top:16px;padding-top:16px;border-top:1px solid #c8e6d4;">
                <div style="font-size:0.75rem;text-transform:uppercase;color:#5a8a6e;font-weight:700;margin-bottom:5px;">Symptoms / Notes</div>
                <div style="font-size:0.92rem;color:#1c2b22;">{{ $appointment->symptoms }}</div>
            </div>

            <div style="text-align:center;margin-top:16px;">
                <span class="badge badge-pending">{{ $appointment->status }}</span>
            </div>
        </div>

        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('appointments.my') }}" class="btn btn-green" style="padding:10px 28px;">View My Appointments</a>
            <a href="{{ route('home') }}" class="btn btn-outline" style="padding:10px 28px;">Back to Home</a>
        </div>

    </div>
</div>

@endsection