@extends('layouts.admin')
@section('page-title', 'Patient Details')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
    <div class="page-header" style="margin-bottom:0;">
        <h1>{{ $patient->first_name }} {{ $patient->last_name }}</h1>
        <p>Patient Code: <strong style="color:#27ae60;">{{ $patient->patient_code }}</strong></p>
    </div>

    <a href="{{ route('admin.patients.index') }}" class="btn btn-outline">← Back to Patients</a>
</div>

<div class="details-grid">

    {{-- Personal Info --}}
    <div class="card">
        <h3 class="section-title">Personal Information</h3>

        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Full Name</div>
                <div class="info-value strong">{{ $patient->first_name }} {{ $patient->last_name }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Gender</div>
                <div class="info-value">{{ $patient->gender }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Date of Birth</div>
                <div class="info-value">
                    {{ \Carbon\Carbon::parse($patient->birth_date)->format('F d, Y') }}
                    <span style="color:#6f8f7a;">({{ \Carbon\Carbon::parse($patient->birth_date)->age }} yrs)</span>
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">Status</div>
                <div class="info-value">
                    @php $s = strtolower($patient->status); @endphp
                    <span class="badge badge-{{ $s === 'active' ? 'active' : ($s === 'inactive' ? 'inactive' : 'pending') }}">
                        {{ $patient->status }}
                    </span>
                </div>
            </div>

            <div class="info-item full">
                <div class="info-label">Address</div>
                <div class="info-value">{{ $patient->address }}</div>
            </div>

            @if($patient->weight_kg)
            <div class="info-item">
                <div class="info-label">Weight</div>
                <div class="info-value">{{ $patient->weight_kg }} kg</div>
            </div>
            @endif

            @if($patient->height_cm)
            <div class="info-item">
                <div class="info-label">Height</div>
                <div class="info-value">{{ $patient->height_cm }} cm</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Account Info --}}
    <div class="card">
        <h3 class="section-title">Account Information</h3>

        <div class="info-grid">
            <div class="info-item full">
                <div class="info-label">Display Name</div>
                <div class="info-value strong">{{ $patient->user->name }}</div>
            </div>

            <div class="info-item full">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $patient->user->email ?? '—' }}</div>
            </div>

            <div class="info-item full">
                <div class="info-label">Phone</div>
                <div class="info-value">{{ $patient->user->phone ?? '—' }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Registered</div>
                <div class="info-value">{{ $patient->created_at->format('M d, Y') }}</div>
            </div>

            <div class="info-item">
                <div class="info-label">Total Appointments</div>
                <div class="info-value strong">{{ $patient->appointments->count() }}</div>
            </div>
        </div>
    </div>

</div>

{{-- Appointment History --}}
<div class="card">
    <div class="card-header">
        <h3>Appointment History</h3>
        <span class="text-muted">{{ $patient->appointments->count() }} total</span>
    </div>

    @if($patient->appointments->isEmpty())
        <p class="text-muted">No appointments on record.</p>
    @else
        <div class="table-wrap" style="box-shadow:none;">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Doctor</th>
                        <th>Department</th>
                        <th>Type</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patient->appointments->sortByDesc('appointment_date') as $appt)
                        @php $s = strtolower(str_replace(' ','',$appt->status)); @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}</td>
                            <td>Dr. {{ $appt->doctor->first_name }} {{ $appt->doctor->last_name }}</td>
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

{{-- Medical Records --}}
<div class="card">
    <div class="card-header">
        <h3>Medical Records</h3>
        <a href="{{ route('admin.records.index') }}" class="btn btn-outline btn-sm">View All</a>
    </div>

    @if($patient->medicalRecords->isEmpty())
        <p class="text-muted">No medical records on file.</p>
    @else
        <div class="table-wrap" style="box-shadow:none;">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Doctor</th>
                        <th>Diagnosis</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patient->medicalRecords->sortByDesc('record_date') as $rec)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($rec->record_date)->format('M d, Y') }}</td>
                            <td>{{ $rec->record_type }}</td>
                            <td>Dr. {{ $rec->doctor->first_name }} {{ $rec->doctor->last_name }}</td>
                            <td>{{ $rec->diagnosis ?? '—' }}</td>
                            <td>
                                <span class="badge badge-{{ strtolower($rec->status) }}">
                                    {{ $rec->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<style>
.details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px;
    margin-bottom: 22px;
}

.section-title {
    margin-bottom: 18px;
    font-size: 1rem;
    font-weight: 800;
    color: var(--text-dark);
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.info-item {
    background: #f9fffb;
    padding: 12px;
    border-radius: 10px;
}

.info-item.full {
    grid-column: span 2;
}

.info-label {
    font-size: 0.75rem;
    font-weight: 700;
    color: #6a8f7c;
    text-transform: uppercase;
    margin-bottom: 4px;
}

.info-value {
    font-size: 0.92rem;
    color: var(--text-dark);
}

.info-value.strong {
    font-weight: 700;
}

@media (max-width: 900px) {
    .details-grid {
        grid-template-columns: 1fr;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .info-item.full {
        grid-column: span 1;
    }
}
</style>

@endsection
