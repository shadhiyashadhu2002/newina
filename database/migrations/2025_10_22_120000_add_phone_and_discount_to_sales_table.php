<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'phone')) {
                $table->string('phone')->nullable()->after('amount');
            }
            if (!Schema::hasColumn('sales', 'discount')) {
                $table->decimal('discount', 10, 2)->default(0)->after('success_fee');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (Schema::hasColumn('sales', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('sales', 'discount')) {
                $table->dropColumn('discount');
            }
        });
    }
};
