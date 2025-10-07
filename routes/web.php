<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminMemberController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController; // Add this import
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\FreshDataController;

// Admin routes (no auth middleware needed for login form)
Route::get('/admin/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');

// Regular user authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Temporary debug login route with detailed debugging
Route::post('/login', function (Request $request) {
    try {
        // Get credentials
        $email = $request->input('email');
        $password = $request->input('password');
        
        // Find user
        $user = \App\Models\User::where('email', $email)->first();
        
        // Detailed debug info
        $debugInfo = [
            'email_provided' => $email,
            'password_length' => strlen($password),
            'user_found' => $user ? true : false,
            'user_id' => $user ? $user->id : null,
            'user_type' => $user ? $user->user_type : null,
            'password_hash_exists' => $user ? (!empty($user->password)) : false,
            'password_check' => $user ? Hash::check($password, $user->password) : false,
        ];
        
        // Log the detailed attempt
        Log::info('Detailed login attempt', $debugInfo);
        
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        
        // Show detailed error if user not found
        if (!$user) {
            return back()->withErrors(['email' => 'No user found with email: ' . $email])->withInput();
        }
        
        // Show detailed error if password is wrong
        if (!Hash::check($password, $user->password)) {
            return back()->withErrors(['password' => 'Password is incorrect for: ' . $email])->withInput();
        }
        
        $credentials = $request->only('email', 'password');
        
        // Attempt authentication
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Regenerate session for security
            $request->session()->regenerate();
            
            Log::info('Login successful', ['user_id' => $user->id, 'user_type' => $user->user_type]);
            
            // Test if we can access dashboard directly
            try {
                if ($user->is_admin || $user->user_type === 'staff') {
                    $dashboardUrl = url('/dashboard');
                    Log::info('Redirecting to dashboard', ['url' => $dashboardUrl]);
                    
                    // Force a simple redirect
                    return redirect($dashboardUrl)->with('login_success', 'Welcome ' . $user->name . '!');
                } else {
                    $dashboardUrl = url('/user/dashboard');
                    Log::info('Redirecting to user dashboard', ['url' => $dashboardUrl]);
                    return redirect($dashboardUrl)->with('login_success', 'Welcome ' . $user->name . '!');
                }
            } catch (Exception $redirectException) {
                Log::error('Redirect failed', ['error' => $redirectException->getMessage()]);
                // If redirect fails, show success message instead
                return back()->with('success', 'Login successful! Please navigate to dashboard manually.');
            }
        }
        
        Log::error('Auth::attempt failed despite password check passing', $debugInfo);
        return back()->withErrors(['email' => 'Authentication system error. Debug info logged.'])->withInput();
        
    } catch (Exception $e) {
        Log::error('Login exception: ' . $e->getMessage());
        return back()->withErrors(['email' => 'System error: ' . $e->getMessage()])->withInput();
    }
})->name('login.submit');

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

// Force login test for debugging authentication
Route::get('/force-login-sana', function () {
    try {
        $user = App\Models\User::where('email', 'sana@service.com')->first();
        
        if (!$user) {
            return ['error' => 'User sana@service.com not found'];
        }
        
        // Force login using Auth::login
        Auth::login($user);
        
        // Check if login worked
        $isLoggedIn = Auth::check();
        $loggedUser = Auth::user();
        
        return [
            'login_attempted' => true,
            'user_found' => true,
            'auth_login_called' => true,
            'is_logged_in_now' => $isLoggedIn,
            'logged_user' => $loggedUser ? [
                'id' => $loggedUser->id,
                'name' => $loggedUser->name,
                'email' => $loggedUser->email,
                'user_type' => $loggedUser->user_type
            ] : null,
            'session_id' => session()->getId(),
            'redirect_url' => $user->user_type === 'staff' || $user->is_admin ? '/dashboard' : '/user/dashboard'
        ];
    } catch (Exception $e) {
        return [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ];
    }
});

// Test manual login with Auth::attempt
Route::get('/test-auth-attempt', function () {
    try {
        $credentials = [
            'email' => 'sana@service.com',
            'password' => 'sana123'
        ];
        
        // Get user first to verify it exists
        $user = App\Models\User::where('email', 'sana@service.com')->first();
        
        if (!$user) {
            return ['error' => 'User not found'];
        }
        
        // Check password manually
        $passwordCheck = Hash::check('sana123', $user->password);
        
        // Try Auth::attempt
        $authResult = Auth::attempt($credentials);
        
        return [
            'user_exists' => true,
            'password_correct' => $passwordCheck,
            'auth_attempt_result' => $authResult,
            'is_authenticated_after' => Auth::check(),
            'session_driver' => config('session.driver'),
            'user_data' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'user_type' => $user->user_type
            ]
        ];
    } catch (Exception $e) {
        return [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ];
    }
});

