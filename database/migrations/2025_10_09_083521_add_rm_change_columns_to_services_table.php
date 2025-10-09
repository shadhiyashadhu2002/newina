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
            $table->string('rm_change')->nullable()->after('service_executive');
            $table->json('rm_change_history')->nullable()->after('rm_change');
            $table->string('previous_service_executive')->nullable()->after('rm_change_history');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['rm_change', 'rm_change_history', 'previous_service_executive']);
        });
    }
};
