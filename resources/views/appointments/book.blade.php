@extends('layouts.app')

@section('content')

<div style="max-width:900px;margin:0 auto;display:flex;flex-direction:column;gap:24px;">

    <div class="page-header">
        <h1>Book an Appointment</h1>
        <p>Fill in the details below. Our team will confirm your schedule shortly.</p>
    </div>

    {{-- Guest banner --}}
    @guest
    <div style="
        background: linear-gradient(135deg,#fff8e1,#fff3c4);
        border:1.5px solid #f9ca24;
        border-radius:12px;
        padding:18px 22px;
        display:flex;
        gap:14px;
        align-items:center;
    ">
        <span style="font-size:1.4rem;">🔒</span>
        <div>
            <strong style="font-size:0.95rem;">Login required to submit</strong>
            <p style="font-size:0.88rem;color:#5a4b00;margin-top:4px;">
                You can browse the form, but you'll need to
                <a href="{{ route('login') }}" style="color:#1a7a42;font-weight:700;">log in</a> or
                <a href="{{ route('register') }}" style="color:#1a7a42;font-weight:700;">create an account</a>
                to complete your booking.
            </p>
        </div>
    </div>
    @endguest

    <div class="card" style="padding:30px;border-radius:18px;">

        <form method="POST" action="{{ route('appointments.store') }}">
            @csrf

            {{-- SECTION --}}
            <div class="form-section">
                <h3>Patient Information</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label>First Name *</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $patient->first_name ?? '') }}" required/>
                        @error('first_name') <div class="error-msg">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label>Last Name *</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $patient->last_name ?? '') }}" required/>
                        @error('last_name') <div class="error-msg">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label>Address *</label>
                    <input type="text" name="address" value="{{ old('address', $patient->address ?? '') }}" required/>
                    @error('address') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Date of Birth *</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date', $patient->birth_date ?? '') }}" required/>
                    </div>

                    <div class="form-group">
                        <label>Gender *</label>
                        <select name="gender" required>
                            <option value="">-- Select --</option>
                            @foreach(['Male','Female','Other'] as $g)
                                <option value="{{ $g }}" {{ old('gender', $patient->gender ?? '') == $g ? 'selected' : '' }}>{{ $g }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Weight (kg)</label>
                        <input type="number" step="0.01" name="weight_kg" value="{{ old('weight_kg', $patient->weight_kg ?? '') }}"/>
                    </div>

                    <div class="form-group">
                        <label>Height (cm)</label>
                        <input type="number" step="0.01" name="height_cm" value="{{ old('height_cm', $patient->height_cm ?? '') }}"/>
                    </div>
                </div>
            </div>

            {{-- SECTION --}}
            <div class="form-section">
                <h3>Appointment Details</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label>Department *</label>
                        <select name="department_id" id="department_id" required onchange="filterDoctors()">
                            <option value="">-- Select --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Doctor *</label>
                        <select name="doctor_id" id="doctor_id" required>
                            <option value="">-- Select --</option>
                            @foreach($doctors as $doc)
                                <option value="{{ $doc->id }}" data-dept="{{ $doc->department_id }}">
                                    Dr. {{ $doc->first_name }} {{ $doc->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Date *</label>
                        <input type="date" name="appointment_date" min="{{ date('Y-m-d') }}" required/>
                    </div>

                    <div class="form-group">
                        <label>Type *</label>
                        <select name="type" required>
                            <option value="">-- Select --</option>
                            <option value="Online">Online</option>
                            <option value="Walk-in">Walk-in</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Symptoms / Notes *</label>
                    <textarea name="symptoms" rows="4" required></textarea>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;margin-top:10px;">
                @auth
                    <button class="btn btn-green" style="padding:12px 40px;font-size:1rem;">
                        Submit Appointment →
                    </button>
                @else
                    <a href="{{ route('login') }}" class="btn btn-green" style="padding:12px 40px;font-size:1rem;">
                        Log in to Submit →
                    </a>
                @endauth
            </div>

        </form>
    </div>
</div>

<style>
.form-section {
    margin-bottom: 26px;
}

.form-section h3 {
    font-size: 0.9rem;
    font-weight: 800;
    text-transform: uppercase;
    color: #3a5a48;
    margin-bottom: 16px;
    border-bottom: 2px solid #eef4f0;
    padding-bottom: 8px;
}

.card {
    box-shadow: 0 10px 30px rgba(0,0,0,0.06);
}

input, select, textarea {
    transition: all 0.15s ease;
}

input:focus, select:focus, textarea:focus {
    transform: scale(1.01);
}
</style>

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
