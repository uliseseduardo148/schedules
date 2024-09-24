<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['employee_id', 'date', 'start_time', 'end_time', 'is_available'];

    protected $casts = [
        'date' => 'datetime:Y-m-d',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getStartTimeParsedAttribute()
    {
        $timezone = $this->employee->timezone;
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->date->format('Y-m-d') . ' ' . $this->start_time->format('H:i:s'), $timezone)->format('H:i');
    }
    public function getEndTimeParsedAttribute()
    {
        $timezone = $this->employee->timezone;
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->date->format('Y-m-d') . ' ' . $this->end_time->format('H:i:s'), $timezone)->format('H:i');
    }
}
