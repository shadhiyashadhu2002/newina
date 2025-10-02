<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Bootstrap Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->boot();

echo "Testing Staff User Authentication\n";
echo "================================\n\n";

$email = 'sana@service.com';
$password = '1010';

// Check if user exists
$user = User::where('email', $email)->first();

if (!$user) {
    echo "❌ User $email not found in database\n";
    exit;
}

echo "✅ User found:\n";
echo "   Name: {$user->name}\n";
echo "   Email: {$user->email}\n";
echo "   User Type: {$user->user_type}\n";
echo "   Is Admin: " . ($user->is_admin ? 'Yes' : 'No') . "\n";
echo "   Created: {$user->created_at}\n";
echo "\n";

// Test password
echo "Testing password '$password':\n";
if (Hash::check($password, $user->password)) {
    echo "✅ Password verification: PASS\n";
} else {
    echo "❌ Password verification: FAIL\n";
    echo "   Stored hash: " . substr($user->password, 0, 50) . "...\n";
    
    // Try to reset password
    echo "\n🔄 Resetting password...\n";
    $user->password = Hash::make($password);
    $user->save();
    
    // Test again
    if (Hash::check($password, $user->password)) {
        echo "✅ Password reset successful - verification now PASS\n";
    } else {
        echo "❌ Password reset failed\n";
    }
}

echo "\n";
echo "Hash info:\n";
echo "Expected password: $password\n";
echo "Hash starts with: " . substr($user->password, 0, 20) . "...\n";
echo "Hash length: " . strlen($user->password) . "\n";