// Debug login form submission
Route::post('/debug-login', function (Request $request) {
    try {
        return [
            'form_submitted' => true,
            'request_method' => $request->method(),
            'has_email' => $request->has('email'),
            'has_password' => $request->has('password'),
            'email_value' => $request->input('email'),
            'password_length' => strlen($request->input('password', '')),
            'all_inputs' => $request->all(),
            'csrf_token' => $request->input('_token'),
            'session_token' => session()->token(),
            'tokens_match' => $request->input('_token') === session()->token(),
        ];
    } catch (Exception $e) {
        return [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ];
    }
});

// Simple login test form
Route::get('/simple-login-form', function () {
    return '
    <!DOCTYPE html>
    <html>
    <head><title>Simple Login Test</title></head>
    <body>
        <h2>Simple Login Test</h2>
        <form method="POST" action="/login" style="max-width:300px; margin:50px auto;">
            <input type="hidden" name="_token" value="' . csrf_token() . '">
            <div style="margin-bottom:10px;">
                <label>Email:</label><br>
                <input type="email" name="email" value="sana@service.com" style="width:100%; padding:8px;" required>
            </div>
            <div style="margin-bottom:10px;">
                <label>Password:</label><br>
                <input type="password" name="password" value="sana123" style="width:100%; padding:8px;" required>
            </div>
            <button type="submit" style="width:100%; padding:10px; background:#4CAF50; color:white; border:none;">Login</button>
        </form>
        
        <hr>
        <p><strong>Debug Info:</strong></p>
        <p>CSRF Token: ' . csrf_token() . '</p>
        <p>Login Route: ' . route('login') . '</p>
        <p>Current URL: ' . request()->url() . '</p>
    </body>
    </html>';
});

// Debug: Compare working vs non-working credentials
Route::get('/debug-credentials', function () {
    try {
        $email = 'sana@service.com';
        $password = 'sana123';
        
        $user = \App\Models\User::where('email', $email)->first();
        
        if (!$user) {
            return ['error' => 'User not found'];
        }
        
        // Test both ways
        $manualCheck = Hash::check($password, $user->password);
        $authAttempt = Auth::attempt(['email' => $email, 'password' => $password]);
        
        return [
            'user_found' => true,
            'user_data' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'user_type' => $user->user_type,
                'password_hash_length' => strlen($user->password),
                'password_starts_with' => substr($user->password, 0, 10),
            ],
            'manual_password_check' => $manualCheck,
            'auth_attempt_result' => $authAttempt,
            'is_authenticated_after' => Auth::check(),
            'test_password_variations' => [
                'original' => Hash::check($password, $user->password),
                'trimmed' => Hash::check(trim($password), $user->password),
                'lowercase' => Hash::check(strtolower($password), $user->password),
            ]
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

// Get service for editing (AJAX)
Route::get('/service/{id}/edit', [ServiceController::class, 'edit'])->name('service.edit');

// Update service (AJAX)
Route::put('/service/{id}', [ServiceController::class, 'update'])->name('service.update');

// Delete service (AJAX) - Soft delete
Route::delete('/service/{id}', [ServiceController::class, 'destroy'])->name('service.delete');

// Expired services (admin only)
Route::get('/expired-services', [ServiceController::class, 'expiredServices'])->name('expired.services');

// Test login and immediate dashboard access
Route::get('/test-login-and-redirect', function () {
    try {
        // Login the user
        $user = \App\Models\User::where('email', 'sana@service.com')->first();
        Auth::login($user);
        
        return [
            'login_success' => Auth::check(),
            'user' => Auth::user() ? Auth::user()->name : null,
            'dashboard_url' => url('/dashboard'),
            'current_url' => request()->url(),
            'test_redirect_target' => redirect('/dashboard')->getTargetUrl(),
            'session_regenerated' => session()->regenerate(),
        ];
    } catch (Exception $e) {
        return [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ];
    }
});

// Check staff users for dropdown debug
Route::get('/debug-staff-dropdown', function () {
    try {
        // Get all users with user_type = 'staff'
        $staffUsers = \App\Models\User::where('user_type', 'staff')
                                     ->orderBy('first_name')
                                     ->get(['id', 'first_name', 'name', 'email', 'user_type']);
        
        // Get all users to see what user_types exist
        $allUsers = \App\Models\User::select('id', 'name', 'first_name', 'email', 'user_type')
                                   ->orderBy('name')
                                   ->get();
        
        // Count by user_type
        $userTypeCounts = \App\Models\User::selectRaw('user_type, COUNT(*) as count')
                                         ->groupBy('user_type')
                                         ->get();
        
        return [
            'staff_users_count' => $staffUsers->count(),
            'staff_users' => $staffUsers->toArray(),
            'all_users_count' => $allUsers->count(),
            'user_type_counts' => $userTypeCounts->toArray(),
            'staff_names_for_dropdown' => $staffUsers->pluck('first_name')->toArray(),
            'all_user_types' => $allUsers->pluck('user_type')->unique()->values()->toArray(),
        ];
    } catch (Exception $e) {
        return [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ];
    }
});