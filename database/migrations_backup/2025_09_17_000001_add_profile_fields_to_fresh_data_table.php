<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fresh_data', function (Blueprint $table) {
            $table->string('gender')->nullable();
            $table->date('registration_date')->nullable();
            $table->string('profile_id')->nullable();
            $table->string('mobile_number_2')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->boolean('profile_created')->default(false);
            $table->boolean('photo_uploaded')->default(false);
            $table->boolean('welcome_call')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('fresh_data', function (Blueprint $table) {
            $table->dropColumn([
                'gender',
                'registration_date',
                'profile_id',
                'mobile_number_2',
                'whatsapp_number',
                'profile_created',
                'photo_uploaded',
                'welcome_call',
            ]);
        });
    }
};
