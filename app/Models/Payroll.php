<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $table = 'payrolls';

    protected $fillable = [
        'employee_id',
        'department',
        'position',
        'basic_salary',
        'payroll_number',
        'pay_period_start',
        'pay_period_end',
        'total_bonus',
        'total_incentive',
        'total_deduction',
        'overtime_pay',
        'late_deduction',
        'gross_salary',
        'net_salary',
        'processed_by',
        'paid_at',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
