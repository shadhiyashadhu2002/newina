<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Starting database check...\n";

try {
    require __DIR__ . '/vendor/autoload.php';
    echo "✓ Autoload loaded\n";
    
    $app = require_once __DIR__ . '/bootstrap/app.php';
    echo "✓ App bootstrapped\n";
    
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    echo "✓ Kernel bootstrapped\n";
    
    // Simple direct query
    $result = DB::select("SELECT id, customer_name, mobile, status, follow_up_date, imid FROM fresh_data WHERE assigned_to IS NOT NULL LIMIT 5");
    
    echo "\nFound " . count($result) . " profiles:\n";
    echo str_repeat("-", 100) . "\n";
    
    foreach($result as $row) {
        echo "ID: {$row->id} | Name: {$row->customer_name} | Status: " . ($row->status ?: 'NULL') . " | Follow-up: " . ($row->follow_up_date ?: 'NULL') . "\n";
    }
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
?>
