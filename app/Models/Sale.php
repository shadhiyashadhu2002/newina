<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'date',
        'profile_id',
        'phone',
        'name',
        'executive',
        'amount',
        'paid_amount',
        'success_fee',
        'discount',
        'plan',
        'status',
        'sale_status',
        'staff_id',
        'office',
        'notes',
        'created_by'
    ];
    
    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'success_fee' => 'decimal:2'
        , 'discount' => 'decimal:2'
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
