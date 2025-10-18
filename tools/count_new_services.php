<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Service;
use Illuminate\Support\Facades\DB;

echo "TOTAL NEW SERVICES: " . Service::where('deleted',0)->where('status','new')->count() . PHP_EOL;
$rows = Service::where('deleted',0)->where('status','new')
    ->select('service_executive', DB::raw('count(*) as cnt'))
    ->groupBy('service_executive')
    ->orderByDesc('cnt')
    ->get();

foreach ($rows as $r) {
    echo sprintf("%s => %d\n", $r->service_executive ?? '[NULL]', $r->cnt);
}

// Show sample of distinct service_executive values
$distinct = Service::where('deleted',0)->whereNotNull('service_executive')->distinct()->pluck('service_executive')->toArray();
echo "\nDistinct service_executive values (count: " . count($distinct) . "):\n";
foreach ($distinct as $d) echo "- $d\n";
