<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'patient_code',
        'first_name',
        'last_name',
        'birth_date',
        'gender',
        'address',
        'weight_kg',
        'height_cm',
        'status',
    ];

    /**
     * Get the user that owns the patient.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the appointments for the patient.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the medical records for the patient.
     */
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }
}
