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

// Test login route (for debugging)
Route::get('/test-login', function () {
    return view('test-login');
});
Route::post('/test-login', function (Illuminate\Http\Request $request) {
    $credentials = $request->only('email', 'password');
    
    try {
        // First check if user exists
        $user = App\Models\User::where('email', $credentials['email'])->first();
        if (!$user) {
            return back()->with(['login_result' => 'User not found in database', 'login_success' => false]);
        }
        
        // Debug user info
        $debugInfo = "User found: {$user->name}, Email: {$user->email}, User Type: {$user->user_type}, Is Admin: " . ($user->is_admin ? 'Yes' : 'No');
        
        // Check password manually
        if (Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            $passwordCheck = 'Password hash check: PASS';
        } else {
            $passwordCheck = 'Password hash check: FAIL - Hash: ' . substr($user->password, 0, 30) . '...';
            
            // Try to reset password if it fails
            $user->password = Illuminate\Support\Facades\Hash::make($credentials['password']);
            $user->save();
            $passwordCheck .= ' | Password has been reset, try again.';
        }
        
        // Try authentication
        if (Auth::attempt($credentials)) {
            $authUser = Auth::user();
            
            // Redirect to appropriate dashboard based on user type
            if ($authUser->is_admin || $authUser->user_type === 'staff') {
                return redirect('/dashboard')->with('success', 'Login successful! Welcome ' . $authUser->name);
            } else {
                return redirect('/user/dashboard')->with('success', 'User login successful!');
            }
        } else {
            return back()->with(['login_result' => 'LOGIN FAILED! Auth::attempt() returned false. ' . $debugInfo . ' | ' . $passwordCheck, 'login_success' => false]);
        }
    } catch (Exception $e) {
        return back()->with(['login_result' => 'ERROR: ' . $e->getMessage(), 'login_success' => false]);
    }
});

// Dashboard test route (for debugging)
Route::get('/dashboard-test', function () {
    try {
        $users = App\Models\User::paginate(10);
        $stats = [
            'total_users' => App\Models\User::count(),
            'new_profiles' => App\Models\User::whereDate('created_at', today())->count()
        ];
        return view('dashboard', compact('users', 'stats'));
    } catch (Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
    }
});

// Simple dashboard test
Route::get('/simple-dashboard', function () {
    return '<h1>Simple Dashboard Test</h1><p>If you can see this, the route works!</p>';
});

// Test dashboard with authentication
Route::get('/test-auth-dashboard', function () {
    // Simulate login for testing
    $user = App\Models\User::where('email', 'sana@service.com')->first();
    if ($user) {
        Auth::login($user);
        return '<h1>Login Successful!</h1><p>User: ' . $user->name . '</p><p><a href="/dashboard">Go to Dashboard</a></p>';
    }
    return 'User not found';
});

// Minimal dashboard test
Route::get('/dashboard-minimal', function () {
    if (!Auth::check()) {
        return redirect('/login');
    }
    $user = Auth::user();
    return '<h1>Dashboard</h1><p>Welcome ' . $user->name . '</p><p>User Type: ' . $user->user_type . '</p>';
})->middleware('auth');

// Server dashboard debug route
Route::get('/server-debug', function () {
    try {
        // Check if user is authenticated
        $authStatus = Auth::check() ? 'YES' : 'NO';
        $currentUser = Auth::user();
        
        // Count staff users
        $staffCount = App\Models\User::where('user_type', 'staff')->count();
        
        return [
            'authenticated' => $authStatus,
            'current_user' => $currentUser ? [
                'id' => $currentUser->id,
                'name' => $currentUser->name,
                'email' => $currentUser->email,
                'user_type' => $currentUser->user_type
            ] : null,
            'staff_users_count' => $staffCount,
            'dashboard_view_exists' => view()->exists('dashboard'),
            'session_driver' => config('session.driver'),
            'app_env' => config('app.env'),
            'debug_mode' => config('app.debug'),
        ];
    } catch (Exception $e) {
        return [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ];
    }
});

// Direct authentication test
Route::get('/direct-login-test', function () {
    try {
        $user = App\Models\User::where('email', 'sana@service.com')->first();
        if (!$user) {
            return 'User not found';
        }
        
        // Check password
        $passwordValid = Illuminate\Support\Facades\Hash::check('1010', $user->password);
        
        if (!$passwordValid) {
            // Reset password
            $user->password = Illuminate\Support\Facades\Hash::make('1010');
            $user->save();
            $passwordValid = Illuminate\Support\Facades\Hash::check('1010', $user->password);
        }
        
        // Try manual login
        Auth::login($user);
        
        return response()->json([
            'user_found' => true,
            'password_valid' => $passwordValid,
            'login_successful' => Auth::check(),
            'auth_user' => Auth::user() ? Auth::user()->email : 'None',
            'user_data' => [
                'name' => $user->name,
                'email' => $user->email,
                'user_type' => $user->user_type,
                'is_admin' => $user->is_admin
            ]
        ]);
    } catch (Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});

// Quick login for Sana (for testing)
Route::get('/quick-login-sana', function () {
    $user = App\Models\User::where('email', 'sana@service.com')->first();
    if ($user) {
        // Reset password to ensure it works
        $user->password = Illuminate\Support\Facades\Hash::make('1010');
        $user->save();
        
        // Login manually
        Auth::login($user);
        
        if (Auth::check()) {
            return redirect('/dashboard')->with('success', 'Quick login successful!');
        } else {
            return 'Login failed even with manual login';
        }
    }
    return 'User not found';
});

// Debug route to check dashboard controller
Route::get('/debug-dashboard', function () {
    try {
        $controller = new App\Http\Controllers\DashboardController();
        $request = request();
        return $controller->index($request);
    } catch (Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

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

    // Shortlist routes
    Route::get('/shortlist-ina/{serviceId}', [ServiceController::class, 'shortlistIna'])->name('shortlist.ina');
    Route::get('/shortlist-others/{serviceId}', [ServiceController::class, 'shortlistOthers'])->name('shortlist.others');

    // Prospects route
    Route::get('/view-prospects/{serviceId}', [ServiceController::class, 'viewProspects'])->name('view.prospects');

    // Client details route
    Route::get('/client-details/{clientId}', [ServiceController::class, 'clientDetails'])->name('client.details');

    // Active Service page route
    Route::get('/active-service', function () {
        return view('profile.activeservice');
    })->name('active.service');

    // Profile search and assignment routes
    Route::post('/search-profiles', [ServiceController::class, 'searchProfiles'])->name('search.profiles');
    Route::post('/assign-profile', [ServiceController::class, 'assignProfile'])->name('assign.profile');
});

// Home route - redirect based on user type
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->is_admin || $user->user_type === 'staff') {
            return redirect()->route('dashboard'); // This routes to admin/staff dashboard
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
        'csrf_token' => csrf_token(),
        'auth_check' => Auth::check(),
        'auth_user' => Auth::user() ? Auth::user()->email : 'Not logged in',
        'session_config' => [
            'lifetime' => config('session.lifetime'),
            'path' => config('session.path'),
            'domain' => config('session.domain'),
            'secure' => config('session.secure'),
            'http_only' => config('session.http_only')
        ]
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
// Service Details routes - Available to all authenticated users
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