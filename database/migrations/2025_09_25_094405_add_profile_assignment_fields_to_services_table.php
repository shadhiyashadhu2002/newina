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
        Schema::table('services', function (Blueprint $table) {
            // Profile assignment fields
            $table->string('member_id')->nullable();
            $table->string('profile_source')->nullable();
            $table->date('service_date')->nullable();
            $table->text('contact_numbers')->nullable();
            $table->text('remarks')->nullable();
            $table->string('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn([
                'member_id',
                'profile_source', 
                'service_date',
                'contact_numbers',
                'remarks',
                'status'
            ]);
        });
    }
};
