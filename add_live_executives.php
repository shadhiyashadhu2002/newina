<?php
/**
 * Script to add 4 new service executives to live server
 * Run this on the live server to add: Rumsi, Thasni, Thashfeeha, Mufeeda
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Adding 4 new service executives to live server...\n";
echo "================================================\n";

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

$added = 0;
$skipped = 0;

foreach($newExecutives as $executive) {
    $exists = User::where('email', $executive['email'])->exists();
    
    if (!$exists) {
        try {
            User::create($executive);
            echo "✓ Added: {$executive['first_name']} ({$executive['email']})\n";
            $added++;
        } catch (Exception $e) {
            echo "✗ Error adding {$executive['first_name']}: " . $e->getMessage() . "\n";
        }
    } else {
        echo "- Skipped: {$executive['first_name']} (already exists)\n";
        $skipped++;
    }
}

echo "\n=== SUMMARY ===\n";
echo "Added: $added executives\n";
echo "Skipped: $skipped executives (already exist)\n";

// Verify the executives are now in the database
echo "\n=== VERIFICATION ===\n";
$names = ['Rumsi', 'Thasni', 'Thashfeeha', 'Mufeeda'];
foreach($names as $name) {
    $user = User::where('user_type', 'staff')->where('first_name', $name)->first();
    if($user) {
        echo "✓ {$name}: Found in database (ID: {$user->id})\n";
    } else {
        echo "✗ {$name}: NOT FOUND in database\n";
    }
}

// Show total staff count
$totalStaff = User::where('user_type', 'staff')->count();
echo "\nTotal staff members now: $totalStaff\n";
echo "\nDone! The 4 new executives should now appear in the service executive dropdown.\n";