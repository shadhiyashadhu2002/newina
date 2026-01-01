<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyValue extends Model
{
    use HasFactory;

    protected $table = 'family_values';

    protected $fillable = [
        'value',
        'status',
    ];
}
