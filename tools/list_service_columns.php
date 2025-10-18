<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use App\Models\Service;
use Illuminate\Support\Facades\Schema;
echo implode(', ', Schema::getColumnListing((new Service())->getTable())) . PHP_EOL;
