<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::first() ?? new Setting();
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hospital_name'              => 'required|string|max:255',
            'system_name'                => 'required|string|max:100',
            'contact_number'             => 'required|string|max:20',
            'email'                      => 'required|email',
            'address'                    => 'required|string',
            'max_bookings_per_day'       => 'required|integer|min:1',
            'booking_window_days'        => 'required|integer|min:1',
            'consultation_duration_mins' => 'required|integer|min:1',
            'cancellation_policy_hours'  => 'required|integer|min:0',
        ]);

        Setting::updateOrCreate(['id' => 1], [
            'hospital_name'              => $request->hospital_name,
            'system_name'                => $request->system_name,
            'contact_number'             => $request->contact_number,
            'email'                      => $request->email,
            'address'                    => $request->address,
            'email_notifications'        => $request->boolean('email_notifications'),
            'sms_notifications'          => $request->boolean('sms_notifications'),
            'new_booking_alerts'         => $request->boolean('new_booking_alerts'),
            'cancellation_alerts'        => $request->boolean('cancellation_alerts'),
            'max_bookings_per_day'       => $request->max_bookings_per_day,
            'booking_window_days'        => $request->booking_window_days,
            'consultation_duration_mins' => $request->consultation_duration_mins,
            'cancellation_policy_hours'  => $request->cancellation_policy_hours,
        ]);

        return back()->with('success', 'Settings saved successfully.');
    }
}