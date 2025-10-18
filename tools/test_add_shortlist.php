<?php

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Boot the framework
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create a request to call the route
$request = Request::create('/add-to-shortlist', 'POST', [
    'profile_id' => 'TEST-PROFILE-123',
    'prospect_name' => 'Unit Test Prospect',
    'prospect_contact' => 'Not specified',
    'source' => 'others'
]);

// Set required headers
$request->headers->set('Accept', 'application/json');

// Set CSRF token and cookies not necessary for CLI invocation

// Set authenticated user by resolving Auth and logging in a user
$kernel->handle($request);

echo "Request prepared. To fully execute through HTTP, run via browser or curl while authenticated.\n";

// Note: Running full framework HTTP stack in this script is non-trivial in CLI without session/cookie handling.
// The script leaves a hint for manual test.

return;
