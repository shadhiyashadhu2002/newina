<?php
require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

use Illuminate\Support\Facades\Auth;
use App\Models\Service;
use App\Models\User;

// Start session to check auth
session_start();

echo "=== Debug New Service Page ===\n";

// Check if user is authenticated via session
$sessionData = $_SESSION ?? [];
echo "Session keys: " . implode(', ', array_keys($sessionData)) . "\n";

try {
    // Manually check the database for the logged in user
    $user = User::find(20954); // The user ID from logs
    echo "User found: " . ($user ? 'Yes' : 'No') . "\n";
    if ($user) {
        echo "User ID: {$user->id}\n";
        echo "User Email: {$user->email}\n";
        echo "User Type: {$user->user_type}\n";
        echo "Is Admin: " . ($user->is_admin ? 'Yes' : 'No') . "\n";
        echo "First Name: " . ($user->first_name ?? 'NULL') . "\n";
    }

    // Check total services
    $totalServices = Service::count();
    echo "Total services in DB: {$totalServices}\n";

    // Check services for this user's first_name
    if ($user && $user->first_name) {
        $userServices = Service::where('service_executive', $user->first_name)->count();
        echo "Services for user's first_name ({$user->first_name}): {$userServices}\n";
    }

    // Check staff users
    $staffUsers = User::where('user_type', 'staff')->count();
    echo "Staff users: {$staffUsers}\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}