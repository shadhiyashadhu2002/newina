<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->integer('preferred_age_min')->nullable()->after('preferred_age');
            $table->integer('preferred_age_max')->nullable()->after('preferred_age_min');
            $table->string('preferred_height')->nullable()->after('preferred_family_status');
            $table->string('preferred_complexion')->nullable()->after('preferred_height');
            $table->string('preferred_body_type')->nullable()->after('preferred_complexion');
            $table->enum('preferred_smoking', ['acceptable', 'not_acceptable', 'no_preference'])->nullable()->after('preferred_body_type');
            $table->enum('preferred_drinking', ['acceptable', 'not_acceptable', 'no_preference'])->nullable()->after('preferred_smoking');
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