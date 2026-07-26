<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollHistory extends Model
{
    protected $table = 'payroll_history';

    protected $fillable = [
        'employee_id',
        'processed_by',
        'cutoff_start',
        'cutoff_end',
        'gross_pay',
        'overtime_pay',
        'bonus_total',
        'total_bonus',
        'incentive_total',
        'total_incentive',
        'late_deduction',
        'late_deductions',
        'department_deduction',
        'sss',
        'sss_deduction',
        'philhealth',
        'philhealth_deduction',
        'pagibig',
        'pagibig_deduction',
        'total_deductions',
        'total_deduction',
        'net_pay',
        'status'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function items()
    {
        return $this->hasMany(PayrollItem::class, 'payroll_id');
    }
}