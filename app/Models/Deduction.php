<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    protected $table = 'deductions';

    protected $fillable = [
        'department_id',
        'deduction_name',
        'deduction_amount',
        'is_active'
    ];
}