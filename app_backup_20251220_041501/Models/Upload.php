<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;

    protected $table = 'uploads';
    
    protected $fillable = [
        'file_original_name',
        'file_name',
        'user_id',
        'file_size',
        'extension',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get the full URL for the uploaded file
    public function getUrlAttribute()
    {
        return asset($this->file_name);
    }
}