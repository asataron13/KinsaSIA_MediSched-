@extends('layouts.admin')
@section('page-title', 'Dashboard')

@section('content')

<div style="display: flex; flex-direction: column; gap: 26px;">

    <div class="page-header" style="margin-bottom: 0;">
        <h1>Dashboard Overview</h1>
        <p>Monitor patients, doctors, appointments, and booking activity in one place.</p>
    </div>

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

    <div class="dashboard-panels">

        {{-- Recent Appointments --}}
        <div class="card dashboard-card">
            <div class="card-header">
                <div>
                    <h3>Recent Appointments</h3>
                    <p class="card-subtext">Latest bookings and current appointment statuses.</p>
                </div>
                <a href="{{ route('admin.appointments.index') }}" class="btn btn-outline btn-sm">View all</a>
            </div>

            @if($recentAppointments->isEmpty())
                <div class="empty-block">
                    <p>No appointments yet.</p>
                </div>
            @else
                <div class="table-wrap" style="box-shadow:none;border:1px solid #eef4f0;">
                    <table>
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentAppointments as $a)
                                @php $s = strtolower(str_replace(' ','',$a->status)); @endphp
                                <tr>
                                    <td style="font-weight:700;">
                                        {{ $a->patient->first_name }} {{ $a->patient->last_name }}
                                    </td>
                                    <td>Dr. {{ $a->doctor->last_name }}</td>
                                    <td style="white-space:nowrap;">
                                        {{ \Carbon\Carbon::parse($a->appointment_date)->format('M d, Y') }}
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $s }}">{{ $a->status }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Pending Approvals --}}
        <div class="card dashboard-card">
            <div class="card-header">
                <div>
                    <h3>Pending Bookings</h3>
                    <p class="card-subtext">Appointments waiting for review and confirmation.</p>
                </div>
                <a href="{{ route('admin.appointments.index', ['status' => 'Pending']) }}" class="btn btn-outline btn-sm">Manage</a>
            </div>

            @if($pendingAppointments->isEmpty())
                <div class="empty-block">
                    <p>No pending bookings.</p>
                </div>
            @else
                <div class="pending-list">
                    @foreach($pendingAppointments as $a)
                        <div class="pending-item">
                            <div>
                                <div class="pending-name">
                                    {{ $a->patient->first_name }} {{ $a->patient->last_name }}
                                </div>
                                <div class="pending-meta">
                                    {{ $a->department->name }} · Dr. {{ $a->doctor->last_name }} ·
                                    {{ \Carbon\Carbon::parse($a->appointment_date)->format('M d') }}
                                </div>
                            </div>
                            <span class="badge badge-pending">Pending</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>

<style>
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 18px;
    }

    .stat-card {
        background: linear-gradient(180deg, #ffffff 0%, #fbfffc 100%);
        border-radius: 18px;
        padding: 22px 18px;
        border-top: 5px solid var(--green-main);
        box-shadow: 0 8px 22px rgba(0, 0, 0, 0.06);
        border-left: 1px solid rgba(26, 122, 66, 0.06);
        border-right: 1px solid rgba(26, 122, 66, 0.06);
        border-bottom: 1px solid rgba(26, 122, 66, 0.06);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 28px rgba(0, 0, 0, 0.08);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .stat-label {
        color: var(--text-muted);
        font-size: 0.92rem;
        font-weight: 600;
    }

    .dashboard-panels {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 22px;
    }

    .dashboard-card {
        padding: 24px;
    }

    .card-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 18px;
        flex-wrap: wrap;
    }

    .card-header h3 {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--text-dark);
        margin: 0;
    }

    .card-subtext {
        margin-top: 4px;
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .empty-block {
        padding: 28px 8px 10px;
    }

    .empty-block p {
        color: var(--text-muted);
        font-size: 0.94rem;
    }

    .pending-list {
        display: flex;
        flex-direction: column;
    }

    .pending-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        padding: 14px 0;
        border-bottom: 1px solid #eef4f0;
    }

    .pending-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .pending-name {
        font-weight: 700;
        font-size: 0.96rem;
        color: var(--text-dark);
        margin-bottom: 4px;
    }

    .pending-meta {
        font-size: 0.84rem;
        color: var(--text-muted);
        line-height: 1.5;
    }

    @media (max-width: 992px) {
        .dashboard-panels {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {
        .stat-grid {
            grid-template-columns: 1fr 1fr;
        }

        .card-header {
            flex-direction: column;
            align-items: stretch;
        }

        .pending-item {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

@endsection
