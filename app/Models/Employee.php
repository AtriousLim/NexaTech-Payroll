<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employees';

    protected $fillable = [
        'employee_code',
        'first_name',
        'last_name',
        'address',
        'contact_number',
        'gmail',
        'username',
        'password',
        'role',
        'position_id',
        'status',
    ];

    protected $hidden = [
        'password',
    ];
}