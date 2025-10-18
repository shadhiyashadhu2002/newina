<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
// Boot the framework kernel for Eloquent to work
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
// Fetch services with photo
$services = App\Models\Service::whereNotNull('photo')->orderBy('id','desc')->take(10)->get(['id','profile_id','photo']);
echo json_encode($services->toArray());
