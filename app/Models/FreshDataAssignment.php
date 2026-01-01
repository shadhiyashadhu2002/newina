<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreshDataAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'assigned_to_staff_id',
        'assigned_by_staff_id',
        'profile_type',
        'status',
        'assigned_at'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->belongsTo(FreshData::class, 'profile_id');
    }

    public function assignedToStaff()
    {
        return $this->belongsTo(User::class, 'assigned_to_staff_id');
    }

    public function assignedByStaff()
    {
        return $this->belongsTo(User::class, 'assigned_by_staff_id');
    }
}
