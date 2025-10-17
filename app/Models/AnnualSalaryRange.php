<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnualSalaryRange extends Model
{
    use HasFactory;

    protected $table = 'annual_salary_ranges';
    protected $fillable = [
        'min_salary',
        'max_salary',
    ];
}
