<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Adding new executives...\n";

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
        'created_by' => 1
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
        'created_by' => 1
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
        'created_by' => 1
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
        'created_by' => 1
    ]
];

foreach($newExecutives as $executive) {
    $exists = User::where('email', $executive['email'])->exists();
    
    if (!$exists) {
        User::create($executive);
        echo "Added: {$executive['first_name']} ({$executive['email']})\n";
    } else {
        echo "Already exists: {$executive['first_name']} ({$executive['email']})\n";
    }
}

echo "\nDone! Checking final staff list...\n";
$staff = User::where('user_type', 'staff')->orderBy('first_name')->get(['first_name', 'email']);
echo "Total staff members: " . $staff->count() . "\n";

// Check for the new executives
echo "\nNew executives verification:\n";
foreach(['Rumsi', 'Thasni', 'Thashfeeha', 'Mufeeda'] as $name) {
    $exists = User::where('user_type', 'staff')->where('first_name', $name)->exists();
    echo "$name: " . ($exists ? "✓ EXISTS" : "✗ NOT FOUND") . "\n";
}