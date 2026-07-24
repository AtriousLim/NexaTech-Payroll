<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    protected $table = 'bonuses';

    protected $fillable = [
        'department_id',
        'position_id',
        'bonus_name',
        'bonus_amount',
        'is_active'
    ];
}