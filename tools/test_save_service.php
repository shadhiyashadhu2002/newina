<?php

require __DIR__ . '/../vendor/autoload.php';

// Bootstrap the framework (minimal)
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create a request similar to a form POST
use Illuminate\Http\Request;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Auth;

$request = Request::create('/save-service', 'POST', [
    'section' => 'others',
    'profile_id' => '',
    'other_site_member_id' => 'EXT-12345',
    'profile_source' => 'SHAADI',
    'start_date' => date('Y-m-d'),
    'member_name' => 'Test User From Other',
    'contact_numbers' => '9999999999',
    'remarks' => 'Inserted by test script'
]);

// Set a fake authenticated user context if available
// Try to grab the first staff user
$user = \App\Models\User::where('user_type', 'staff')->first();
if ($user) {
    Auth::login($user);
}

$controller = new ServiceController();
$response = $controller->saveSection($request);

// Output the JSON
if ($response instanceof Illuminate\Http\JsonResponse) {
    echo $response->getContent();
} else {
    var_dump($response);
}

