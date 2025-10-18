<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Service;
use Illuminate\Support\Facades\DB;

echo "Normalizing empty/null service.status to 'new' for non-deleted rows...\n";
$res = DB::update("UPDATE services SET status = 'new' WHERE deleted = 0 AND (status IS NULL OR TRIM(status) = '')");

echo "Rows affected: " . $res . "\n";

// Verify
$cnt = Service::where('deleted',0)->whereIn('status', ['new','pending'])->count();
echo "Count with status IN (new,pending) after normalize: $cnt\n";

echo "Done.\n";
