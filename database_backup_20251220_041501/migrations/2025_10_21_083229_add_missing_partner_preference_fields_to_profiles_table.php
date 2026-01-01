<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'preferred_age_min')) {
                $table->integer('preferred_age_min')->nullable()->after('preferred_age');
            }
            if (!Schema::hasColumn('services', 'preferred_age_max')) {
                $table->integer('preferred_age_max')->nullable()->after('preferred_age_min');
            }
            if (!Schema::hasColumn('services', 'preferred_height')) {
                $table->string('preferred_height')->nullable()->after('preferred_family_status');
            }
            if (!Schema::hasColumn('services', 'preferred_complexion')) {
                $table->string('preferred_complexion')->nullable()->after('preferred_height');
            }
            if (!Schema::hasColumn('services', 'preferred_body_type')) {
                $table->string('preferred_body_type')->nullable()->after('preferred_complexion');
            }
            if (!Schema::hasColumn('services', 'preferred_smoking')) {
                $table->enum('preferred_smoking', ['acceptable', 'not_acceptable', 'no_preference'])->nullable()->after('preferred_body_type');
            }
            if (!Schema::hasColumn('services', 'preferred_drinking')) {
                $table->enum('preferred_drinking', ['acceptable', 'not_acceptable', 'no_preference'])->nullable()->after('preferred_smoking');
            }
        });
    }

    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'preferred_age_min',
                'preferred_age_max',
                'preferred_height',
                'preferred_complexion',
                'preferred_body_type',
                'preferred_smoking',
                'preferred_drinking'
            ]);
        });
    }
};