<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'department_name',
        'default_deduction'
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function positions()
    {
        return $this->hasMany(Position::class);
    }
}