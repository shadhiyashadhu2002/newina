<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('annual_salary_ranges')) {
            Schema::create('annual_salary_ranges', function (Blueprint $table) {
                $table->id();
                $table->decimal('min_salary', 15, 2);
                $table->decimal('max_salary', 15, 2);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('annual_salary_ranges');
    }
};
