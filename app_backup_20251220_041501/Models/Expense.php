<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Expense extends Model
{
    protected $fillable = [
        'date',
        'description', 
        'notes',
        'amount',
        'manager',
        'created_by'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2'
    ];

    // Relationship with User
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scope for today's expenses
    public function scopeToday($query)
    {
        return $query->whereDate('date', Carbon::today());
    }

    // Scope for this week's expenses
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('date', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ]);
    }

    // Scope for this month's expenses
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', Carbon::now()->month)
                    ->whereYear('date', Carbon::now()->year);
    }
}
