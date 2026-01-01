<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fresh_data_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profile_id');
            $table->unsignedBigInteger('assigned_to_staff_id');
            $table->unsignedBigInteger('assigned_by_staff_id');
            $table->enum('profile_type', ['sale', 'service'])->default('sale');
            $table->enum('status', ['assigned', 'contacted', 'converted', 'rejected'])->default('assigned');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();
            
            $table->index('profile_id');
            $table->index(['assigned_to_staff_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('fresh_data_assignments');
    }
};
