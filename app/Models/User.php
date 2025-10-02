<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'email',
        'password',
        'code',
        'is_admin',
        'user_type',
        'gender',
        'phone',
        'phone2',
        'mobile_number_1',
        'mobile_number_2',
        'whatsapp_number',
        'welcome_call_completed',
        'comments',
        'created_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function member()
    {
        return $this->hasOne(Member::class);
    }

    public function physicalAttribute()
    {
        return $this->hasOne(PhysicalAttribute::class);
    }

    public function spiritualBackground()
    {
        return $this->hasOne(SpiritualBackground::class);
    }

    public function career()
    {
        return $this->hasOne(Career::class);
    }

    public function profilePhoto()
    {
        return $this->belongsTo(Upload::class, 'photo', 'id');
    }
    
}
