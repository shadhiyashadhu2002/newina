<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    // First copy discount values into success_fee if success_fee is zero or null
    DB::statement('UPDATE `sales` SET `success_fee` = `discount` WHERE (`success_fee` IS NULL OR `success_fee` = 0) AND (`discount` IS NOT NULL AND `discount` <> 0)');

        Schema::table('sales', function (Blueprint $table) {
            // then drop the discount column
            if (Schema::hasColumn('sales', 'discount')) {
                $table->dropColumn('discount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'discount')) {
                $table->decimal('discount', 10, 2)->default(0)->after('amount');
            }
        });

    // Optionally copy values back from success_fee into discount for safety
    DB::statement('UPDATE `sales` SET `discount` = `success_fee` WHERE (`discount` IS NULL OR `discount` = 0) AND (`success_fee` IS NOT NULL AND `success_fee` <> 0)');
    }
};
