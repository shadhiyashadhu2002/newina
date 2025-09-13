<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminMemberController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController; // Add this import
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Admin routes (no auth middleware needed for login form)
Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');

// Regular user authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Registration routes (if needed)
Route::get('/register', [LoginController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [LoginController::class, 'register'])->name('register.submit');

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    // Add New Profile page route
    Route::get('/profile/add', function () {
        return view('profile.addnewprofile');
    })->name('profile.addnew');
    // Profile update route (user)
    Route::put('/profile/{id}', [UserController::class, 'updateProfile'])->name('profile.update');
    
    // Profile edit page route (user) - Fixed duplicate routes
    Route::get('/profile/{id}/edit', function ($id) {
        $user = \App\Models\User::findOrFail($id);
        // Fetch gender from members table using user id
        $member = \App\Models\Member::where('user_id', $user->id)->first();
        $gender = null;
        if ($member) {
            if ($member->gender == 1) {
                $gender = 'Male';
            } elseif ($member->gender == 2) {
                $gender = 'Female';
            } else {
                $gender = 'Other';
            }
        }
        return view('profile.profile_edit', compact('user', 'gender'));
    })->name('profile.edit_hi');
    
    // Admin dashboard route - only accessible by admins
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User dashboard route - only accessible by regular users
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::post('/profile', [UserController::class, 'store'])->name('profile.store');
    // Profile page route (user)
    Route::get('/profile', function () {
    $users = \App\Models\User::whereDate('created_at', now()->toDateString())->get();
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