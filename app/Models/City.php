<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'name',
        'state_id'
    ];

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
