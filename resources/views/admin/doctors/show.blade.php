@extends('layouts.admin')
@section('page-title', 'Doctor Details')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
    <div class="page-header" style="margin-bottom:0;">
        <h1>Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</h1>
        <p>{{ $doctor->department->name }}</p>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn btn-outline">Edit Profile</a>
        <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline">← Back</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:22px;margin-bottom:22px;">

    {{-- Doctor Info --}}
    <div class="card">
        <h3 style="margin-bottom:18px;font-size:1rem;">Doctor Profile</h3>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Full Name</div>
                <div class="info-value">Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Department</div>
                <div class="info-value">{{ $doctor->department->name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Experience</div>
                <div class="info-value">{{ $doctor->experience_years }} years</div>
            </div>
            <div class="info-item">
                <div class="info-label">Rating</div>
                <div class="info-value">⭐ {{ number_format($doctor->rating, 1) }} / 5.0</div>
            </div>
            <div class="info-item">
                <div class="info-label">Schedule</div>
                <div class="info-value">
                    {{ \Carbon\Carbon::parse($doctor->schedule_start)->format('h:i A') }}
                    – {{ \Carbon\Carbon::parse($doctor->schedule_end)->format('h:i A') }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Availability</div>
                <div class="info-value">
                    @php $av = strtolower(str_replace(' ','',$doctor->availability_status)); @endphp
                    <span class="badge badge-{{ $av === 'insession' ? 'insession' : ($av === 'dayoff' ? 'dayoff' : 'available') }}">
                        {{ $doctor->availability_status }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Account Info --}}
    <div class="card">
        <h3 style="margin-bottom:18px;font-size:1rem;">Account Information</h3>
        <div class="info-grid">
            <div class="info-item" style="grid-column:span 2;">
                <div class="info-label">Display Name</div>
                <div class="info-value">{{ $doctor->user->name }}</div>
            </div>
            <div class="info-item" style="grid-column:span 2;">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $doctor->user->email ?? '—' }}</div>
            </div>
            <div class="info-item" style="grid-column:span 2;">
                <div class="info-label">Phone</div>
                <div class="info-value">{{ $doctor->user->phone ?? '—' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Total Appointments</div>
                <div class="info-value">{{ $doctor->appointments->count() }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Joined</div>
                <div class="info-value">{{ $doctor->created_at->format('M d, Y') }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Appointment History --}}
<div class="card">
    <div class="card-header">
        <h3>Appointment History</h3>
        <span style="font-size:0.85rem;color:#5a8a6e;">{{ $doctor->appointments->count() }} total</span>
    </div>
    @if($doctor->appointments->isEmpty())
        <p style="color:#5a8a6e;">No appointments yet.</p>
    @else
        <div class="table-wrap" style="box-shadow:none;">
            <table>
                <thead>
                    <tr><th>Date</th><th>Patient</th><th>Department</th><th>Type</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @foreach($doctor->appointments->sortByDesc('appointment_date') as $appt)
                    @php $s = strtolower(str_replace(' ','',$appt->status)); @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}</td>
                        <td>{{ $appt->patient->first_name }} {{ $appt->patient->last_name }}</td>
                        <td>{{ $appt->department->name }}</td>
                        <td>{{ $appt->type }}</td>
                        <td><span class="badge badge-{{ $s }}">{{ $appt->status }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection