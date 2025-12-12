<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('fresh_data', function (Blueprint $table) {
            if (!Schema::hasColumn('fresh_data', 'secondary_phone')) {
                $table->string('secondary_phone', 20)->nullable();
            }
            if (!Schema::hasColumn('fresh_data', 'is_new_lead')) {
                $table->enum('is_new_lead', ['yes', 'no'])->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('fresh_data', function (Blueprint $table) {
            $table->dropColumn(['secondary_phone', 'is_new_lead']);
        });
    }
};
