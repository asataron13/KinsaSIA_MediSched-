@extends('layouts.admin')
@section('page-title', 'Edit Doctor')

@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
    <div class="page-header" style="margin-bottom:0;">
        <h1>Edit Dr. {{ $doctor->first_name }} {{ $doctor->last_name }}</h1>
        <p>Update doctor profile information.</p>
    </div>

    <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline">← Back</a>
</div>

<div class="card" style="max-width:820px;padding:28px;">
    <form method="POST" action="{{ route('admin.doctors.update', $doctor) }}">
        @csrf
        @method('PATCH')

        <div class="form-section">
            <h3>Doctor Profile</h3>

            <div class="form-row">
                <div class="form-group">
                    <label>First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $doctor->first_name) }}" required/>
                    @error('first_name') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $doctor->last_name) }}" required/>
                    @error('last_name') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Department *</label>
                    <select name="department_id" required>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id', $doctor->department_id) == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Years of Experience *</label>
                    <input type="number" name="experience_years" value="{{ old('experience_years', $doctor->experience_years) }}" min="0" required/>
                    @error('experience_years') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Schedule Start *</label>
                    <input type="time" name="schedule_start" value="{{ old('schedule_start', \Carbon\Carbon::parse($doctor->schedule_start)->format('H:i')) }}" required/>
                    @error('schedule_start') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Schedule End *</label>
                    <input type="time" name="schedule_end" value="{{ old('schedule_end', \Carbon\Carbon::parse($doctor->schedule_end)->format('H:i')) }}" required/>
                    @error('schedule_end') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Availability Status *</label>
                    <select name="availability_status" required>
                        @foreach(['Available','In Session','Day Off'] as $st)
                            <option value="{{ $st }}" {{ old('availability_status', $doctor->availability_status) == $st ? 'selected' : '' }}>
                                {{ $st }}
                            </option>
                        @endforeach
                    </select>
                    @error('availability_status') <div class="error-msg">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Rating (0–5)</label>
                    <input type="number" step="0.1" name="rating" value="{{ old('rating', $doctor->rating) }}" min="0" max="5"/>
                    @error('rating') <div class="error-msg">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;margin-top:18px;gap:10px;">
            <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-green">Save Changes</button>
        </div>
    </form>
</div>

<style>
    .form-section h3 {
        font-size: 0.9rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #3a5a48;
        margin-bottom: 18px;
        padding-bottom: 8px;
        border-bottom: 2px solid #eef4f0;
    }
</style>

@endsection
