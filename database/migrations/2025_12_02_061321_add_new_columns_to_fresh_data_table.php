<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('fresh_data', function (Blueprint $table) {
            $table->string('secondary_phone', 20)->nullable()->after('imid');
            $table->enum('is_new_lead', ['yes', 'no'])->nullable()->after('secondary_phone');
        });
    }

    public function down()
    {
        Schema::table('fresh_data', function (Blueprint $table) {
            $table->dropColumn(['secondary_phone', 'is_new_lead']);
        });
    }
};
