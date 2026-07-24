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
        'sss_deduction',
        'philhealth_deduction',
        'pagibig_deduction',
        'late_deductions',
        'net_pay',
        'status'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}