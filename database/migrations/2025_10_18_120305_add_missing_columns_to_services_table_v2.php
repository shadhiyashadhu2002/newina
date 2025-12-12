<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'remarks')) {
                $table->text('remarks')->nullable();
            }
            if (!Schema::hasColumn('services', 'other_site_member_id')) {
                $table->string('other_site_member_id')->nullable();
            }
            if (!Schema::hasColumn('services', 'profile_source')) {
                $table->string('profile_source')->nullable();
            }
            if (!Schema::hasColumn('services', 'contact_numbers')) {
                $table->string('contact_numbers')->nullable();
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
            Schema::dropColumns('services', ['remarks', 'other_site_member_id', 'profile_source', 'contact_numbers', 'assigned_by', 'assigned_at', 'photo']);
        });
    }
};