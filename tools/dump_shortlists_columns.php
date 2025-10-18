<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $cols = Illuminate\Support\Facades\Schema::getColumnListing('shortlists');
    echo "COLUMNS:\n" . implode(", ", $cols) . "\n";
    // show a sample row if exists
    $row = Illuminate\Support\Facades\DB::table('shortlists')->limit(5)->get();
    echo "SAMPLE ROWS:\n";
    foreach ($row as $r) {
        echo json_encode($r) . "\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
