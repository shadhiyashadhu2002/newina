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
        Schema::table('services', function (Blueprint $table) {
            $table->boolean('deleted')->default(0)->after('edit_flag');
            $table->text('delete_comment')->nullable()->after('deleted');
            $table->timestamp('deleted_at')->nullable()->after('delete_comment');
            $table->unsignedBigInteger('deleted_by')->nullable()->after('deleted_at');
            
            // Add foreign key for deleted_by
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['deleted_by']);
            $table->dropColumn(['deleted', 'delete_comment', 'deleted_at', 'deleted_by']);
        });
    }
};
