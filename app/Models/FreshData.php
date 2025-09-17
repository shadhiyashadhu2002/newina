<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreshData extends Model
{
	use HasFactory;

	protected $fillable = [
		'mobile',
		'name',
		'source',
		'remarks',
		'assigned_to',
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'assigned_to');
	}
}
