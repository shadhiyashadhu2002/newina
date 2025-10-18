<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'photo')) {
                $table->unsignedBigInteger('photo')->nullable()->after('remarks');
            }
        });
    }

    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            if (Schema::hasColumn('services', 'photo')) {
                $table->dropColumn('photo');
            }
        });
    }
};
