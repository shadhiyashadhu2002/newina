<?php

require_once 'vendor/autoload.php';
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->boot();

echo "Testing Staff User Login Credentials\n";
echo "====================================\n\n";

// Test credentials for staff users
$testCredentials = [
    ['email' => 'sana@service.com', 'password' => '1010'],
    ['email' => 'asnas@sales.com', 'password' => '1011'], 
    ['email' => 'shamna@service.com', 'password' => '1016'],
    ['email' => 'priya@service.com', 'password' => '1017']
];

foreach ($testCredentials as $creds) {
    $user = User::where('email', $creds['email'])->first();
    
    if (!$user) {
        echo "❌ User {$creds['email']} not found in database\n";
        continue;
    }
    
    echo "Testing: {$user->name} ({$creds['email']})\n";
    echo "  - User exists: ✓\n";
    echo "  - User type: {$user->user_type}\n";
    echo "  - Is admin: " . ($user->is_admin ? 'Yes' : 'No') . "\n";
    
    // Test password
    if (Hash::check($creds['password'], $user->password)) {
        echo "  - Password check: ✅ VALID\n";
    } else {
        echo "  - Password check: ❌ INVALID\n";
        echo "  - Expected: {$creds['password']}\n";
        echo "  - Hash in DB: " . substr($user->password, 0, 30) . "...\n";
    }
    echo "\n";
}

// Also check what the current password hash should be
echo "Password Hash Test:\n";
echo "==================\n";
$testHash = Hash::make('1010');
echo "Hash for '1010': " . substr($testHash, 0, 30) . "...\n";
echo "Verify test: " . (Hash::check('1010', $testHash) ? 'PASS' : 'FAIL') . "\n";