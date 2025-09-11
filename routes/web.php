// Route to handle profile code link
Route::get('/profile/{code}', function ($code) {
    return view('profile', ['code' => $code]);
});

<?php
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminMemberController;
use App\Http\Controllers\DashboardController;
// Resource route for admin members
Route::resource('admin/members', AdminMemberController::class);
// Debug route to check session driver
Route::get('/session-test', function () {
    return config('session.driver');
});
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


Route::get('/', function () {
    $user = Auth::user();
    $profileId = $user ? $user->code : null;
    $perPage = request('per_page', 10);
    $allowedPerPage = [10, 50, 100];
    if (!in_array($perPage, $allowedPerPage)) {
        $perPage = 10;
    }
    $users = App\Models\User::paginate((int) $perPage)->appends(['per_page' => $perPage]);
    return view('dashboard', compact('profileId', 'users'));
});
