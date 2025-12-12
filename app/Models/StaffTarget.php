<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffTarget extends Model
{
    protected $fillable = [
        'staff_id',
        'month',
        'branch',
        'department',
        'target_amount',
        'created_by'
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
