<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'department_id',
        'first_name',
        'last_name',
        'experience_years',
        'rating',
        'schedule_start',
        'schedule_end',
        'availability_status',
    ];

    /**
     * Get the user that owns the doctor.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the department that owns the doctor.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the appointments for the doctor.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the medical records for the doctor.
     */
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }
}
