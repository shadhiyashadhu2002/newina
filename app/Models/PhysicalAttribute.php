<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhysicalAttribute extends Model
{
    use HasFactory;

    protected $table = 'physical_attributes';
    
    protected $fillable = [
        'user_id',
        'height',
        'weight',
        'body_type',
        'complexion',
        'physical_status',
        'blood_group',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}