<?php
/**
 * Server Session Fix Script
 * 
 * This script will:
 * 1. Create sessions table if it doesn't exist
 * 2. Test authentication 
 * 3. Provide session driver alternatives
 */

// Include Laravel bootstrap
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔧 Session Fix Script\n";
echo "====================\n\n";

try {
    // 1. Check if sessions table exists
    echo "1. Checking sessions table...\n";
    
    if (Schema::hasTable('sessions')) {
        echo "   ✅ Sessions table exists\n";
        
        // Check table structure
        $columns = Schema::getColumnListing('sessions');
        echo "   📋 Columns: " . implode(', ', $columns) . "\n";
        
        // Count sessions
        $sessionCount = DB::table('sessions')->count();
        echo "   📊 Current sessions: {$sessionCount}\n";
    } else {
        echo "   ❌ Sessions table does not exist\n";
        echo "   🔨 Creating sessions table...\n";
        
        // Create sessions table manually
        Schema::create('sessions', function ($table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
        
        echo "   ✅ Sessions table created successfully!\n";
    }
    
    echo "\n";
    
    // 2. Test authentication
    echo "2. Testing authentication...\n";
    
    $user = \App\Models\User::where('email', 'sana@service.com')->first();
    
    if (!$user) {
        echo "   ❌ Test user (sana@service.com) not found\n";
    } else {
        echo "   ✅ Test user found: {$user->name}\n";
        
        // Test password
        $passwordTest = Hash::check('sana123', $user->password);
        echo "   🔐 Password check: " . ($passwordTest ? "✅ PASS" : "❌ FAIL") . "\n";
        
        // Test Auth::attempt
        $authAttempt = Auth::attempt([
            'email' => 'sana@service.com',
            'password' => 'sana123'
        ]);
        
        echo "   🔑 Auth::attempt: " . ($authAttempt ? "✅ SUCCESS" : "❌ FAILED") . "\n";
        
        if ($authAttempt) {
            echo "   👤 Authenticated user: " . Auth::user()->name . "\n";
            echo "   🎯 User type: " . Auth::user()->user_type . "\n";
        }
    }
    
    echo "\n";
    
    // 3. Session configuration info
    echo "3. Session Configuration:\n";
    echo "   📁 Driver: " . config('session.driver') . "\n";
    echo "   ⏰ Lifetime: " . config('session.lifetime') . " minutes\n";
    echo "   🍪 Cookie: " . config('session.cookie') . "\n";
    echo "   🔒 Secure: " . (config('session.secure') ? 'YES' : 'NO') . "\n";
    echo "   🌐 Same Site: " . config('session.same_site') . "\n";
    
    echo "\n";
    
    // 4. Staff users count
    $staffCount = \App\Models\User::where('user_type', 'staff')->count();
    echo "4. Staff Users: {$staffCount} found\n";
    
    echo "\n🎉 Session fix script completed!\n";
    
    if (!$authAttempt ?? false) {
        echo "\n⚠️  RECOMMENDATIONS:\n";
        echo "   1. Try switching to file sessions in .env:\n";
        echo "      SESSION_DRIVER=file\n";
        echo "   2. Clear config cache: php artisan config:clear\n";
        echo "   3. Check storage permissions: chmod -R 775 storage\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📁 File: " . $e->getFile() . "\n";
    echo "📍 Line: " . $e->getLine() . "\n";
}