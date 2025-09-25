<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create staff users for testing
        $staffUsers = [
            [
                'name' => 'john',
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john@staff.com',
                'password' => Hash::make('staff123'),
                'is_admin' => 0,
                'user_type' => 'staff',
                'phone2' => '7777777777',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'sarah',
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah@staff.com',
                'password' => Hash::make('staff123'),
                'is_admin' => 0,
                'user_type' => 'staff',
                'phone2' => '6666666666',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'mike',
                'first_name' => 'Mike',
                'last_name' => 'Wilson',
                'email' => 'mike@staff.com',
                'password' => Hash::make('staff123'),
                'is_admin' => 0,
                'user_type' => 'staff',
                'phone2' => '5555555555',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($staffUsers as $userData) {
            // Use updateOrCreate to avoid duplicate errors
            User::updateOrCreate(
                ['email' => $userData['email']], // Find by email
                $userData // Update or create with this data
            );
        }

        $this->command->info('Staff users created successfully!');
    }
}