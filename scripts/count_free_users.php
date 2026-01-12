<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$res = \DB::select("SELECT COUNT(DISTINCT users.id) AS cnt FROM users LEFT JOIN members ON members.user_id = users.id WHERE (members.current_package_id IS NULL OR members.current_package_id = 0 OR members.package_validity < NOW())");
echo isset($res[0]->cnt) ? $res[0]->cnt : 0;
