<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpiritualBackground extends Model
{
    use HasFactory;

    protected $table = 'spiritual_backgrounds';
    
    protected $fillable = [
        'user_id',
        'religion_id',
        'caste_id',
        'subcaste',
        'gothra',
        'star',
        'raasi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class);
    }

    public function caste()
    {
        return $this->belongsTo(Caste::class);
    }
}