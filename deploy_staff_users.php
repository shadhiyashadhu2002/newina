<?php
/**
 * Deployment Script for Staff Users
 * 
 * This script can be run on your server to add staff users to the database
 * Run this after uploading your code to the server
 */

// Include Laravel bootstrap
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Application;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Starting staff users deployment...\n";
    
    // Run the staff users seeder
    Artisan::call('db:seed', [
        '--class' => 'StaffUsersSeeder'
    ]);
    
    echo "✅ Staff users seeder completed successfully!\n";
    echo "Output: " . Artisan::output() . "\n";
    
    // Show created staff users
    $staffUsers = \App\Models\User::where('user_type', 'staff')->get();
    echo "\n📋 Staff Users in Database:\n";
    echo "==========================\n";
    
    foreach ($staffUsers as $user) {
        echo "• {$user->name} ({$user->email}) - Code: {$user->code}\n";
    }
    
    echo "\n🎉 Deployment completed successfully!\n";
    echo "All staff users are now available on the server.\n";
    
} catch (Exception $e) {
    echo "❌ Error during deployment: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}