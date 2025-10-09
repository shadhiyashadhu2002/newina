<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "Verifying staff data that should appear in dropdowns:\n";
echo "=====================================================\n";

$staffUsers = User::where('user_type', 'staff')
                  ->orderBy('first_name')
                  ->get(['id', 'first_name', 'name']);

echo "Staff users count: " . $staffUsers->count() . "\n\n";

echo "Staff members that should appear in dropdown:\n";
foreach($staffUsers as $staff) {
    echo "- {$staff->first_name} (ID: {$staff->id})\n";
}

echo "\nChecking specifically for the 4 new executives:\n";
$newExecs = ['Rumsi', 'Thasni', 'Thashfeeha', 'Mufeeda'];
foreach($newExecs as $name) {
    $user = User::where('user_type', 'staff')->where('first_name', $name)->first();
    if($user) {
        echo "✓ {$name}: Found (ID: {$user->id}, Email: {$user->email})\n";
    } else {
        echo "✗ {$name}: NOT FOUND\n";
    }
}