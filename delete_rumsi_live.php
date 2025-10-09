<?php
/**
 * Script to DELETE Ramsi/Rumsi from live server
 * Run this on the live server to remove the unwanted executive
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "DELETING Rumsi from live server...\n";
echo "==================================\n";

$deleted = 0;
$notFound = 0;

// Target specifically: Rumsi (with 'u')
$names = ['Rumsi'];
$emails = ['rumsi@service.com'];

echo "Searching for Rumsi users...\n";

foreach($names as $name) {
    echo "\nChecking for user with name: {$name}...\n";
    
    $user = User::where('user_type', 'staff')->where('first_name', $name)->first();
    
    if ($user) {
        echo "  Found: {$user->first_name} (ID: {$user->id}, Email: {$user->email})\n";
        echo "  Deleting...\n";
        
        try {
            $user->delete();
            echo "  ✓ Successfully deleted {$user->first_name}\n";
            $deleted++;
        } catch (Exception $e) {
            echo "  ✗ Error deleting {$user->first_name}: " . $e->getMessage() . "\n";
        }
    } else {
        echo "  - No user found with name: {$name}\n";
        $notFound++;
    }
}

// Also check by email in case name is different
echo "\nChecking by email addresses...\n";
foreach($emails as $email) {
    echo "\nChecking for email: {$email}...\n";
    
    $user = User::where('email', $email)->first();
    
    if ($user) {
        echo "  Found: {$user->first_name} (ID: {$user->id}, Email: {$user->email})\n";
        
        // Check if we already deleted this user
        if (!User::where('id', $user->id)->exists()) {
            echo "  - Already deleted\n";
        } else {
            echo "  Deleting...\n";
            try {
                $user->delete();
                echo "  ✓ Successfully deleted user with email {$email}\n";
                $deleted++;
            } catch (Exception $e) {
                echo "  ✗ Error deleting user: " . $e->getMessage() . "\n";
            }
        }
    } else {
        echo "  - No user found with email: {$email}\n";
        $notFound++;
    }
}

echo "\n=== SUMMARY ===\n";
echo "Deleted: $deleted users\n";
echo "Not found: $notFound searches returned no results\n";

// Verification - check if Rumsi user still exists
echo "\n=== VERIFICATION ===\n";
$stillExists = false;

foreach($names as $name) {
    $exists = User::where('user_type', 'staff')->where('first_name', $name)->exists();
    echo "{$name}: " . ($exists ? "STILL EXISTS ❌" : "NOT FOUND ✓") . "\n";
    if ($exists) $stillExists = true;
}

foreach($emails as $email) {
    $exists = User::where('email', $email)->exists();
    echo "{$email}: " . ($exists ? "STILL EXISTS ❌" : "NOT FOUND ✓") . "\n";
    if ($exists) $stillExists = true;
}

if (!$stillExists) {
    echo "\n✅ SUCCESS: Rumsi user has been deleted from the live server!\n";
} else {
    echo "\n⚠️  WARNING: Rumsi user may still exist. Check manually.\n";
}

// Show current staff list
echo "\n=== CURRENT STAFF MEMBERS ===\n";
$staff = User::where('user_type', 'staff')->orderBy('first_name')->get(['first_name', 'email', 'code']);

echo "Total staff members: " . $staff->count() . "\n";
foreach($staff as $s) {
    echo "- {$s->first_name} ({$s->email}) [{$s->code}]\n";
}

echo "\n=== NEXT STEPS ===\n";
echo "1. Verify Rumsi is not in the list above\n";
echo "2. Run the add_executives_safe.php script to add the 3 remaining executives\n";
echo "3. Clear cache: php artisan cache:clear\n";
echo "4. Test the Service Executive dropdown\n";

echo "\nDone!\n";