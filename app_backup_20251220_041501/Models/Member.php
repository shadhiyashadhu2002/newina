<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members';
    protected $fillable = [
        'user_id',
        'gender',
        'birthday',
        'introduction',
        'marital_status_id',
        'children',
        'on_behalves_id',
        'annual_salary_range_id',
        'mothere_tongue',
        'known_languages',
        'current_package_id',
        'remaining_interest',
        'remaining_contact_view',
        'remaining_profile_viewer_view',
        'remaining_profile_image_view',
        'remaining_gallery_image_view',
        'remaining_photo_gallery',
        'auto_profile_match',
        'package_validity',
        'ignored_users',
        'ignored_by',
        'reported_user',
        'reported_by',
        'blocked_reason',
    ];

    protected $casts = [
        'birthday' => 'date',
        'package_validity' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function maritalStatus()
    {
        return $this->belongsTo(MaritalStatus::class, 'marital_status_id');
    }

    // Calculate age from birthday
    public function getAgeAttribute()
    {
        if (!$this->birthday) {
            return null;
        }
        
        return $this->birthday->age;
    }

    // Scope to filter by age range
    public function scopeByAgeRange($query, $minAge = null, $maxAge = null)
    {
        if ($minAge !== null) {
            // For minimum age, birthday should be on or before this date
            $maxBirthDate = now()->subYears($minAge)->format('Y-m-d');
            $query->where('birthday', '<=', $maxBirthDate);
        }

        if ($maxAge !== null) {
            // For maximum age, birthday should be after this date
            $minBirthDate = now()->subYears($maxAge + 1)->format('Y-m-d');
            $query->where('birthday', '>', $minBirthDate);
        }

        return $query;
    }
}
