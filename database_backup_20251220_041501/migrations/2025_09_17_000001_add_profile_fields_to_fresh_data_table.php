<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('fresh_data')) {
            return;
        }

        if (!Schema::hasColumn('fresh_data', 'gender')) {
            Schema::table('fresh_data', function (Blueprint $table) {
                $table->string('gender')->nullable();
            });
        }

        if (!Schema::hasColumn('fresh_data', 'registration_date')) {
            Schema::table('fresh_data', function (Blueprint $table) {
                $table->date('registration_date')->nullable();
            });
        }

        if (!Schema::hasColumn('fresh_data', 'profile_id')) {
            Schema::table('fresh_data', function (Blueprint $table) {
                $table->string('profile_id')->nullable();
            });
        }

        if (!Schema::hasColumn('fresh_data', 'mobile_number_2')) {
            Schema::table('fresh_data', function (Blueprint $table) {
                $table->string('mobile_number_2')->nullable();
            });
        }

        if (!Schema::hasColumn('fresh_data', 'whatsapp_number')) {
            Schema::table('fresh_data', function (Blueprint $table) {
                $table->string('whatsapp_number')->nullable();
            });
        }

        if (!Schema::hasColumn('fresh_data', 'profile_created')) {
            Schema::table('fresh_data', function (Blueprint $table) {
                $table->boolean('profile_created')->default(false);
            });
        }

        if (!Schema::hasColumn('fresh_data', 'photo_uploaded')) {
            Schema::table('fresh_data', function (Blueprint $table) {
                $table->boolean('photo_uploaded')->default(false);
            });
        }

        if (!Schema::hasColumn('fresh_data', 'welcome_call')) {
            Schema::table('fresh_data', function (Blueprint $table) {
                $table->boolean('welcome_call')->default(false);
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('fresh_data')) {
            return;
        }

        if (Schema::hasColumn('fresh_data', 'gender')) {
            Schema::table('fresh_data', function (Blueprint $table) {
                $table->dropColumn('gender');
            });
        }
        if (Schema::hasColumn('fresh_data', 'registration_date')) {
            Schema::table('fresh_data', function (Blueprint $table) {
                $table->dropColumn('registration_date');
            });
        }
        if (Schema::hasColumn('fresh_data', 'profile_id')) {
            Schema::table('fresh_data', function (Blueprint $table) {
                $table->dropColumn('profile_id');
            });
        }
        if (Schema::hasColumn('fresh_data', 'mobile_number_2')) {
            Schema::table('fresh_data', function (Blueprint $table) {
                $table->dropColumn('mobile_number_2');
            });
        }
        if (Schema::hasColumn('fresh_data', 'whatsapp_number')) {
            Schema::table('fresh_data', function (Blueprint $table) {
                $table->dropColumn('whatsapp_number');
            });
        }
        if (Schema::hasColumn('fresh_data', 'profile_created')) {
            Schema::table('fresh_data', function (Blueprint $table) {
                $table->dropColumn('profile_created');
            });
        }
        if (Schema::hasColumn('fresh_data', 'photo_uploaded')) {
            Schema::table('fresh_data', function (Blueprint $table) {
                $table->dropColumn('photo_uploaded');
            });
        }
        if (Schema::hasColumn('fresh_data', 'welcome_call')) {
            Schema::table('fresh_data', function (Blueprint $table) {
                $table->dropColumn('welcome_call');
            });
        }
    }
};
