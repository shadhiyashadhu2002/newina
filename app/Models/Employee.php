<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $fillable = [
        'emp_code',
        'name',
        'emergency_mobile',
        'email',
        'contact_person',
        'aadhar_card_no',
        'address',
        'date_of_joining',
        'designation',
        'department',
        'company',
        'salary',
    ];

    protected $casts = [
        'date_of_joining' => 'date',
        'salary' => 'float',
    ];
}
