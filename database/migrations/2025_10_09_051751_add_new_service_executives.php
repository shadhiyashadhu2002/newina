<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 4 new service executives
        $newExecutives = [
            [
                'name' => 'Rumsi Service',
                'first_name' => 'Rumsi',
                'email' => 'rumsi@service.com',
                'password' => Hash::make('rumsi123'),
                'user_type' => 'staff',
                'is_admin' => false,
                'code' => 'STAFF008',
                'gender' => 'Female',
                'phone' => '9876543218',
                'phone2' => '9876543218',
                'mobile_number_1' => '9876543218',
                'whatsapp_number' => '9876543218',
                'welcome_call_completed' => false,
                'comments' => 'Service Executive',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Thasni Service',
                'first_name' => 'Thasni',
                'email' => 'thasni@service.com',
                'password' => Hash::make('thasni123'),
                'user_type' => 'staff',
                'is_admin' => false,
                'code' => 'STAFF009',
                'gender' => 'Female',
                'phone' => '9876543219',
                'phone2' => '9876543219',
                'mobile_number_1' => '9876543219',
                'whatsapp_number' => '9876543219',
                'welcome_call_completed' => false,
                'comments' => 'Service Executive',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Thashfeeha Service',
                'first_name' => 'Thashfeeha',
                'email' => 'thashfeeha@service.com',
                'password' => Hash::make('thashfeeha123'),
                'user_type' => 'staff',
                'is_admin' => false,
                'code' => 'STAFF010',
                'gender' => 'Female',
                'phone' => '9876543220',
                'phone2' => '9876543220',
                'mobile_number_1' => '9876543220',
                'whatsapp_number' => '9876543220',
                'welcome_call_completed' => false,
                'comments' => 'Service Executive',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Mufeeda Service',
                'first_name' => 'Mufeeda',
                'email' => 'mufeeda@service.com',
                'password' => Hash::make('mufeeda123'),
                'user_type' => 'staff',
                'is_admin' => false,
                'code' => 'STAFF011',
                'gender' => 'Female',
                'phone' => '9876543221',
                'phone2' => '9876543221',
                'mobile_number_1' => '9876543221',
                'whatsapp_number' => '9876543221',
                'welcome_call_completed' => false,
                'comments' => 'Service Executive',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach($newExecutives as $executive) {
            // Check if user already exists to avoid duplicates
            $exists = User::where('email', $executive['email'])->exists();
            
            if (!$exists) {
                User::create($executive);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the 4 new executives
        $emails = [
            'rumsi@service.com',
            'thasni@service.com', 
            'thashfeeha@service.com',
            'mufeeda@service.com'
        ];
        
        User::whereIn('email', $emails)->delete();
    }
};
