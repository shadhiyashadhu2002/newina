<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('profile_history', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('profile_id')->unsigned();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->string('executive_name')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('status')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->text('remarks')->nullable();
            $table->string('imid')->nullable();
            $table->date('assigned_date')->nullable();
            $table->string('action_type')->default('update'); // create, update, assign
            $table->timestamps();
            
            $table->foreign('profile_id')->references('id')->on('fresh_data')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('profile_history');
    }
};
