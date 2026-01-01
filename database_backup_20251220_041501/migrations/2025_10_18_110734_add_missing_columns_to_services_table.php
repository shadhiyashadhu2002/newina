<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'other_site_member_id')) {
                $table->string('other_site_member_id')->nullable();
            }
            if (!Schema::hasColumn('services', 'assigned_by')) {
                $table->string('assigned_by')->nullable();
            }
            if (!Schema::hasColumn('services', 'assigned_at')) {
                $table->timestamp('assigned_at')->nullable();
            }
            if (!Schema::hasColumn('services', 'photo')) {
                $table->unsignedBigInteger('photo')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'other_site_member_id')) {
                $table->dropColumn('other_site_member_id');
            }
            if (Schema::hasColumn('services', 'assigned_by')) {
                $table->dropColumn('assigned_by');
            }
            if (Schema::hasColumn('services', 'assigned_at')) {
                $table->dropColumn('assigned_at');
            }
            if (Schema::hasColumn('services', 'photo')) {
                $table->dropColumn('photo');
            }
        });
    }
};