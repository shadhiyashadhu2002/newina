<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('helpline_queries', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('call_status')->nullable(); // Whatsapp, Call, etc.
            $table->string('nature_of_call')->nullable(); // Whatsapp Message, etc.
            $table->string('video_source')->nullable(); // Instagram, Facebook, etc.
            $table->string('video_reference')->nullable(); // Advertisement, etc.
            $table->string('mobile_number')->nullable();
            $table->string('profile_id')->nullable();
            $table->string('executive_name')->nullable();
            $table->text('remarks')->nullable();
            $table->string('purpose')->nullable(); // Product Information, etc.
            $table->boolean('new_lead')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('helpline_queries');
    }
};
