@extends('layouts.admin')
@section('page-title', 'Add Doctor')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
    <div class="page-header" style="margin-bottom:0;">
        <h1>Add New Doctor</h1>
        <p>Create a doctor account and profile.</p>
    </div>
    <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline">← Back</a>
</div>

<div class="card" style="max-width:780px;">
    <form method="POST" action="{{ route('admin.doctors.store') }}">
        @csrf

        <h3 style="font-size:0.95rem;font-weight:800;text-transform:uppercase;letter-spacing:0.04em;color:#3a5a48;margin-bottom:18px;padding-bottom:10px;border-bottom:2px solid #eef4f0;">
            Login Credentials
        </h3>
        <div class="form-row">
            <div class="form-group">
                <label>Display Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Dr. Maria Santos" required/>
                @error('name') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="doctor@email.com"/>
                @error('email') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="09171234567"/>
                @error('phone') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group"></div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Password *</label>
                <input type="password" name="password" required/>
                @error('password') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Confirm Password *</label>
                <input type="password" name="password_confirmation" required/>
            </div>
        </div>

        <h3 style="font-size:0.95rem;font-weight:800;text-transform:uppercase;letter-spacing:0.04em;color:#3a5a48;margin:24px 0 18px;padding-bottom:10px;border-bottom:2px solid #eef4f0;">
            Doctor Profile
        </h3>
        <div class="form-row">
            <div class="form-group">
                <label>First Name *</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" required/>
                @error('first_name') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Last Name *</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" required/>
                @error('last_name') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Department *</label>
                <select name="department_id" required>
                    <option value="">-- Select --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
                @error('department_id') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Years of Experience *</label>
                <input type="number" name="experience_years" value="{{ old('experience_years', 0) }}" min="0" required/>
                @error('experience_years') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Schedule Start *</label>
                <input type="time" name="schedule_start" value="{{ old('schedule_start', '08:00') }}" required/>
                @error('schedule_start') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Schedule End *</label>
                <input type="time" name="schedule_end" value="{{ old('schedule_end', '17:00') }}" required/>
                @error('schedule_end') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Availability Status *</label>
                <select name="availability_status" required>
                    @foreach(['Available','In Session','Day Off'] as $st)
                        <option value="{{ $st }}" {{ old('availability_status', 'Available') == $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
                @error('availability_status') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Rating (0–5)</label>
                <input type="number" step="0.1" name="rating" value="{{ old('rating', 0) }}" min="0" max="5"/>
                @error('rating') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;margin-top:10px;gap:10px;">
            <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-green">Create Doctor Account</button>
        </div>
    </form>
</div>

@endsection