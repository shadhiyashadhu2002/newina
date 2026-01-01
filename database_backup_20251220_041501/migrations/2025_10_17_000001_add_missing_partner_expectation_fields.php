<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('partner_expectations')) {
            Schema::table('partner_expectations', function (Blueprint $table) {
                if (!Schema::hasColumn('partner_expectations', 'preferred_age_min')) {
                    $table->integer('preferred_age_min')->nullable()->after('general');
                }
                if (!Schema::hasColumn('partner_expectations', 'preferred_age_max')) {
                    $table->integer('preferred_age_max')->nullable()->after('preferred_age_min');
                }
                if (!Schema::hasColumn('partner_expectations', 'preferred_height')) {
                    $table->string('preferred_height')->nullable();
                }
                if (!Schema::hasColumn('partner_expectations', 'preferred_weight')) {
                    $table->string('preferred_weight')->nullable();
                }
                if (!Schema::hasColumn('partner_expectations', 'complexion')) {
                    $table->string('complexion')->nullable();
                }
                if (!Schema::hasColumn('partner_expectations', 'preferred_body_type')) {
                    $table->string('preferred_body_type')->nullable();
                }
                if (!Schema::hasColumn('partner_expectations', 'smoking_acceptable')) {
                    $table->boolean('smoking_acceptable')->nullable()->default(null);
                }
                if (!Schema::hasColumn('partner_expectations', 'drinking_acceptable')) {
                    $table->boolean('drinking_acceptable')->nullable()->default(null);
                }
                if (!Schema::hasColumn('partner_expectations', 'preferred_education')) {
                    $table->string('preferred_education')->nullable();
                }
                if (!Schema::hasColumn('partner_expectations', 'preferred_annual_income')) {
                    $table->string('preferred_annual_income')->nullable();
                }
                if (!Schema::hasColumn('partner_expectations', 'preferred_profession')) {
                    $table->string('preferred_profession')->nullable()->after('profession');
                }
                if (!Schema::hasColumn('partner_expectations', 'preferred_religion_id')) {
                    $table->unsignedBigInteger('preferred_religion_id')->nullable()->after('religion_id');
                }
                if (!Schema::hasColumn('partner_expectations', 'preferred_caste_id')) {
                    $table->unsignedBigInteger('preferred_caste_id')->nullable()->after('preferred_religion_id');
                }
                if (!Schema::hasColumn('partner_expectations', 'preferred_sub_caste_id')) {
                    $table->unsignedBigInteger('preferred_sub_caste_id')->nullable()->after('preferred_caste_id');
                }
                if (!Schema::hasColumn('partner_expectations', 'preferred_marital_status_id')) {
                    $table->unsignedBigInteger('preferred_marital_status_id')->nullable()->after('marital_status_id');
                }
                if (!Schema::hasColumn('partner_expectations', 'preferred_family_value_id')) {
                    $table->unsignedBigInteger('preferred_family_value_id')->nullable()->after('family_value_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('partner_expectations')) {
            Schema::table('partner_expectations', function (Blueprint $table) {
                $cols = [
                    'preferred_age_min', 'preferred_age_max', 'preferred_height', 'preferred_weight',
                    'preferred_body_type', 'preferred_education', 'preferred_profession',
                    'preferred_religion_id', 'preferred_caste_id', 'preferred_sub_caste_id',
                    'preferred_marital_status_id', 'preferred_family_value_id'
                ];
                foreach ($cols as $c) {
                    if (Schema::hasColumn('partner_expectations', $c)) {
                        $table->dropColumn($c);
                    }
                }
            });
        }
    }
};
