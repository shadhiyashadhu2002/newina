<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create a default test user
        User::factory()->create([
            'name' => 'Test User',
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'phone2' => '9999999999',
        ]);

        // Create an admin user 'shadhiya' with password 'shadhiya123'
        \App\Models\User::create([
            'name' => 'shadhiya',
            'first_name' => 'shadhiya',
            'last_name' => 'shajahan',
            'email' => 'shadhiya@admin.com',
            'password' => bcrypt('shadhiya123'),
            'is_admin' => 1,
            'phone2' => '8888888888',
        ]);

        // Create a staff user 'john' with password 'staff123'
        \App\Models\User::create([
            'name' => 'john',
            'first_name' => 'John',
            'last_name' => 'Smith',
            'email' => 'john@staff.com',
            'password' => bcrypt('staff123'),
            'is_admin' => 0,
            'user_type' => 'staff',
            'phone2' => '7777777777',
        ]);

        // Create another staff user 'sarah' with password 'staff123'
        \App\Models\User::create([
            'name' => 'sarah',
            'first_name' => 'Sarah',
            'last_name' => 'Johnson',
            'email' => 'sarah@staff.com',
            'password' => bcrypt('staff123'),
            'is_admin' => 0,
            'user_type' => 'staff',
            'phone2' => '6666666666',
        ]);
    }
}
