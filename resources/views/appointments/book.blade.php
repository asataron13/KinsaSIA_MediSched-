@extends('layouts.app')

@section('content')

<div class="page-header">
    <h1>Book an Appointment</h1>
    <p>Fill in the details below. Our team will confirm your schedule shortly.</p>
</div>

{{-- Guest banner --}}
@guest
<div style="background:#fff8e1;border:1.5px solid #f9ca24;border-radius:10px;padding:16px 20px;margin-bottom:24px;display:flex;align-items:center;gap:14px;">
    <span style="font-size:1.3rem;">🔒</span>
    <div>
        <strong>Login required to submit</strong>
        <p style="font-size:0.88rem;color:#5a4b00;margin-top:2px;">
            You can browse the form, but you'll need to
            <a href="{{ route('login') }}" style="color:#1a7a42;font-weight:700;">log in</a> or
            <a href="{{ route('register') }}" style="color:#1a7a42;font-weight:700;">create an account</a>
            to complete your booking.
        </p>
    </div>
</div>
@endguest

<div class="card" style="max-width:740px;margin:0 auto;">
    <form method="POST" action="{{ route('appointments.store') }}">
        @csrf

        {{-- ── Patient Information ── --}}
        <h3 style="font-size:1.05rem;font-weight:800;margin-bottom:20px;padding-bottom:10px;border-bottom:2px solid #eafaf1;color:#1a2e1f;">
            Patient Information
        </h3>

        <div class="form-row">
            <div class="form-group">
                <label>First Name *</label>
                <input type="text" name="first_name" value="{{ old('first_name', $patient->first_name ?? '') }}" placeholder="e.g. Juan" required/>
                @error('first_name') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Last Name *</label>
                <input type="text" name="last_name" value="{{ old('last_name', $patient->last_name ?? '') }}" placeholder="e.g. dela Cruz" required/>
                @error('last_name') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Address *</label>
            <input type="text" name="address" value="{{ old('address', $patient->address ?? '') }}" placeholder="House No., Street, Barangay, City" required/>
            @error('address') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Date of Birth *</label>
                <input type="date" name="birth_date" value="{{ old('birth_date', $patient->birth_date ?? '') }}" required/>
                @error('birth_date') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Gender *</label>
                <select name="gender" required>
                    <option value="">-- Select --</option>
                    @foreach(['Male','Female','Other'] as $g)
                        <option value="{{ $g }}" {{ old('gender', $patient->gender ?? '') == $g ? 'selected' : '' }}>{{ $g }}</option>
                    @endforeach
                </select>
                @error('gender') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Weight (kg) <span style="font-weight:400;text-transform:none;color:#5a8a6e;">optional</span></label>
                <input type="number" step="0.01" name="weight_kg" value="{{ old('weight_kg', $patient->weight_kg ?? '') }}" placeholder="e.g. 65"/>
                @error('weight_kg') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Height (cm) <span style="font-weight:400;text-transform:none;color:#5a8a6e;">optional</span></label>
                <input type="number" step="0.01" name="height_cm" value="{{ old('height_cm', $patient->height_cm ?? '') }}" placeholder="e.g. 165"/>
                @error('height_cm') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- ── Appointment Details ── --}}
        <h3 style="font-size:1.05rem;font-weight:800;margin:24px 0 20px;padding-bottom:10px;border-bottom:2px solid #eafaf1;color:#1a2e1f;">
            Appointment Details
        </h3>

        <div class="form-row">
            <div class="form-group">
                <label>Department *</label>
                <select name="department_id" id="department_id" required onchange="filterDoctors()">
                    <option value="">-- Select a department --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
                @error('department_id') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Doctor *</label>
                <select name="doctor_id" id="doctor_id" required>
                    <option value="">-- Select a doctor --</option>
                    @foreach($doctors as $doc)
                        <option value="{{ $doc->id }}"
                                data-dept="{{ $doc->department_id }}"
                                {{ old('doctor_id') == $doc->id ? 'selected' : '' }}>
                            Dr. {{ $doc->first_name }} {{ $doc->last_name }}
                        </option>
                    @endforeach
                </select>
                @error('doctor_id') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Preferred Date *</label>
                <input type="date" name="appointment_date" value="{{ old('appointment_date') }}" min="{{ date('Y-m-d') }}" required/>
                @error('appointment_date') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Appointment Type *</label>
                <select name="type" required>
                    <option value="">-- Select --</option>
                    <option value="Online"  {{ old('type') == 'Online'  ? 'selected' : '' }}>Online</option>
                    <option value="Walk-in" {{ old('type') == 'Walk-in' ? 'selected' : '' }}>Walk-in</option>
                </select>
                @error('type') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Symptoms / Notes *</label>
            <textarea name="symptoms" rows="4" placeholder="Describe your symptoms or reason for visit in detail..." required>{{ old('symptoms') }}</textarea>
            @error('symptoms') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        {{-- Submit or login prompt --}}
        <div style="display:flex;justify-content:flex-end;margin-top:10px;">
            @auth
                <button type="submit" class="btn btn-green" style="padding:11px 36px;font-size:1rem;">
                    Submit Appointment →
                </button>
            @else
                <a href="{{ route('login') }}" class="btn btn-green" style="padding:11px 36px;font-size:1rem;">
                    Log in to Submit →
                </a>
            @endauth
        </div>

    </form>
</div>

<script>
function filterDoctors() {
    const deptId = document.getElementById('department_id').value;
    const doctorSelect = document.getElementById('doctor_id');
    const options = doctorSelect.querySelectorAll('option');

    options.forEach(opt => {
        if (!opt.value || !deptId) { opt.style.display = ''; return; }
        opt.style.display = opt.dataset.dept === deptId ? '' : 'none';
    });
    doctorSelect.value = '';
}
</script>

@endsection