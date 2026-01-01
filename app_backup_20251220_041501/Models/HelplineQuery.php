<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HelplineQuery extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'call_status',
        'nature_of_call',
        'video_source',
        'video_reference',
        'mobile_number',
        'profile_id',
        'executive_name',
        'remarks',
        'purpose',
        'new_lead',
        'created_by'
    ];

    protected $casts = [
        'date' => 'date',
        'new_lead' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
