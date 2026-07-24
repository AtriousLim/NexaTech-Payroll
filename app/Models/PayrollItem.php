<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollItem extends Model
{
    protected $table = 'payroll_items';

    protected $fillable = [
        'payroll_id',
        'item_type',
        'item_name',
        'amount',
        'remarks'
    ];

    public function payroll()
    {
        return $this->belongsTo(PayrollHistory::class,'payroll_id');
    }
}