<?php
// Laravel Eloquent model for the education table
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table = 'education';
    protected $fillable = [
        'user_id',
        'degree',
        // add other columns as needed
    ];
}
