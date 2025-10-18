<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Service;

echo "Current distinct service_executive values:\n";
$distinct = Service::whereNotNull('service_executive')->distinct()->pluck('service_executive')->toArray();
foreach ($distinct as $d) echo "- $d\n";

$toUpdate = Service::whereNotNull('service_executive')->count();
echo "\nTotal rows with service_executive not null: $toUpdate\n";

// Preview grouped counts before
$groups = Service::select('service_executive', DB::raw('count(*) as cnt'))
    ->whereNotNull('service_executive')
    ->groupBy('service_executive')
    ->orderByDesc('cnt')
    ->get();

echo "\nCounts by current value:\n";
foreach ($groups as $g) {
    echo sprintf("%s => %d\n", $g->service_executive, $g->cnt);
}

// Apply normalization
echo "\nApplying normalization: updating service_executive = LOWER(TRIM(service_executive))\n";
$updated = DB::update("UPDATE services SET service_executive = LOWER(TRIM(service_executive)) WHERE service_executive IS NOT NULL");

echo "Rows affected: $updated\n";

$distinct2 = Service::whereNotNull('service_executive')->distinct()->pluck('service_executive')->toArray();
echo "\nDistinct service_executive values after normalization (count: " . count($distinct2) . "):\n";
foreach ($distinct2 as $d) echo "- $d\n";

echo "\nDone.\n";