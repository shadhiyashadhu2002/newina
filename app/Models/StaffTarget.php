<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffTarget extends Model
{
    protected $table = 'staff_targets';

    protected $fillable = [
        'staff_id',
        'month',
        'target_amount',
        'branch',
        'created_by'
    ];

    protected $casts = [
        'target_amount' => 'decimal:2'
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
