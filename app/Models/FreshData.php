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
		'gender',
		'registration_date',
		'profile_id',
		'mobile_number_2',
		'whatsapp_number',
		'profile_created',
		'photo_uploaded',
		'welcome_call',
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'assigned_to');
	}
}
