<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'hospital_name',
        'system_name',
        'contact_number',
        'email',
        'address',
        'email_notifications',
        'sms_notifications',
        'new_booking_alerts',
        'cancellation_alerts',
        'max_bookings_per_day',
        'booking_window_days',
        'consultation_duration_mins',
        'cancellation_policy_hours',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'new_booking_alerts' => 'boolean',
            'cancellation_alerts' => 'boolean',
        ];
    }
}

