<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            // Add phone column if not exists
            if (!Schema::hasColumn('sales', 'phone')) {
                $table->string('phone', 20)->nullable()->after('profile_id');
            }

            // Add discount column if not exists
            if (!Schema::hasColumn('sales', 'discount')) {
                $table->decimal('discount', 10, 2)->default(0)->after('success_fee');
            }

            // Make profile_id nullable if it exists and not nullable
            if (Schema::hasColumn('sales', 'profile_id')) {
                try {
                    $table->string('profile_id')->nullable()->change();
                } catch (\Exception $e) {
                    // Some DB drivers/versions cannot change column types easily; ignore if it fails
                }
            }

            // Add staff_id if missing
            if (!Schema::hasColumn('sales', 'staff_id')) {
                $table->unsignedBigInteger('staff_id')->nullable()->after('status');
            }

            // Add created_by if missing
            if (!Schema::hasColumn('sales', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('notes');
            }
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('sales', 'discount')) {
                $table->dropColumn('discount');
            }
            // Do not revert profile_id nullability or drop staff/created_by here for safety
        });
    }
};
