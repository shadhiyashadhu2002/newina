<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StaffUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                // Array of staff users with their specific details
        $staffUsers = [
            [
                'name' => 'Sana Service',
                'first_name' => 'Sana',
                'email' => 'sana@service.com',
                'password' => 'sana123',  // Plain password - will be hashed automatically
                'user_type' => 'staff',
                'is_admin' => false,
                'code' => 'STAFF001',
                'gender' => 'Female',
                'phone' => '9895134410',
                'phone2' => '9895134410',
                'mobile_number_1' => '9895134410',
                'mobile_number_2' => null,
                'whatsapp_number' => '9895134410',
                'welcome_call_completed' => false,
                'comments' => 'Customer Service Representative',
                'created_by' => 1, // Admin user ID who created this
            ],
            // ADD MORE STAFF MEMBERS HERE - Copy this structure for each staff member
            /*
            [
                'name' => 'Full Name Here',
                'first_name' => 'First Name',
                'email' => 'email@company.com',
                'password' => 'password123',
                'user_type' => 'staff',
                'is_admin' => false,
                'code' => 'STAFF002',
                'gender' => 'Male/Female/Other',
                'mobile_number_1' => 'phone_number',
                'mobile_number_2' => null,
                'whatsapp_number' => 'whatsapp_number',
                'welcome_call_completed' => false,
                'comments' => 'Role/Department',
                'created_by' => 1,
            ],
            */
            [
                'name' => 'Asna',
                'first_name' => 'Asna',
                'email' => 'asna@sales.com',
                'password' => '1007',
                'user_type' => 'staff',
                'is_admin' => false,
                'code' => 'STAFF003',
                'gender' => 'Female',
                'phone' => '9876543210',
                'phone2' => '9876543210',
                'mobile_number_1' => '9876543210',
                'mobile_number_2' => null,
                'whatsapp_number' => '9876543210',
                'welcome_call_completed' => false,
                'comments' => 'Sales Executive',
                'created_by' => 1,
            ],
            // ADD MORE STAFF MEMBERS HERE - Just copy the array structure above
            
            [
                'name' => 'Safa',
                'first_name' => 'Safa',
                'email' => 'safa@sales.com',
                'password' => '1008',
                'user_type' => 'staff',
                'is_admin' => false,
                'code' => 'STAFF004',
                'gender' => 'Female',
                'phone' => '8765432109',
                'phone2' => '8765432109',
                'mobile_number_1' => '8765432109',
                'mobile_number_2' => null,
                'whatsapp_number' => '8765432109',
                'welcome_call_completed' => false,
                'comments' => 'Sales Executive',
                'created_by' => 1,
            ],
              [
                'name' => 'Jumana',
                'first_name' => 'Jumana',
                'email' => 'jumana@service.com',
                'password' => '1010',
                'user_type' => 'staff',
                'is_admin' => false,
                'code' => 'STAFF005',
                'gender' => 'Female',
                'phone' => '7654321098',
                'phone2' => '7654321098',
                'mobile_number_1' => '7654321098',
                'mobile_number_2' => null,
                'whatsapp_number' => '7654321098',
                'welcome_call_completed' => false,
                'comments' => 'Service Executive',
                'created_by' => 1,
            ],
             [
                'name' => 'Saniya',
                'first_name' => 'Saniya',
                'email' => 'saniya@service.com',
                'password' => '1012',
                'user_type' => 'staff',
                'is_admin' => false,
                'code' => 'STAFF006',
                'gender' => 'Female',
                'phone' => '6543210987',
                'phone2' => '6543210987',
                'mobile_number_1' => '6543210987',
                'mobile_number_2' => null,
                'whatsapp_number' => '6543210987',
                'welcome_call_completed' => false,
                'comments' => 'Service Executive',
                'created_by' => 1,
            ],
              [
                'name' => 'Safna',
                'first_name' => 'Safna',
                'email' => 'safna@service.com',
                'password' => '1015',
                'user_type' => 'staff',
                'is_admin' => false,
                'code' => 'STAFF007',
                'gender' => 'Female',
                'phone' => '5432109876',
                'phone2' => '5432109876',
                'mobile_number_1' => '5432109876',
                'mobile_number_2' => null,
                'whatsapp_number' => '5432109876',
                'welcome_call_completed' => false,
                'comments' => 'Service Executive',
                'created_by' => 1,
            ],
             [
                'name' => 'Shamna',
                'first_name' => 'Shamna',
                'email' => 'shamna@service.com',
                'password' => '1016',
                'user_type' => 'staff',
                'is_admin' => false,
                'code' => 'STAFF008',
                'gender' => 'Female',
                'phone' => '4321098765',
                'phone2' => '4321098765',
                'mobile_number_1' => '4321098765',
                'mobile_number_2' => null,
                'whatsapp_number' => '4321098765',
                'welcome_call_completed' => false,
                'comments' => 'Service Executive',
                'created_by' => 1,
            ],
             [
                'name' => 'Priya',
                'first_name' => 'Priya',
                'email' => 'priya@service.com',
                'password' => '1017',
                'user_type' => 'staff',
                'is_admin' => false,
                'code' => 'STAFF009',
                'gender' => 'Female',
                'phone' => '3210987654',
                'phone2' => '3210987654',
                'mobile_number_1' => '3210987654',
                'mobile_number_2' => null,
                'whatsapp_number' => '3210987654',
                'welcome_call_completed' => false,
                'comments' => 'Service Executive',
                'created_by' => 1,
            ],
            [
                'name' => 'Jasna',
                'first_name' => 'Jasna',
                'email' => 'jasna@service.com',
                'password' => '1019',
                'user_type' => 'staff',
                'is_admin' => false,
                'code' => 'STAFF010',
                'gender' => 'Female',
                'phone' => '2109876543',
                'phone2' => '2109876543',
                'mobile_number_1' => '2109876543',
                'mobile_number_2' => null,
                'whatsapp_number' => '2109876543',
                'welcome_call_completed' => false,
                'comments' => 'Service Executive',
                'created_by' => 1,
            ],
            [
                'name' => 'Jasmin',
                'first_name' => 'Jasmin',
                'email' => 'jasmin@service.com',
                'password' => '1020',
                'user_type' => 'staff',
                'is_admin' => false,
                'code' => 'STAFF011',
                'gender' => 'Female',
                'phone' => '1098765432',
                'phone2' => '1098765432',
                'mobile_number_1' => '1098765432',
                'mobile_number_2' => null,
                'whatsapp_number' => '1098765432',
                'welcome_call_completed' => false,
                'comments' => 'Service Executive',
                'created_by' => 1,
            ],
             [
                'name' => 'Sajna',
                'first_name' => 'Sajna',
                'email' => 'sajna@service.com',
                'password' => '1024',
                'user_type' => 'staff',
                'is_admin' => false,
                'code' => 'STAFF012',
                'gender' => 'Female',
                'phone' => '0987654321',
                'phone2' => '0987654321',
                'mobile_number_1' => '0987654321',
                'mobile_number_2' => null,
                'whatsapp_number' => '0987654321',
                'welcome_call_completed' => false,
                'comments' => 'Service Executive',
                'created_by' => 1,
            ],
            [
                'name' => 'Midhuna',
                'first_name' => 'Midhuna',
                'email' => 'midhuna@service.com',
                'password' => '1025',
                'user_type' => 'staff',
                'is_admin' => false,
                'code' => 'STAFF013',
                'gender' => 'Female',
                'phone' => '9876543201',
                'phone2' => '9876543201',
                'mobile_number_1' => '9876543201',
                'mobile_number_2' => null,
                'whatsapp_number' => '9876543201',
                'welcome_call_completed' => false,
                'comments' => 'Service Executive',
                'created_by' => 1,
            ],
             [
                'name' => 'Hima',
                'first_name' => 'Hima',
                'email' => 'hima@service.com',
                'password' => '1028',
                'user_type' => 'staff',
                'is_admin' => false,
                'code' => 'STAFF014',
                'gender' => 'Female',
                'phone' => '8765432101',
                'phone2' => '8765432101',
                'mobile_number_1' => '8765432101',
                'mobile_number_2' => null,
                'whatsapp_number' => '8765432101',
                'welcome_call_completed' => false,
                'comments' => 'Service Executive',
                'created_by' => 1,
            ],
            
            
        ];

        // Create each staff user
        foreach ($staffUsers as $staffData) {
            // Hash the password before creating user
            $staffData['password'] = Hash::make($staffData['password']);
            
            // Check if user already exists to avoid duplicates
            $existingUser = User::where('email', $staffData['email'])->first();
            
            if (!$existingUser) {
                User::create($staffData);
                $this->command->info("Created staff user: {$staffData['name']} ({$staffData['email']})");
            } else {
                $this->command->warn("Staff user already exists: {$staffData['email']}");
            }
        }
        
        $this->command->info('Staff users seeding completed!');
    }
}
