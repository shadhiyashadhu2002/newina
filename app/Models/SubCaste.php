<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCaste extends Model
{
    use HasFactory;

    protected $table = 'sub_castes';

    protected $fillable = [
        'name',
        'caste_id',
        'status',
    ];
}
