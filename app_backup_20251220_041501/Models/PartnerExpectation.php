<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerExpectation extends Model
{
    use HasFactory;

    protected $table = 'partner_expectations';

    protected $fillable = [
        'user_id',
        'general',
        'preferred_age_min',
        'preferred_age_max',
        'height',
        'weight',
        'preferred_height',
        'preferred_weight',
        'marital_status_id',
        'children_acceptable',
        'residence_country_id',
        'religion_id',
        'caste_id',
        'sub_caste_id',
        'preferred_religion_id',
        'preferred_caste_id',
        'preferred_sub_caste_id',
        'education',
        'preferred_education',
        'profession',
        'preferred_profession',
        'smoking_acceptable',
            'preferred_profession', // This line is already present
        'diet',
        'body_type',
            'preferred_smoking',
            'preferred_drinking',
        'preferred_body_type',
        'personal_value',
        'manglik',
        'language_id',
        'family_value_id',
        'preferred_family_value_id',
        'preferred_country_id',
        'preferred_state_id',
        'complexion',
        'created_at',
            'preferred_annual_income', // New line added
        'deleted_at',
    ];
}
