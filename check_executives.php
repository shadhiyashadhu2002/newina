<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = \App\Models\User::where(function($q) {
    $q->whereIn('first_name', ['Rahna', 'Lamiya'])
      ->orWhereIn('name', ['Rahna', 'Lamiya']);
})->get(['id', 'name', 'first_name', 'user_type', 'team', 'is_admin', 'email']);

echo "Found " . $users->count() . " users\n";
foreach($users as $u) {
    echo "ID: " . $u->id . " | FirstName: " . $u->first_name . " | Name: " . $u->name . " | Type: " . $u->user_type . " | Team: " . $u->team . " | Admin: " . $u->is_admin . " | Email: " . $u->email . "\n";
}

// Also check the staff users to see what navigation they get
echo "\n\nAll Staff Users:\n";
$staff = \App\Models\User::where('user_type', 'staff')->get(['id', 'name', 'first_name', 'user_type', 'team', 'is_admin']);
foreach($staff as $s) {
    echo "ID: " . $s->id . " | Name: " . $s->first_name . " | Team: " . $s->team . " | Admin: " . $s->is_admin . "\n";
}
