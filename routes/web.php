
<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminMemberController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Admin routes (no auth middleware needed for login form)
Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    // Profile edit page route (user)
    Route::get('/profile/{id}/edit', function ($id) {
        return view('profile.edit_hi');
    })->name('profile.edit_hi');
    // Admin dashboard route - only accessible by admins
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User dashboard route - only accessible by regular users
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');

    // Profile page route (user)
    Route::get('/profile', function () {
        $users = \App\Models\User::all();
    return view('profile.profile', compact('users'));
    })->name('profile.hellow');

    // Profile page route (admin)
    Route::get('/admin/profile', function () {
        return view('admin.profile');
    })->name('admin.profile');

    // Resource route for admin members (admin only)
    Route::resource('admin/members', AdminMemberController::class);

    // Logout route
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});

// Regular user login routes
Route::get('/login', function () {
    return view('auth.login'); // Make sure this view exists
})->name('login');

// Home route - redirect based on user type
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->is_admin) {
            return redirect()->route('dashboard'); // This routes to admin dashboard
        } else {
            return redirect()->route('user.dashboard');
        }
    }
    
    // If not authenticated, show login
    return redirect()->route('login');
});

// Debug route to check session driver
Route::get('/session-test', function () {
    return response()->json([
        'session_driver' => config('session.driver'),
        'session_id' => session()->getId(),
        'csrf_token' => csrf_token()
    ]);
});