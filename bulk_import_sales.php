<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Sale;
use Carbon\Carbon;

// Your Excel data - replace this with your actual data
$salesData = [
    ['1-Dec-2025', 'IM205122545', 'HASHIR', '9876543210', 6000, 'OFFICE PAYMENT', 'SERVICE', 'PAID', 'RAMEESHA', 'TIRUR'],
    ['2-Dec-2025', 'IM205122546', 'JOHN DOE', '9876543211', 8000, 'GPAY CANARA', 'Basic', 'PENDING', 'STAFF NAME', 'VATAKARA'],
    // Add more rows here
];

$staffId = 23164; // Replace with your actual staff ID
$successCount = 0;
$errorCount = 0;

foreach ($salesData as $row) {
    try {
        $date = Carbon::parse($row[0]);
        
        Sale::create([
            'date' => $date,
            'profile_id' => $row[1],
            'name' => $row[2],
            'phone' => $row[3],
            'amount' => $row[4],
            'paid_amount' => $row[4],
            'cash_type' => $row[5],
            'plan' => $row[6],
            'sale_status' => $row[7],
            'executive' => $row[8],
            'office' => $row[9],
            'success_fee' => 0,
            'discount' => 0,
            'notes' => null,
            'staff_id' => $staffId,
            'created_by' => $staffId,
            'status' => 'new',
        ]);
        
        $successCount++;
        echo "✓ Imported: {$row[2]}\n";
    } catch (Exception $e) {
        $errorCount++;
        echo "✗ Error importing {$row[2]}: " . $e->getMessage() . "\n";
    }
}

echo "\n=== Import Complete ===\n";
echo "Success: $successCount\n";
echo "Errors: $errorCount\n";
