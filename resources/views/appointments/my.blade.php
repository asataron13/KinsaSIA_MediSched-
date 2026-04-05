@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1>My Appointments</h1>
    <p>Track the status of all your booked appointments here.</p>
</div>

<div style="margin-bottom:20px;">
    <a href="{{ route('appointments.book') }}" class="btn btn-green">+ New Appointment</a>
</div>

@if($appointments->isEmpty())
    <div class="card" style="text-align:center;padding:60px;">
        <p style="color:#5a8a6e;font-size:1rem;margin-bottom:18px;">You haven't booked any appointments yet.</p>
        <a href="{{ route('appointments.book') }}" class="btn btn-green">Book Now</a>
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

                    // Cancel is blocked ONLY when BOTH conditions are true at the same time
                    $cancelLocked  = $isAccepted && $isWithin3Days;

                    // Appointments that are fully done — show nothing
                    $isDone = in_array($appt->status, ['Completed', 'Cancelled']);

                    $canCancel = !$isDone && !$cancelLocked;
                @endphp
                <tr>
                    <td>Dr. {{ $appt->doctor->first_name }} {{ $appt->doctor->last_name }}</td>
                    <td>{{ $appt->department->name }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}
                        @if($cancelLocked)
                            <div style="font-size:0.75rem;color:#e74c3c;font-weight:600;margin-top:2px;">
                                ⚠ {{ $daysUntil <= 0 ? 'Today or past' : $daysUntil . ' day' . ($daysUntil === 1 ? '' : 's') . ' away' }}
                            </div>
                        @endif
                    </td>
                    <td>{{ $appt->appointment_time ? \Carbon\Carbon::parse($appt->appointment_time)->format('h:i A') : '—' }}</td>
                    <td>{{ $appt->type }}</td>
                    <td><span class="badge badge-{{ $s }}">{{ $appt->status }}</span></td>
                    <td>
                        @if($canCancel)
                            <form method="POST" action="{{ route('appointments.cancel', $appt) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-red"
                                        style="font-size:0.82rem;padding:5px 14px;"
                                        onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                    Cancel
                                </button>
                            </form>
                        @elseif($isDone)
                            <span style="color:#aaa;font-size:0.85rem;">—</span>
                        @else
                            {{-- Locked: accepted AND within 3 days --}}
                            <span style="display:inline-block;padding:5px 14px;border-radius:8px;
                                         background:#fdecea;color:#c0392b;font-size:0.82rem;
                                         font-weight:600;cursor:not-allowed;"
                                  title="Cannot cancel: accepted by doctor and within 3 days">
                                Locked
                            </span>
                            <div style="font-size:0.75rem;color:#c0392b;margin-top:3px;line-height:1.3;">
                                Accepted &amp; within 3 days
                            </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Policy note --}}
    <div style="margin-top:16px;padding:14px 18px;background:white;border-radius:10px;
                box-shadow:0 2px 8px rgba(0,0,0,0.05);font-size:0.83rem;color:#5a8a6e;">
        <strong style="color:#3a5a48;">Cancellation policy:</strong>
        You may cancel an appointment at any time <em>unless</em> it has already been
        <strong>accepted by your doctor</strong> <em>and</em> the appointment date is
        <strong>within 3 days</strong> of today. Both conditions must apply together for cancellation to be locked.
    </div>
@endif

@endsection