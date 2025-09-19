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
    }
}
