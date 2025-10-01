<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    use HasFactory;

    protected $table = 'careers';
    
    protected $fillable = [
        'user_id',
        'designation',
        'company',
        'industry',
        'experience',
        'annual_income',
        'currency',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}