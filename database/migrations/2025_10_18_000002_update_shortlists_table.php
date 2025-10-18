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
        if (!Schema::hasTable('shortlists')) return;

        Schema::table('shortlists', function (Blueprint $table) {
            if (!Schema::hasColumn('shortlists', 'profile_id')) {
                $table->string('profile_id')->nullable()->after('user_id')->index();
            }
            if (!Schema::hasColumn('shortlists', 'prospect_id')) {
                $table->string('prospect_id')->nullable()->after('profile_id')->index();
            }
            if (!Schema::hasColumn('shortlists', 'source')) {
                $table->string('source')->nullable()->after('prospect_id')->index();
            }
            if (!Schema::hasColumn('shortlists', 'prospect_name')) {
                $table->string('prospect_name')->nullable()->after('source');
            }
            if (!Schema::hasColumn('shortlists', 'prospect_age')) {
                $table->integer('prospect_age')->nullable()->after('prospect_name');
            }
            if (!Schema::hasColumn('shortlists', 'prospect_education')) {
                $table->string('prospect_education')->nullable()->after('prospect_age');
            }
            if (!Schema::hasColumn('shortlists', 'prospect_occupation')) {
                $table->string('prospect_occupation')->nullable()->after('prospect_education');
            }
            if (!Schema::hasColumn('shortlists', 'prospect_location')) {
                $table->string('prospect_location')->nullable()->after('prospect_occupation');
            }
            if (!Schema::hasColumn('shortlists', 'prospect_religion')) {
                $table->string('prospect_religion')->nullable()->after('prospect_location');
            }
            if (!Schema::hasColumn('shortlists', 'prospect_caste')) {
                $table->string('prospect_caste')->nullable()->after('prospect_religion');
            }
            if (!Schema::hasColumn('shortlists', 'prospect_marital_status')) {
                $table->string('prospect_marital_status')->nullable()->after('prospect_caste');
            }
            if (!Schema::hasColumn('shortlists', 'prospect_height')) {
                $table->string('prospect_height')->nullable()->after('prospect_marital_status');
            }
            if (!Schema::hasColumn('shortlists', 'prospect_weight')) {
                $table->string('prospect_weight')->nullable()->after('prospect_height');
            }
            if (!Schema::hasColumn('shortlists', 'prospect_contact')) {
                $table->string('prospect_contact')->nullable()->after('prospect_weight');
            }
            if (!Schema::hasColumn('shortlists', 'contact_date')) {
                $table->date('contact_date')->nullable()->after('prospect_contact');
            }
            if (!Schema::hasColumn('shortlists', 'status')) {
                $table->string('status')->default('new')->after('contact_date');
            }
            if (!Schema::hasColumn('shortlists', 'customer_reply')) {
                $table->text('customer_reply')->nullable()->after('status');
            }
            if (!Schema::hasColumn('shortlists', 'remark')) {
                $table->text('remark')->nullable()->after('customer_reply');
            }
            if (!Schema::hasColumn('shortlists', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Do not drop columns automatically to preserve data. Manual rollback required if necessary.
    }
};
