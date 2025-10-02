<?php
require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;
use App\Models\User;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->boot();

// Check staff users
echo "Staff Users Successfully Created:\n";
echo "==================================\n";

$staffEmails = [
    'sana@service.com', 'asnas@sales.com', 'safa@sales.com', 'jumana@service.com', 
    'saniya@service.com', 'safna@service.com', 'shamna@service.com', 'priya@service.com', 
    'jasna@service.com', 'jasmin@service.com', 'sajna@service.com', 'midhuna@service.com', 
    'hima@service.com'
];

$created = [];
$missing = [];

foreach ($staffEmails as $email) {
    $user = User::where('email', $email)->where('user_type', 'staff')->first();
    if ($user) {
        $created[] = $user->name . ' (' . $email . ') - ' . $user->code;
    } else {
        $missing[] = $email;
    }
}

echo "CREATED (" . count($created) . "):\n";
foreach ($created as $user) {
    echo "✓ " . $user . "\n";
}

echo "\nMISSING (" . count($missing) . "):\n";
foreach ($missing as $email) {
    echo "✗ " . $email . "\n";
}

$totalStaff = User::where('user_type', 'staff')->count();
echo "\nTotal staff users in database: " . $totalStaff . "\n";