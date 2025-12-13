<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('emp_code')->unique();
            $table->string('name');
            $table->string('emergency_mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('aadhar_card_no')->nullable();
            $table->text('address')->nullable();
            $table->date('date_of_joining')->nullable();
            $table->string('designation')->nullable();
            $table->string('department')->nullable();
            $table->string('company')->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
