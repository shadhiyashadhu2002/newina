<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('fresh_data', 'customer_name')) {
            Schema::table('fresh_data', function (Blueprint $table) {
                $table->string('customer_name')->nullable()->after('name');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('fresh_data', 'customer_name')) {
            Schema::table('fresh_data', function (Blueprint $table) {
                $table->dropColumn('customer_name');
            });
        }
    }
};
