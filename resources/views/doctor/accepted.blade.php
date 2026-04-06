@extends('layouts.app')

@section('content')

<div style="max-width:1200px;margin:0 auto;display:flex;flex-direction:column;gap:22px;">

    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:14px;">
        <div class="page-header" style="margin-bottom:0;">
            <h1>My Patients</h1>
            <p>
                Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}
                &middot; Active appointments
            </p>
        </div>

        <a href="{{ route('doctor.queue') }}" class="btn btn-outline">
            ← Back to Queue
        </a>
    </div>

    @if($appointments->isEmpty())
        <div class="card empty-state">
            <div class="empty-icon">🩺</div>
            <p>You haven't accepted any appointments yet.</p>

            <div style="margin-top:20px;">
                <a href="{{ route('doctor.queue') }}" class="btn btn-green">
                    View Patient Queue →
                </a>
            </div>
        </div>
    @else

        {{-- STATUS SUMMARY --}}
        @php
            $confirmed  = $appointments->where('status', 'Confirmed')->count();
            $inProgress = $appointments->where('status', 'In Progress')->count();
            $completed  = $appointments->where('status', 'Completed')->count();
        @endphp

        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            @if($confirmed)
                <span class="badge badge-confirmed" style="padding:7px 16px;">{{ $confirmed }} Confirmed</span>
            @endif
            @if($inProgress)
                <span class="badge badge-inprogress" style="padding:7px 16px;">{{ $inProgress }} In Progress</span>
            @endif
            @if($completed)
                <span class="badge badge-completed" style="padding:7px 16px;">{{ $completed }} Completed</span>
            @endif
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Code</th>
                        <th>Schedule</th>
                        <th>Type</th>
                        <th>Notes</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($appointments as $appt)
                        @php $s = strtolower(str_replace(' ', '', $appt->status)); @endphp

                        <tr>

                            <td>
                                <div style="font-weight:700;color:var(--text-dark);">
                                    {{ $appt->patient->first_name }} {{ $appt->patient->last_name }}
                                </div>

                                <div style="font-size:0.8rem;color:#5a8a6e;margin-top:3px;">
                                    {{ $appt->patient->gender }}
                                    &middot;
                                    {{ \Carbon\Carbon::parse($appt->patient->birth_date)->age }} yrs
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

                            <td>
                                <div style="font-weight:700;">
                                    {{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}
                                </div>

                                @if($appt->appointment_time)
                                    <div style="font-size:0.8rem;color:#5a8a6e;">
                                        {{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}
                                    </div>
                                @endif
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
                                <span class="badge badge-{{ $s }}">
                                    {{ $appt->status }}
                                </span>
                            </td>

                            <td>
                                <div style="display:flex;gap:8px;flex-wrap:wrap;">

                                    @if($appt->status === 'Confirmed')
                                        <form method="POST" action="{{ route('doctor.appointments.advance', $appt) }}">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-blue btn-sm">
                                                Start Session
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('doctor.appointments.decline', $appt) }}">
                                            @csrf @method('PATCH')
                                            <button
                                                class="btn btn-red btn-sm"
                                                onclick="return confirm('Cancel this appointment?')"
                                            >
                                                Cancel
                                            </button>
                                        </form>

                                    @elseif($appt->status === 'In Progress')
                                        <form method="POST" action="{{ route('doctor.appointments.advance', $appt) }}">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-green btn-sm">
                                                Mark Complete
                                            </button>
                                        </form>

                                    @else
                                        <span style="color:#aaa;font-size:0.85rem;">—</span>
                                    @endif

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
