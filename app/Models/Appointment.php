<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'department_id',
        'appointment_date',
        'appointment_time',
        'type',
        'symptoms',
        'status',
    ];

    /**
     * Get the patient that owns the appointment.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the doctor that owns the appointment.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the department that owns the appointment.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the medical record for the appointment.
     */
    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }
}
