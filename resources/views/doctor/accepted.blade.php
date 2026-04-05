@extends('layouts.app')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:28px;flex-wrap:wrap;gap:14px;">
    <div class="page-header" style="margin-bottom:0;">
        <h1>My Patients</h1>
        <p>Dr. {{ $doctor->first_name }} {{ $doctor->last_name }} &middot; Accepted & active appointments</p>
    </div>
    <a href="{{ route('doctor.queue') }}" class="btn btn-outline" style="white-space:nowrap;">
        ← Back to Queue
    </a>
</div>

@if($appointments->isEmpty())
    <div class="card empty-state">
        <div class="empty-icon">🩺</div>
        <p>You haven't accepted any appointments yet.</p>
        <div style="margin-top:20px;">
            <a href="{{ route('doctor.queue') }}" class="btn btn-green">View Patient Queue →</a>
        </div>
    </div>
@else
    {{-- Status summary pills --}}
    @php
        $confirmed   = $appointments->where('status', 'Confirmed')->count();
        $inProgress  = $appointments->where('status', 'In Progress')->count();
        $completed   = $appointments->where('status', 'Completed')->count();
    @endphp
    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:22px;">
        @if($confirmed)
            <span class="badge badge-confirmed" style="font-size:0.85rem;padding:6px 16px;">{{ $confirmed }} Confirmed</span>
        @endif
        @if($inProgress)
            <span class="badge badge-inprogress" style="font-size:0.85rem;padding:6px 16px;">{{ $inProgress }} In Progress</span>
        @endif
        @if($completed)
            <span class="badge badge-completed" style="font-size:0.85rem;padding:6px 16px;">{{ $completed }} Completed</span>
        @endif
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Code</th>
                    <th>Preferred Date</th>
                    <th>Type</th>
                    <th>Symptoms / Notes</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appt)
                @php $s = strtolower(str_replace(' ', '', $appt->status)); @endphp
                <tr>
                    <td>
                        <div style="font-weight:700;">{{ $appt->patient->first_name }} {{ $appt->patient->last_name }}</div>
                        <div style="font-size:0.8rem;color:#5a8a6e;">
                            {{ $appt->patient->gender }} &middot; {{ \Carbon\Carbon::parse($appt->patient->birth_date)->age }} yrs
                        </div>
                    </td>
                    <td>
                        <span style="font-family:monospace;font-size:0.85rem;color:#27ae60;font-weight:700;">
                            {{ $appt->patient->patient_code }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight:600;">{{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}</div>
                        @if($appt->appointment_time)
                            <div style="font-size:0.8rem;color:#5a8a6e;">{{ \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') }}</div>
                        @endif
                    </td>
                    <td>{{ $appt->type }}</td>
                    <td style="max-width:220px;">
                        <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:0.88rem;color:#3a5a48;"
                             title="{{ $appt->symptoms }}">
                            {{ $appt->symptoms }}
                        </div>
                    </td>
                    <td><span class="badge badge-{{ $s }}">{{ $appt->status }}</span></td>
                    <td>
                        <div style="display:flex;gap:8px;flex-wrap:wrap;">
                            @if($appt->status === 'Confirmed')
                                {{-- Advance to In Progress --}}
                                <form method="POST" action="{{ route('doctor.appointments.advance', $appt) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-blue" style="font-size:0.82rem;padding:6px 14px;">
                                        Start Session
                                    </button>
                                </form>
                                {{-- Decline / Cancel --}}
                                <form method="POST" action="{{ route('doctor.appointments.decline', $appt) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-red" style="font-size:0.82rem;padding:6px 14px;"
                                            onclick="return confirm('Cancel this appointment?')">
                                        Cancel
                                    </button>
                                </form>
                            @elseif($appt->status === 'In Progress')
                                {{-- Advance to Completed --}}
                                <form method="POST" action="{{ route('doctor.appointments.advance', $appt) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-green" style="font-size:0.82rem;padding:6px 14px;">
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

@endsection