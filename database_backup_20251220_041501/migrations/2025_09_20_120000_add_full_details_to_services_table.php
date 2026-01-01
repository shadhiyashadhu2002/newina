<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Service Details
            $table->string('service_name')->nullable();
            $table->decimal('amount_paid', 10, 2)->nullable();
            $table->text('service_details')->nullable();
            $table->integer('service_duration')->nullable();
            $table->decimal('success_fee', 10, 2)->nullable();
            $table->decimal('refund_price', 10, 2)->nullable();
            $table->string('after_payment')->nullable();
            $table->date('start_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('rm_name')->nullable();
            // Member Info
            $table->string('member_name')->nullable();
            $table->integer('member_age')->nullable();
            $table->string('member_education')->nullable();
            $table->string('member_occupation')->nullable();
            $table->string('member_income')->nullable();
            $table->string('member_marital_status')->nullable();
            $table->string('member_family_status')->nullable();
            $table->string('member_father_details')->nullable();
            // Partner Preferences
            $table->text('partner_preferences')->nullable();
            // Contact Details
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_address')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'service_name','amount_paid','service_details','service_duration','success_fee','refund_price','after_payment','start_date','expiry_date','rm_name',
                'member_name','member_age','member_education','member_occupation','member_income','member_marital_status','member_family_status','member_father_details',
                'partner_preferences','contact_phone','contact_email','contact_address'
            ]);
        });
    }
};
