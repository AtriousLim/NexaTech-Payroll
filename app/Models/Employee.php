<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Department;
use App\Models\Position;

class Employee extends Model
{
    protected $table = 'employees';

    protected $fillable = [

        'employee_code',

        'first_name',

        'last_name',

        'address',

        'contact_number',

        'gmail',

        'department_id',

        'position_id',

        'employment_type',

        'hire_date',

        'status',

    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function payrollHistory()
    {
        return $this->hasMany(PayrollHistory::class);
    }

    public function bonuses()
    {
        return $this->hasMany(Bonus::class, 'position_id', 'position_id');
    }
}