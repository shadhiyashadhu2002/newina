<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Updating password for hr@inamatrimony.com...\n";
echo "============================================\n\n";

$user = User::where('email', 'hr@inamatrimony.com')->first();

if($user) {
    $newPassword = '##hrinamatrimony';
    $user->update(['password' => Hash::make($newPassword)]);
    
    echo "✓ Password updated successfully!\n";
    echo "  - Email: {$user->email}\n";
    echo "  - User ID: {$user->id}\n";
    echo "  - Name: {$user->name}\n";
    echo "  - New Password: {$newPassword}\n\n";
    echo "User can now login with this password.\n";
} else {
    echo "✗ User with email hr@inamatrimony.com not found.\n";
}
