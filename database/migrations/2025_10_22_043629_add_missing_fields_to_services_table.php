<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsToServicesTable extends Migration
{
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            // Add is_complete if it doesn't exist
            if (!Schema::hasColumn('services', 'is_complete')) {
                $table->boolean('is_complete')->default(false)->after('status');
            }
            
            // Add service_price if it doesn't exist
            if (!Schema::hasColumn('services', 'service_price')) {
                $table->decimal('service_price', 10, 2)->nullable()->after('service_name');
            }
            
            // Add other site tracking fields if they don't exist
            if (!Schema::hasColumn('services', 'other_site_member_id')) {
                $table->string('other_site_member_id')->nullable()->after('photo');
            }
            
            if (!Schema::hasColumn('services', 'profile_source')) {
                $table->string('profile_source')->nullable()->after('other_site_member_id');
            }
            
            if (!Schema::hasColumn('services', 'assigned_by')) {
                $table->string('assigned_by')->nullable()->after('profile_source');
            }
            
            if (!Schema::hasColumn('services', 'assigned_at')) {
                $table->timestamp('assigned_at')->nullable()->after('assigned_by');
            }
            
            if (!Schema::hasColumn('services', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('profile_id');
            }
        });
    }

    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            $columns = [
                'is_complete',
                'service_price',
                'other_site_member_id',
                'profile_source',
                'assigned_by',
                'assigned_at',
                'user_id'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('services', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}