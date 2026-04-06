@extends('layouts.app')

@section('content')

<div style="max-width:1100px;margin:0 auto;display:flex;flex-direction:column;gap:22px;">

    <div class="page-header">
        <h1>My Appointments</h1>
        <p>Track the status of all your booked appointments here.</p>
    </div>

    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;">
        <div></div>
        <a href="{{ route('appointments.book') }}" class="btn btn-green">+ New Appointment</a>
    </div>

    @if($appointments->isEmpty())
        <div class="card empty-state">
            <div class="empty-icon">📅</div>
            <p>You haven't booked any appointments yet.</p>
            <div style="margin-top:16px;">
                <a href="{{ route('appointments.book') }}" class="btn btn-green">Book Now</a>
            </div>
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Department</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appt)
                    @php
                        $s             = strtolower(str_replace(' ', '', $appt->status));
                        $daysUntil     = \Carbon\Carbon::today()->diffInDays(\Carbon\Carbon::parse($appt->appointment_date), false);
                        $isAccepted    = $appt->status === 'Confirmed';
                        $isWithin3Days = $daysUntil <= 3;
                        $cancelLocked  = $isAccepted && $isWithin3Days;
                        $isDone        = in_array($appt->status, ['Completed', 'Cancelled']);
                        $canCancel     = !$isDone && !$cancelLocked;
                    @endphp

                    <tr>
                        <td style="font-weight:600;">
                            Dr. {{ $appt->doctor->first_name }} {{ $appt->doctor->last_name }}
                        </td>

                        <td>{{ $appt->department->name }}</td>

                        <td>
                            {{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}

                            @if($cancelLocked)
                                <div style="
                                    margin-top:4px;
                                    font-size:0.75rem;
                                    color:#e74c3c;
                                    font-weight:600;
                                ">
                                    ⚠ {{ $daysUntil <= 0 ? 'Today or past' : $daysUntil . ' day' . ($daysUntil === 1 ? '' : 's') . ' away' }}
                                </div>
                            @endif
                        </td>

                        <td>
                            {{ $appt->appointment_time ? \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') : '—' }}
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

                        <td>
                            <span class="badge badge-{{ $s }}">{{ $appt->status }}</span>
                        </td>

                        <td>
                            @if($canCancel)
                                <form method="POST" action="{{ route('appointments.cancel', $appt) }}">
                                    @csrf @method('PATCH')
                                    <button
                                        type="submit"
                                        class="btn btn-red btn-sm"
                                        onclick="return confirm('Are you sure you want to cancel this appointment?')"
                                    >
                                        Cancel
                                    </button>
                                </form>

                            @elseif($isDone)
                                <span style="color:#aaa;font-size:0.85rem;">—</span>

                            @else
                                <div style="text-align:center;">
                                    <span style="
                                        display:inline-block;
                                        padding:6px 14px;
                                        border-radius:8px;
                                        background:#fdecea;
                                        color:#c0392b;
                                        font-size:0.82rem;
                                        font-weight:600;
                                        cursor:not-allowed;
                                    ">
                                        Locked
                                    </span>

                                    <div style="
                                        font-size:0.72rem;
                                        color:#c0392b;
                                        margin-top:3px;
                                    ">
                                        Accepted & within 3 days
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Policy --}}
        <div class="card" style="font-size:0.85rem;color:#5a8a6e;">
            <strong style="color:#3a5a48;">Cancellation policy:</strong>
            You may cancel anytime <em>unless</em> the appointment is
            <strong>accepted</strong> and within <strong>3 days</strong>.
        </div>
    @endif

</div>

@endsection
