<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminMemberController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController; // Add this import
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FreshDataController;

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
    
    // Profile edit page route (user) - Controller based
Route::get('/profile/{id}/edit', [UserController::class, 'editProfile'])->name('profile.edit_hi');
    
    // Admin dashboard route - only accessible by admins
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User dashboard route - only accessible by regular users
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
    Route::post('/profile', [UserController::class, 'store'])->name('profile.store');
    // Profile page route (user)
    Route::get('/profile', function () {
    $users = \App\Models\User::whereNotNull('code')->orderBy('created_at', 'desc')->take(10)->get();
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


Route::post('/edit-fresh-data/{id}', [FreshDataController::class, 'update'])->name('update.fresh.data');
Route::get('/fresh-data', [FreshDataController::class, 'index'])->name('fresh.data');
Route::get('/add-fresh-data', function () {
    return view('profile.addfreashdata');
})->name('add.fresh.data');
Route::post('/add-fresh-data', [FreshDataController::class, 'store'])->name('add.fresh.data.store');
Route::get('/edit-fresh-data/{id}', [FreshDataController::class, 'edit'])->name('edit.fresh.data');
Route::get('/fresh-data/view/{id}', [FreshDataController::class, 'view'])->name('fresh.data.view');
Route::get('/services', [ServiceController::class, 'servicesDashboard'])->name('services.page');
Route::post('/services/update-status/{id}', [ServiceController::class, 'updateStatus'])->name('services.updateStatus');
Route::post('/save-service', [ServiceController::class, 'saveSection'])->name('save.service.section');
Route::get('/csrf-token', function() {
    return response()->json(['token' => csrf_token()]);
})->name('csrf.token');

use App\Models\Service;
Route::get('/service-details/{id}/{name}', function ($id, $name) {
    $service = Service::where('profile_id', $id)->first();
    if (!$service) {
        abort(404, 'Service not found for this profile');
    }
    return view('profile.servicedetails', compact('service'));
})->name('service.details');

// Route for creating new service details (without existing service)
Route::get('/profile/{id}/servicedetails', function ($id) {
    return view('profile.servicedetails');
})->name('profile.servicedetails');

// New Service page route (show list for executive or admin)
Route::get('/new-service', function () {
    $user = Auth::user();
    if ($user && $user->is_admin) {
        return app(ServiceController::class)->allServices();
    } else if ($user) {
        return app(ServiceController::class)->executiveServices();
    }
    return redirect()->route('login');
})->name('new.service');

// Store new service (AJAX)
Route::post('/new-service', [ServiceController::class, 'store'])->name('new.service.store');
// Active Service page route
Route::get('/active-service', function () {
    return view('profile.activeservice');
})->name('active.service');