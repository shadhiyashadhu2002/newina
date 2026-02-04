<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = \App\Models\User::whereIn('id', [28680, 28681])->get();
foreach($users as $u) {
    echo "ID: {$u->id} | Name: '{$u->name}' | FirstName: '{$u->first_name}' | Type: {$u->user_type}\n";
}
