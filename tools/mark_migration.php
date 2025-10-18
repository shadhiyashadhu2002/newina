<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$batch = DB::table('migrations')->max('batch');
$batch = $batch ? $batch : 1;
$path = 'database/migrations/2025_10_18_000001_create_shortlists_table.php';
$name = '2025_10_18_000001_create_shortlists_table';
$exists = DB::table('migrations')->where('migration', $name)->exists();
if ($exists) {
    echo "Migration already marked: $name\n";
    exit;
}
DB::table('migrations')->insert(['migration' => $name, 'batch' => $batch]);
echo "Inserted migration record for $name with batch $batch\n";
