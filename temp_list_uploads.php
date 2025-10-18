<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$uploads = App\Models\Upload::orderBy('id','desc')->take(20)->get(['id','file_name','file_original_name','user_id','file_size','created_at']);
echo json_encode($uploads->toArray());
