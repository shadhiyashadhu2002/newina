<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "Current Staff Users:\n";
echo "===================\n";

$users = User::where('user_type', 'staff')->orderBy('first_name')->get(['first_name', 'email', 'code']);

foreach($users as $user) {
    echo "Name: {$user->first_name}, Email: {$user->email}, Code: {$user->code}\n";
}

echo "\nTotal Staff: " . $users->count() . "\n";

// Check for the specific new executives
$newExecutives = ['Rumsi', 'Thasni', 'Thashfeeha', 'Mufeeda'];
echo "\nChecking for new executives:\n";
foreach($newExecutives as $name) {
    $exists = User::where('user_type', 'staff')->where('first_name', $name)->exists();
    echo "$name: " . ($exists ? "EXISTS" : "NOT FOUND") . "\n";
}