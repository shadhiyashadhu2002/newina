<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Member Info
            $table->string('member_mother_details')->nullable();
            $table->string('member_sibling_details')->nullable();
            $table->string('member_caste')->nullable();
            $table->string('member_subcaste')->nullable();
            // Partner Preferences
            $table->string('preferred_age')->nullable();
            $table->string('preferred_weight')->nullable();
            $table->string('preferred_education')->nullable();
            $table->string('preferred_religion')->nullable();
            $table->string('preferred_caste')->nullable();
            $table->string('preferred_subcaste')->nullable();
            $table->string('preferred_marital_status')->nullable();
            $table->string('preferred_annual_income')->nullable();
            $table->string('preferred_occupation')->nullable();
            $table->string('preferred_family_status')->nullable();
            $table->string('preferred_eating_habits')->nullable();
            // Contact Details
            $table->string('contact_customer_name')->nullable();
            $table->string('contact_mobile_no')->nullable();
            $table->string('contact_whatsapp_no')->nullable();
            $table->string('contact_alternate')->nullable();
            $table->string('contact_client')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'member_mother_details','member_sibling_details','member_caste','member_subcaste',
                'preferred_age','preferred_weight','preferred_education','preferred_religion','preferred_caste','preferred_subcaste','preferred_marital_status','preferred_annual_income','preferred_occupation','preferred_family_status','preferred_eating_habits',
                'contact_customer_name','contact_mobile_no','contact_whatsapp_no','contact_alternate','contact_client'
            ]);
        });
    }
};
