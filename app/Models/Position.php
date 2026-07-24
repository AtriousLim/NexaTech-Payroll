<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $table = 'positions';

    protected $fillable = [
        'department_id',
        'position_title',
        'basic_salary',
        'has_bonus',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}