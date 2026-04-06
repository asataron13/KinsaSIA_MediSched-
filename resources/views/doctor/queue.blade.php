@extends('layouts.app')

@section('content')

<div style="max-width:1200px;margin:0 auto;display:flex;flex-direction:column;gap:22px;">

    <div class="page-header">
        <h1>Patient Queue</h1>
        <p>
            Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
            &middot; {{ $doctor->department->name }}
            &middot; Pending appointment requests
        </p>
    </div>

    @if($appointments->isEmpty())
        <div class="card empty-state">
            <div class="empty-icon">📭</div>
            <p>No pending appointment requests right now.</p>
            <p style="margin-top:6px;font-size:0.88rem;color:#a0b8a8;">
                Check back later or view your accepted patients.
            </p>
            <div style="margin-top:20px;">
                <a href="{{ route('doctor.accepted') }}" class="btn btn-outline">View My Patients →</a>
            </div>
        </div>
    @else
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
            <div style="
                background:#fef9e7;
                color:#9a7d0a;
                font-size:0.9rem;
                font-weight:800;
                padding:8px 16px;
                border-radius:999px;
                border:1px solid #f3df9a;
            ">
                {{ $appointments->count() }} pending {{ $appointments->count() === 1 ? 'request' : 'requests' }}
            </div>

            <a href="{{ route('doctor.accepted') }}" class="btn btn-outline btn-sm">View My Patients</a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Code</th>
                        <th>Date Requested</th>
                        <th>Preferred Date</th>
                        <th>Type</th>
                        <th>Symptoms / Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appt)
                        <tr>
                            <td>
                                <div style="font-weight:700;color:var(--text-dark);">
                                    {{ $appt->patient->first_name }} {{ $appt->patient->last_name }}
                                </div>
                                <div style="font-size:0.8rem;color:#5a8a6e;margin-top:3px;">
                                    {{ $appt->patient->gender }} &middot; {{ \Carbon\Carbon::parse($appt->patient->birth_date)->age }} yrs
                                </div>
                            </td>

                            <td>
                                <span style="
                                    display:inline-block;
                                    font-family:monospace;
                                    font-size:0.82rem;
                                    color:#27ae60;
                                    font-weight:800;
                                    background:#effaf4;
                                    padding:5px 10px;
                                    border-radius:999px;
                                ">
                                    {{ $appt->patient->patient_code }}
                                </span>
                            </td>

                            <td style="font-size:0.88rem;color:#5a8a6e;">
                                {{ $appt->created_at->format('M d, Y') }}<br>
                                <span style="font-size:0.8rem;">{{ $appt->created_at->diffForHumans() }}</span>
                            </td>

                            <td>
                                <div style="font-weight:700;color:var(--text-dark);">
                                    {{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}
                                </div>
                                <div style="font-size:0.8rem;color:#5a8a6e;margin-top:3px;">
                                    Requested schedule
                                </div>
                            </td>

                            <td>
                                <span style="
                                    background:#f4faf6;
                                    padding:5px 10px;
                                    border-radius:999px;
                                    font-size:0.82rem;
                                    font-weight:700;
                                    color:#2f5e45;
                                ">
                                    {{ $appt->type }}
                                </span>
                            </td>

                            <td style="max-width:240px;">
                                <div style="
                                    overflow:hidden;
                                    text-overflow:ellipsis;
                                    white-space:nowrap;
                                    font-size:0.88rem;
                                    color:#3a5a48;
                                " title="{{ $appt->symptoms }}">
                                    {{ $appt->symptoms }}
                                </div>
                            </td>

                            <td>
                                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                    <form method="POST" action="{{ route('doctor.appointments.accept', $appt) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-green btn-sm">
                                            ✓ Accept
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('doctor.appointments.decline', $appt) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button
                                            type="submit"
                                            class="btn btn-red btn-sm"
                                            onclick="return confirm('Decline this appointment request?')"
                                        >
                                            ✕ Decline
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>

@endsection
