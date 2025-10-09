<?php
/**
 * Safe script to add 4 new service executives to live server
 * This script automatically finds available staff codes
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Adding 4 new service executives to live server (SAFE VERSION)...\n";
echo "=================================================================\n";

// Function to find next available staff code
function getNextStaffCode() {
    $lastCode = User::where('code', 'LIKE', 'STAFF%')
                   ->whereNotNull('code')
                   ->orderBy('code', 'desc')
                   ->value('code');
    
    if ($lastCode) {
        // Extract number from code like "STAFF008" -> 8
        $number = (int) str_replace('STAFF', '', $lastCode);
        return 'STAFF' . str_pad($number + 1, 3, '0', STR_PAD_LEFT);
    }
    
    return 'STAFF001';
}

echo "Finding available staff codes...\n";

// Get current highest staff code
$existingCodes = User::where('code', 'LIKE', 'STAFF%')
                    ->whereNotNull('code')
                    ->orderBy('code', 'desc')
                    ->pluck('code')
                    ->toArray();

echo "Current staff codes: " . implode(', ', array_slice($existingCodes, 0, 5)) . (count($existingCodes) > 5 ? '...' : '') . "\n";

$newExecutives = [
    [
        'name' => 'Rumsi Service',
        'first_name' => 'Rumsi',
        'email' => 'rumsi@service.com',
        'password' => Hash::make('rumsi123'),
        'user_type' => 'staff',
        'is_admin' => false,
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

echo "\nProcessing executives...\n";

foreach($newExecutives as $executive) {
    echo "\nChecking: {$executive['first_name']}...\n";
    
    // Check if user already exists by email
    $existsByEmail = User::where('email', $executive['email'])->exists();
    
    // Check if user already exists by name
    $existsByName = User::where('user_type', 'staff')->where('first_name', $executive['first_name'])->exists();
    
    if (!$existsByEmail && !$existsByName) {
        try {
            // Get next available staff code
            $newCode = getNextStaffCode();
            $executive['code'] = $newCode;
            
            echo "  - Assigning code: {$newCode}\n";
            
            User::create($executive);
            echo "  ✓ Added: {$executive['first_name']} ({$executive['email']}) with code {$newCode}\n";
            $added++;
        } catch (Exception $e) {
            echo "  ✗ Error adding {$executive['first_name']}: " . $e->getMessage() . "\n";
        }
    } else {
        if ($existsByEmail) {
            echo "  - Skipped: {$executive['first_name']} (email already exists)\n";
        } else {
            echo "  - Skipped: {$executive['first_name']} (name already exists)\n";
        }
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
        echo "✓ {$name}: Found (ID: {$user->id}, Code: {$user->code}, Email: {$user->email})\n";
    } else {
        echo "✗ {$name}: NOT FOUND\n";
    }
}

// Show total staff count
$totalStaff = User::where('user_type', 'staff')->count();
echo "\nTotal staff members: $totalStaff\n";

// Show all staff for verification
echo "\n=== ALL STAFF MEMBERS ===\n";
$allStaff = User::where('user_type', 'staff')->orderBy('first_name')->get(['first_name', 'code', 'email']);
foreach($allStaff as $staff) {
    echo "- {$staff->first_name} ({$staff->code}) - {$staff->email}\n";
}

echo "\nDone! The new executives should now appear in the service executive dropdown.\n";
echo "Please clear cache if needed: php artisan cache:clear\n";