<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$staff = \App\Models\User::where('user_type', 'staff')->get();
$credentials = [];

foreach($staff as $s) {
    $newPassword = strtolower($s->first_name) . '123';
    $s->password = bcrypt($newPassword);
    $s->save();
    $credentials[] = "Email: " . $s->email . " | Password: " . $newPassword;
}

file_put_contents('staff_credentials.txt', implode("\n", $credentials));
echo "Passwords reset! Check staff_credentials.txt file\n";
echo count($credentials) . " staff passwords updated.\n";
?>
