<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Bootstrap the Laravel application so we can use Eloquent
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->boot();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Users to ensure exist
$usersToCreate = [
    ['email' => 'thasni@service.com', 'first_name' => 'Thasni', 'name' => 'Thasni', 'password' => 'thasni123'],
    ['email' => 'remsi@service.com', 'first_name' => 'Remsi', 'name' => 'Remsi', 'password' => 'remsi123'],
];

// Debug: print DB name from config to confirm CLI uses same DB
try {
    echo "DB (CLI) database: " . config('database.connections.mysql.database') . "\n";
} catch (Exception $e) {
    echo "DB config not available: " . $e->getMessage() . "\n";
}

foreach ($usersToCreate as $u) {
    try {
        echo "Ensuring user: {$u['email']}\n";

        $values = [
            'first_name' => $u['first_name'],
            'name' => $u['name'],
            'user_type' => 'staff',
            'is_admin' => 0,
        ];

        // Prepare hash
        if (class_exists('\Illuminate\Support\Facades\Hash')) {
            $values['password'] = \Illuminate\Support\Facades\Hash::make($u['password']);
        } else {
            $values['password'] = password_hash($u['password'], PASSWORD_BCRYPT);
        }

        // Set safe flags if columns exist
        try { $values['approved'] = 1; } catch (Exception $e) {}
        try { $values['blocked'] = 0; } catch (Exception $e) {}
        try { $values['deactivated'] = 0; } catch (Exception $e) {}
        try { $values['email_verified_at'] = date('Y-m-d H:i:s'); } catch (Exception $e) {}

        // Use updateOrCreate to force presence
        $user = User::updateOrCreate(['email' => $u['email']], $values);

        if ($user->wasRecentlyCreated) {
            echo "Created: {$user->email} (ID={$user->id})\n";
        } else {
            echo "Updated/existing: {$user->email} (ID={$user->id})\n";
        }

    } catch (Exception $e) {
        echo "Error ensuring user {$u['email']}: " . $e->getMessage() . "\n";
    }
}
