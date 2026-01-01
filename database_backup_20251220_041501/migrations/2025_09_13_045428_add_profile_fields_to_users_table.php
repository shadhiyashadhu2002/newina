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
        Schema::table('users', function (Blueprint $table) {
            $table->string('gender')->nullable();
            $table->string('mobile_number_1')->nullable();
            $table->string('mobile_number_2')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->boolean('welcome_call_completed')->default(false);
            $table->text('comments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'gender',
                'mobile_number_1', 
                'mobile_number_2',
                'whatsapp_number',
                'welcome_call_completed',
                'comments'
            ]);
        });
    }
};