<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incentive extends Model
{
    protected $table = 'incentives';

    protected $fillable = [
        'department_id',
        'position_id',
        'incentive_name',
        'incentive_amount',
        'is_active'
    ];
}