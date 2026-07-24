<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'attendances';

    protected $fillable = [
        'employee_id',
        'attendance_date',
        'time_in',
        'time_out',
        'late_minutes',
        'overtime_minutes',
        'status',
        'remarks'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}