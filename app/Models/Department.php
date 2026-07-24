<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';

    protected $fillable = [
        'department_name',
    ];

    public function positions()
    {
        return $this->hasMany(Position::class, 'department_id');
    }
}