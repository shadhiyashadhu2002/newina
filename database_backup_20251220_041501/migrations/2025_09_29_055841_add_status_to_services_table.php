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
        // Only add the column if it doesn't already exist (defensive for repeated runs)
        if (!Schema::hasColumn('services', 'status')) {
            Schema::table('services', function (Blueprint $table) {
                $table->enum('status', ['new', 'active', 'completed', 'cancelled'])->default('new')->after('service_executive');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
