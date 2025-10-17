<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    use HasFactory;

    protected $table = 'families';
    protected $fillable = [
        'user_id',
        'father',
        'father_occupation',
        'mother',
        'mother_occupation',
        'no_of_brothers',
        'no_of_sisters',
        'about_sibling',
    ];
}
