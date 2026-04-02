<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the doctors for the department.
     */
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    /**
     * Get the appointments for the department.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
