<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$q1 = \DB::select("SELECT COUNT(DISTINCT users.id) AS cnt FROM users LEFT JOIN members ON members.user_id = users.id WHERE (members.current_package_id IS NULL OR members.current_package_id = 0 OR members.package_validity < NOW())");
$q2 = \DB::select("SELECT COUNT(DISTINCT users.id) AS cnt FROM users LEFT JOIN members ON members.user_id = users.id WHERE (members.current_package_id IS NULL OR members.current_package_id = 0 OR members.package_validity < NOW()) AND NOT EXISTS (SELECT 1 FROM fresh_data WHERE fresh_data.mobile = users.phone AND fresh_data.assigned_to IS NOT NULL)");

echo "Query1 (controller condition): " . ($q1[0]->cnt ?? 0) . "\n";
echo "Query2 (dashboard condition with NOT EXISTS): " . ($q2[0]->cnt ?? 0) . "\n";
