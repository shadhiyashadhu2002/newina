<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Registration Form API Routes
Route::get('/registration-form/profile/fetch2/{imid}', [App\Http\Controllers\Api\RegistrationFormController::class, 'fetchProfile']);
Route::post('/registration-form/save', [App\Http\Controllers\Api\RegistrationFormController::class, 'save']);
Route::post('/registration-form/generate-pdf', [App\Http\Controllers\Api\RegistrationFormController::class, 'generatePDF']);
