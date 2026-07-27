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
        'status',
        'payment_date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function payrollEntry()
    {
        return $this->hasOne(Payroll::class, 'payroll_history_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(PayrollItem::class, 'payroll_id');
    }
}