<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_id',
        'record_type',
        'diagnosis',
        'notes',
        'status',
        'record_date',
    ];

    /**
     * Get the patient that owns the medical record.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the doctor that owns the medical record.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the appointment that owns the medical record.
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
