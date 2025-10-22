<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'name',
        'plan_name',
        'payment_date',
        'service_executive',
        'status',
        'is_complete',
        // Service Details
        'service_name',
        'service_price',
        'amount_paid',
        'service_details',
        'service_duration',
        'success_fee',
        'refund_price',
        'after_payment',
        'start_date',
        'expiry_date',
        'rm_name',
        // Member Info
        'member_name',
        'member_age',
        'member_education',
        'member_occupation',
        'member_income',
        'member_marital_status',
        'member_family_status',
        'member_father_details',
        'member_mother_details',
        'member_sibling_details',
        'member_caste',
        'member_subcaste',
        // Partner Preferences
        'preferred_age',
        'preferred_weight',
        'preferred_education',
        'preferred_religion',
        'preferred_caste',
        'preferred_subcaste',
        'preferred_marital_status',
        'preferred_annual_income',
        'preferred_occupation',
        'preferred_family_status',
        'preferred_eating_habits',
        'preferred_age_min',
        'preferred_age_max',
        'preferred_height',
        'preferred_complexion',
        'preferred_body_type',
         'preferred_smoking',
        'preferred_drinking',
        // Contact Details
        'contact_customer_name',
        'contact_mobile_no',
        'contact_whatsapp_no',
        'contact_email',
        'contact_alternate',
        'contact_client',
        'contact_phone',
        'contact_address',
        // Edit tracking
        'edit_comment',
        'edit_flag',
        // RM Change tracking
        'rm_change',
        'rm_change_history',
        'previous_service_executive',
        // Soft delete tracking
        'deleted',
        'delete_comment',
        'deleted_at',
    'deleted_by',
    // Status tracking
    'tracking_updated_by',
    // Photo upload linkage (uploads.id)
    'photo',
    ];
      // ⭐ ADD DEFAULT VALUES - Important for new records
    protected $attributes = [
        'status' => 'new',
        'is_complete' => false,
        'deleted' => 0,
        'edit_flag' => 'N',
    ];

    // ⭐ ADD CASTS - Ensures proper data types
    protected $casts = [
        'is_complete' => 'boolean',
        'deleted' => 'boolean',
        'start_date' => 'date',
        'expiry_date' => 'date',
        'payment_date' => 'date',
        'deleted_at' => 'datetime',
        'assigned_at' => 'datetime',
        'amount_paid' => 'decimal:2',
        'service_price' => 'decimal:2',
        'success_fee' => 'decimal:2',
        'refund_price' => 'decimal:2',
        'member_age' => 'integer',
        'preferred_age_min' => 'integer',
        'preferred_age_max' => 'integer',
    ];

    // ⭐ ADD QUERY SCOPES - Makes querying easier

    /**
     * Scope to get only new/incomplete services
     */
   public function scopeIncomplete($query)
{
    return $query->where('deleted', 0)
                ->where(function($q) {
                    $q->where(function($subQ) {
                        // Either is_complete is false or NULL
                        $subQ->where('is_complete', false)
                             ->orWhereNull('is_complete');
                    })
                    ->where(function($subQ) {
                        // AND status is NOT 'active' (or is NULL/new/pending)
                        $subQ->where('status', '!=', 'active')
                             ->orWhereNull('status')
                             ->orWhere('status', 'new')
                             ->orWhere('status', 'pending');
                    });
                });
}

    /**
     * Scope to get completed services
     */
  public function scopeCompleted($query)
{
    return $query->where('is_complete', true)
                ->where('deleted', 0);
}

    /**
     * Scope to get active services
     */
    public function scopeActive($query)
{
    return $query->where('deleted', 0)
                ->where(function($q) {
                    $q->where('status', 'active')
                      ->orWhere(function($subQ) {
                          // Also include services marked as complete
                          $subQ->where('is_complete', true)
                               ->whereNotNull('service_name')
                               ->whereNotNull('start_date');
                      });
                })
                ->where(function($q) {
                    // Exclude expired services
                    $q->whereNull('expiry_date')
                      ->orWhere('expiry_date', '>=', now());
                });
}

    /**
     * Scope to get services for a specific executive
     */
    public function scopeForExecutive($query, $executiveName)
    {
        $normalized = strtolower(trim($executiveName));
        return $query->whereRaw('LOWER(TRIM(service_executive)) = ?', [$normalized]);
    }

    /**
     * Scope to exclude deleted services
     */
    public function scopeNotDeleted($query)
    {
        return $query->where('deleted', 0);
    }

    // ⭐ ADD RELATIONSHIPS

    /**
     * Get the user that owns the service
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Get the photo upload
     */
    public function photoUpload()
    {
        return $this->belongsTo(\App\Models\Upload::class, 'photo');
    }

    /**
     * Get shortlisted profiles for this service
     */
    public function shortlists()
    {
        return $this->hasMany(\App\Models\Shortlist::class, 'profile_id', 'profile_id');
    }

    // ⭐ ADD HELPER METHODS

    /**
     * Check if service is expired
     */
    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date < now();
    }

    /**
     * Get the photo URL
     */
    public function getPhotoUrlAttribute()
    {
        if (!$this->photo) {
            return null;
        }

        $upload = \App\Models\Upload::find($this->photo);
        if ($upload && $upload->file_name) {
            $filePath = public_path($upload->file_name);
            if (file_exists($filePath)) {
                return url('laravel/public/' . $upload->file_name);
            }
        }

        return null;
    }

    /**
     * Get the remaining balance
     */
    public function getRemainingBalanceAttribute()
    {
        if (!$this->service_price || !$this->amount_paid) {
            return 0;
        }
        return $this->service_price - $this->amount_paid;
    }

    /**
     * Check if service has contact details
     */
    public function hasContactDetails()
    {
        return !empty($this->contact_mobile_no) && !empty($this->contact_customer_name);
    }

    /**
     * Check if service has complete service details
     */
    public function hasServiceDetails()
    {
        return !empty($this->service_name) 
            && !empty($this->amount_paid) 
            && !empty($this->start_date) 
            && !empty($this->expiry_date);
    }

}
