<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Service;
use Illuminate\Support\Facades\DB;

echo "Service table summary\n";
$tot = Service::count();
$notDeleted = Service::where('deleted',0)->count();
$deleted = Service::where('deleted',1)->count();
echo "Total rows: $tot\n";
echo "Not deleted: $notDeleted, Deleted: $deleted\n\n";

// Counts by raw status values
$statuses = Service::select('status', DB::raw('count(*) as cnt'))
    ->groupBy('status')
    ->orderByDesc('cnt')
    ->get();

echo "Counts by raw status values:\n";
foreach ($statuses as $s) {
    $st = $s->status ?? '[NULL]';
    echo sprintf("%s => %d\n", $st, $s->cnt);
}

// Normalized statuses (lowercase trimmed) counts
$norm = Service::select(DB::raw('LOWER(TRIM(COALESCE(status, ""))) as st_norm'), DB::raw('count(*) as cnt'))
    ->groupBy('st_norm')
    ->orderByDesc('cnt')
    ->get();

echo "\nCounts by normalized status:\n";
foreach ($norm as $n) {
    $st = $n->st_norm === '' ? '[EMPTY]' : $n->st_norm;
    echo sprintf("%s => %d\n", $st, $n->cnt);
}

// Count by normalized service_executive for not-deleted rows
$execs = Service::where('deleted',0)
    ->select(DB::raw('LOWER(TRIM(COALESCE(service_executive, ""))) as exec_norm'), DB::raw('count(*) as cnt'))
    ->groupBy('exec_norm')
    ->orderByDesc('cnt')
    ->get();

echo "\nTop service_executives (normalized) for non-deleted rows:\n";
foreach ($execs as $e) {
    $name = $e->exec_norm === '' ? '[EMPTY]' : $e->exec_norm;
    echo sprintf("%s => %d\n", $name, $e->cnt);
}

// Show how many rows would match 'sana' as executive with statuses new/pending
$exec = 'sana';
$matches = Service::where('deleted',0)
    ->whereRaw('LOWER(TRIM(COALESCE(service_executive, ""))) = ?', [$exec])
    ->get();

echo "\nRows matching executive '$exec' (count: " . $matches->count() . "):\n";
foreach ($matches->take(20) as $m) {
    echo sprintf("id:%d profile:%s status:%s exec:%s created:%s\n", $m->id, $m->profile_id, $m->status ?? '[NULL]', $m->service_executive ?? '[NULL]', $m->created_at);
}

// How many of those are new/pending
$matchesNew = $matches->whereIn('status', ['new','pending'])->count();
$matchesActive = $matches->where('status','active')->count();

echo "\nFor executive '$exec' -> new/pending: $matchesNew, active: $matchesActive\n";

// Show sample of distinct service_executive values in DB (not-deleted)
$distinctExec = Service::where('deleted',0)->whereNotNull('service_executive')->distinct()->pluck('service_executive')->toArray();

echo "\nDistinct (raw) service_executive values (count: " . count($distinctExec) . "):\n";
foreach (array_slice($distinctExec, 0, 50) as $d) {
    echo "- $d\n";
}

echo "\nDone.\n";
