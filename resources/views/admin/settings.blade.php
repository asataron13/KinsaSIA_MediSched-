@extends('layouts.admin')
@section('page-title', 'Settings')

@section('content')

<div class="page-header">
    <h1>Settings</h1>
    <p>Configure hospital information and system preferences.</p>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf @method('PATCH')

    {{-- Hospital Information --}}
    <div class="card" style="max-width:860px;">
        <h3 style="font-size:0.95rem;font-weight:800;text-transform:uppercase;letter-spacing:0.04em;color:#3a5a48;margin-bottom:20px;padding-bottom:10px;border-bottom:2px solid #eef4f0;">
            Hospital Information
        </h3>
        <div class="form-row">
            <div class="form-group">
                <label>Hospital Name *</label>
                <input type="text" name="hospital_name" value="{{ old('hospital_name', $settings->hospital_name ?? '') }}" required/>
                @error('hospital_name') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>System Name *</label>
                <input type="text" name="system_name" value="{{ old('system_name', $settings->system_name ?? 'MediSched') }}" required/>
                @error('system_name') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Contact Number *</label>
                <input type="text" name="contact_number" value="{{ old('contact_number', $settings->contact_number ?? '') }}" placeholder="(02) 8123-4567" required/>
                @error('contact_number') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Email Address *</label>
                <input type="email" name="email" value="{{ old('email', $settings->email ?? '') }}" placeholder="info@hospital.com" required/>
                @error('email') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="form-group">
            <label>Address *</label>
            <input type="text" name="address" value="{{ old('address', $settings->address ?? '') }}" placeholder="Street, City, Province" required/>
            @error('address') <div class="error-msg">{{ $message }}</div> @enderror
        </div>
    </div>

    {{-- Booking Settings --}}
    <div class="card" style="max-width:860px;">
        <h3 style="font-size:0.95rem;font-weight:800;text-transform:uppercase;letter-spacing:0.04em;color:#3a5a48;margin-bottom:20px;padding-bottom:10px;border-bottom:2px solid #eef4f0;">
            Booking Settings
        </h3>
        <div class="form-row">
            <div class="form-group">
                <label>Max Bookings Per Day *</label>
                <input type="number" name="max_bookings_per_day" value="{{ old('max_bookings_per_day', $settings->max_bookings_per_day ?? 30) }}" min="1" required/>
                @error('max_bookings_per_day') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Booking Window (days) *</label>
                <input type="number" name="booking_window_days" value="{{ old('booking_window_days', $settings->booking_window_days ?? 30) }}" min="1" required/>
                @error('booking_window_days') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Consultation Duration (minutes) *</label>
                <input type="number" name="consultation_duration_mins" value="{{ old('consultation_duration_mins', $settings->consultation_duration_mins ?? 30) }}" min="1" required/>
                @error('consultation_duration_mins') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label>Cancellation Policy (hours before) *</label>
                <input type="number" name="cancellation_policy_hours" value="{{ old('cancellation_policy_hours', $settings->cancellation_policy_hours ?? 24) }}" min="0" required/>
                @error('cancellation_policy_hours') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>
    </div>

    {{-- Notification Settings --}}
    <div class="card" style="max-width:860px;">
        <h3 style="font-size:0.95rem;font-weight:800;text-transform:uppercase;letter-spacing:0.04em;color:#3a5a48;margin-bottom:20px;padding-bottom:10px;border-bottom:2px solid #eef4f0;">
            Notification Preferences
        </h3>

        @foreach([
            ['email_notifications',  'Email Notifications',   'Send confirmation emails to patients.'],
            ['sms_notifications',    'SMS Notifications',     'Send SMS alerts to patients.'],
            ['new_booking_alerts',   'New Booking Alerts',    'Notify admin when a new booking is made.'],
            ['cancellation_alerts',  'Cancellation Alerts',   'Notify admin when a booking is cancelled.'],
        ] as [$field, $label, $desc])
        <div style="display:flex;justify-content:space-between;align-items:center;padding:14px 0;border-bottom:1px solid #eef4f0;">
            <div>
                <div style="font-weight:600;font-size:0.93rem;">{{ $label }}</div>
                <div style="font-size:0.83rem;color:#5a8a6e;margin-top:2px;">{{ $desc }}</div>
            </div>
            <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                <input type="hidden" name="{{ $field }}" value="0"/>
                <input type="checkbox" name="{{ $field }}" value="1" style="width:18px;height:18px;cursor:pointer;"
                    {{ old($field, $settings->$field ?? false) ? 'checked' : '' }}/>
                <span style="font-size:0.88rem;color:#3a5a48;font-weight:600;">Enabled</span>
            </label>
        </div>
        @endforeach
    </div>

    <div style="max-width:860px;display:flex;justify-content:flex-end;gap:10px;">
        <button type="submit" class="btn btn-green" style="padding:11px 32px;">Save Settings</button>
    </div>
</form>

@endsection