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
        // Service Details
        'service_name',
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
        // Soft delete tracking
        'deleted',
        'delete_comment',
        'deleted_at',
        'deleted_by',
    ];
}
