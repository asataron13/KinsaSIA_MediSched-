@extends('layouts.admin')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stat Cards --}}
<div class="stat-grid">
    <div class="stat-card" style="border-top-color:#27ae60;">
        <div class="stat-value">{{ $totalPatients }}</div>
        <div class="stat-label">Total Patients</div>
    </div>
    <div class="stat-card" style="border-top-color:#2980b9;">
        <div class="stat-value">{{ $totalDoctors }}</div>
        <div class="stat-label">Total Doctors</div>
    </div>
    <div class="stat-card" style="border-top-color:#f39c12;">
        <div class="stat-value">{{ $pendingBookings }}</div>
        <div class="stat-label">Pending Bookings</div>
    </div>
    <div class="stat-card" style="border-top-color:#27ae60;">
        <div class="stat-value">{{ $confirmedCount }}</div>
        <div class="stat-label">Confirmed</div>
    </div>
    <div class="stat-card" style="border-top-color:#8e44ad;">
        <div class="stat-value">{{ $todayAppointments }}</div>
        <div class="stat-label">Today's Appointments</div>
    </div>
    <div class="stat-card" style="border-top-color:#555;">
        <div class="stat-value">{{ $completedCount }}</div>
        <div class="stat-label">Completed</div>
    </div>
    <div class="stat-card" style="border-top-color:#e74c3c;">
        <div class="stat-value">{{ $cancelledCount }}</div>
        <div class="stat-label">Cancelled</div>
    </div>
    <div class="stat-card" style="border-top-color:#1a5276;">
        <div class="stat-value">{{ $inProgressCount }}</div>
        <div class="stat-label">In Progress</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:22px;">

    {{-- Recent Appointments --}}
    <div class="card">
        <div class="card-header">
            <h3>Recent Appointments</h3>
            <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline btn-sm">View all →</a>
        </div>
        @if($recentAppointments->isEmpty())
            <p style="color:#5a8a6e;font-size:0.9rem;">No appointments yet.</p>
        @else
            <table>
                <thead>
                    <tr><th>Patient</th><th>Doctor</th><th>Date</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @foreach($recentAppointments as $a)
                    @php $s = strtolower(str_replace(' ','',$a->status)); @endphp
                    <tr>
                        <td style="font-weight:600;">{{ $a->patient->first_name }} {{ $a->patient->last_name }}</td>
                        <td>Dr. {{ $a->doctor->last_name }}</td>
                        <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($a->appointment_date)->format('M d, Y') }}</td>
                        <td><span class="badge badge-{{ $s }}">{{ $a->status }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Pending Approvals --}}
    <div class="card">
        <div class="card-header">
            <h3>Pending Bookings</h3>
            <a href="{{ route('admin.appointments.index', ['status' => 'Pending']) }}" class="btn btn-outline btn-sm">Manage →</a>
        </div>
        @if($pendingAppointments->isEmpty())
            <p style="color:#5a8a6e;font-size:0.9rem;">No pending bookings.</p>
        @else
            @foreach($pendingAppointments as $a)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #eef4f0;">
                <div>
                    <div style="font-weight:600;font-size:0.92rem;">{{ $a->patient->first_name }} {{ $a->patient->last_name }}</div>
                    <div style="font-size:0.8rem;color:#5a8a6e;">{{ $a->department->name }} · Dr. {{ $a->doctor->last_name }} · {{ \Carbon\Carbon::parse($a->appointment_date)->format('M d') }}</div>
                </div>
                <span class="badge badge-pending">Pending</span>
            </div>
            @endforeach
        @endif
    </div>

</div>

@endsection