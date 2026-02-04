<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "Setting Sreya's team to 'service'...\n";

// Try to find by name in uppercase first
$user = User::where('name', 'SREYA')->first();
if(!$user) {
    // fallback to first_name search (case-insensitive)
    $user = User::whereRaw('LOWER(first_name) = ?', [strtolower('Sreya')])->first();
}

if($user) {
    $old = $user->team;
    $user->update(['team' => 'service']);
    echo "Updated user ID {$user->id}: first_name='{$user->first_name}', name='{$user->name}', old team='{$old}', new team='service'\n";
} else {
    echo "User Sreya not found.\n";
}
