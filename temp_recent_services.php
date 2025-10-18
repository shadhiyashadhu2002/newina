<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$services = App\Models\Service::orderBy('id','desc')->take(50)->get(['id','profile_id','photo','assigned_by','created_at']);
echo json_encode($services->toArray());
