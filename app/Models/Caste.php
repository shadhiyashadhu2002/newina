<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caste extends Model
{
    use HasFactory;

    protected $table = 'castes';
    
    protected $fillable = [
        'name',
        'religion_id',
        'status',
    ];

    public function religion()
    {
        return $this->belongsTo(Religion::class);
    }

    public function spiritualBackgrounds()
    {
        return $this->hasMany(SpiritualBackground::class);
    }
}