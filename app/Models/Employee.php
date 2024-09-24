<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'email', 'timezone'];

    public function workSchedules()
    {
        return $this->hasMany(WorkSchedule::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

}
