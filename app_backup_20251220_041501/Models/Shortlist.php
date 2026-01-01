<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shortlist extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'profile_id',
        'prospect_id',
        'source',
        'prospect_name',
        'prospect_age',
        'prospect_education',
        'prospect_occupation',
        'prospect_location',
        'prospect_religion',
        'prospect_caste',
        'prospect_marital_status',
        'prospect_height',
        'prospect_weight',
        'prospect_contact',
        'contact_date',
        'status',
        'customer_reply',
        'remark',
        'shortlisted_by',
        'user_id'
    ];

    protected $casts = [
        'contact_date' => 'date',
        'prospect_age' => 'integer',
        'shortlisted_by' => 'integer',
        'user_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationship to the customer's service
    public function service()
    {
        return $this->belongsTo(Service::class, 'profile_id', 'profile_id');
    }

    // If the prospect is from INA, get their user details
    public function prospectUser()
    {
        return $this->belongsTo(User::class, 'prospect_id', 'code');
    }
}
