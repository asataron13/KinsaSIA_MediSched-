@extends('layouts.admin')
@section('page-title', 'Add Record')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
    <div class="page-header" style="margin-bottom:0;">
        <h1>Add Medical Record</h1>
        <p>Create a new medical record linked to an appointment.</p>
    </div>
    <a href="{{ route('admin.records.index') }}" class="btn btn-outline">← Back</a>
</div>

<div class="card" style="max-width:780px;">
    <form method="POST" action="{{ route('admin.records.store') }}">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label>Patient *</label>
                <select name="patient_id" required>
                    <option value="">-- Select Patient --</option>
                    @foreach($patients as $p)
                        <option value="{{ $p->id }}" {{ old('patient_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->first_name }} {{ $p->last_name }} ({{ $p->patient_code }})
                        </option>
                    @endforeach
                </select>
                @error('patient_id') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Doctor *</label>
                <select name="doctor_id" required>
                    <option value="">-- Select Doctor --</option>
                    @foreach($doctors as $d)
                        <option value="{{ $d->id }}" {{ old('doctor_id') == $d->id ? 'selected' : '' }}>
                            Dr. {{ $d->first_name }} {{ $d->last_name }} – {{ $d->department->name }}
                        </option>
                    @endforeach
                </select>
                @error('doctor_id') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Linked Appointment *</label>
            <select name="appointment_id" required>
                <option value="">-- Select Appointment --</option>
                @foreach($appointments as $appt)
                    <option value="{{ $appt->id }}" {{ old('appointment_id') == $appt->id ? 'selected' : '' }}>
                        #{{ $appt->id }} – {{ $appt->patient->first_name }} {{ $appt->patient->last_name }}
                        ({{ \Carbon\Carbon::parse($appt->appointment_date)->format('M d, Y') }}) – {{ $appt->status }}
                    </option>
                @endforeach
            </select>
            @error('appointment_id') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Record Type *</label>
                <select name="record_type" required>
                    <option value="">-- Select --</option>
                    @foreach(['Medical History','Lab Results','Prescription','Billing'] as $t)
                        <option value="{{ $t }}" {{ old('record_type') == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
                @error('record_type') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Status *</label>
                <select name="status" required>
                    @foreach(['Pending','Filed','Reviewed','Paid'] as $st)
                        <option value="{{ $st }}" {{ old('status', 'Pending') == $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
                @error('status') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Diagnosis</label>
                <input type="text" name="diagnosis" value="{{ old('diagnosis') }}" placeholder="e.g. Hypertension"/>
                @error('diagnosis') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Record Date *</label>
                <input type="date" name="record_date" value="{{ old('record_date', date('Y-m-d')) }}" required/>
                @error('record_date') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Notes</label>
            <textarea name="notes" rows="4" placeholder="Additional notes or observations…">{{ old('notes') }}</textarea>
            @error('notes') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:10px;">
            <a href="{{ route('admin.records.index') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-green">Create Record</button>
        </div>
    </form>
</div>

@endsection