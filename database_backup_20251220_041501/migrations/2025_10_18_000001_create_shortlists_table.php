<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('shortlists')) {
            Schema::create('shortlists', function (Blueprint $table) {
                $table->id();
                $table->string('profile_id')->index();
                $table->string('prospect_id')->nullable()->index();
                $table->string('source')->nullable()->index();

                $table->string('prospect_name')->nullable();
                $table->integer('prospect_age')->nullable();
                $table->string('prospect_education')->nullable();
                $table->string('prospect_occupation')->nullable();
                $table->string('prospect_location')->nullable();
                $table->string('prospect_religion')->nullable();
                $table->string('prospect_caste')->nullable();
                $table->string('prospect_marital_status')->nullable();
                $table->string('prospect_height')->nullable();
                $table->string('prospect_weight')->nullable();
                $table->string('prospect_contact')->nullable();
                $table->date('contact_date')->nullable();

                $table->string('status')->default('new');
                $table->text('customer_reply')->nullable();
                $table->text('remark')->nullable();

                $table->string('shortlisted_by')->nullable();

                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shortlists');
    }
};