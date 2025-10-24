
<?php
use App\Http\Controllers\SaleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminMemberController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController; // Add this import
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ExpenseController;
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

            // Redirect based on user type
            try {
                if ($user->is_admin || $user->user_type === 'staff') {
                    $dashboardUrl = url('/dashboard');
                    Log::info('Redirecting to dashboard', ['url' => $dashboardUrl]);
                    return redirect($dashboardUrl)->with('login_success', 'Welcome ' . $user->name . '!');
                } else {
                    $dashboardUrl = url('/user/dashboard');
                    Log::info('Redirecting to user dashboard', ['url' => $dashboardUrl]);
                    return redirect($dashboardUrl)->with('login_success', 'Welcome ' . $user->name . '!');
                }
            } catch (Exception $redirectException) {
                Log::error('Redirect failed', ['error' => $redirectException->getMessage()]);
                return back()->with('success', 'Login successful! Please navigate to dashboard manually.');
            }
        }

        Log::error('Auth::attempt failed', isset($debugInfo) ? $debugInfo : []);
        return back()->withErrors(['email' => 'Authentication failed. Debug info logged.'])->withInput();

    } catch (Exception $e) {
        Log::error('Login exception: ' . $e->getMessage());
        return back()->withErrors(['email' => 'System error: ' . $e->getMessage()])->withInput();
    }
})->name('login.submit');

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

// Quick login for Thasni (testing helper)
Route::get('/quick-login-thasni', function () {
    $user = App\Models\User::where('email', 'thasni@service.com')->first();
    if ($user) {
        // Ensure password is set to known value for testing
        $user->password = Illuminate\Support\Facades\Hash::make('thasni123');
        $user->save();

        // Login manually
        Auth::login($user);

        if (Auth::check()) {
            return redirect('/dashboard')->with('success', 'Quick login for Thasni successful!');
        } else {
            return 'Login failed even with manual login';
        }
    }
    return 'User not found';
});

// Quick login for Remsi (testing helper)
Route::get('/quick-login-remsi', function () {
    $user = App\Models\User::where('email', 'remsi@service.com')->first();
    if ($user) {
        // Ensure password is set to known value for testing
        $user->password = Illuminate\Support\Facades\Hash::make('remsi123');
        $user->save();

        // Login manually
        Auth::login($user);

        if (Auth::check()) {
            return redirect('/dashboard')->with('success', 'Quick login for Remsi successful!');
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

    // Debug: return resolved client details (service, user, partner expectation and computed partnerPrefs)
    Route::get('/debug/client-details/{clientId}', [ServiceController::class, 'debugClientDetails'])->name('debug.client.details');

    // Active Service page route (dynamic)
    Route::get('/active-service', function () {
        return app(App\Http\Controllers\ServiceController::class)->activeServiceList();
    })->name('active.service');

    // DEBUG: return service counts for current user (temporary)
    Route::get('/debug/service-counts', function() {
        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'not-authenticated'], 401);
        $executiveName = strtolower(trim($user->first_name ?? explode('@',$user->email)[0] ?? 'Unknown'));

        // Get all services for this executive
        $allServices = \App\Models\Service::where('deleted',0)
            ->whereRaw('LOWER(TRIM(service_executive)) = ?', [$executiveName])
            ->get(['id', 'profile_id', 'status', 'service_executive', 'created_at']);

        // Get distinct statuses
        $statuses = \App\Models\Service::where('deleted',0)
            ->whereRaw('LOWER(TRIM(service_executive)) = ?', [$executiveName])
            ->distinct()
            ->pluck('status');

        $total = $allServices->count();
        $new = $allServices->where('status', 'new')->count();
        $active = $allServices->where('status', 'active')->count();
        $pending = $allServices->where('status', 'pending')->count();

        return response()->json([
            'user' => [
                'name' => $user->name,
                'first_name' => $user->first_name,
                'email' => $user->email,
                'is_admin' => $user->is_admin
            ],
            'executive_name_used' => $executiveName,
            'total' => $total,
            'new' => $new,
            'active' => $active,
            'pending' => $pending,
            'all_statuses_found' => $statuses->toArray(),
            'all_services' => $allServices->toArray(),
            'service_executives_in_db' => \App\Models\Service::where('deleted', 0)
                ->distinct()
                ->pluck('service_executive')
                ->toArray()
        ]);
    });

    // Profile search and assignment routes
    Route::post('/search-profiles', [ServiceController::class, 'searchProfiles'])->name('search.profiles');
    Route::post('/assign-profile', [ServiceController::class, 'assignProfile'])->name('assign.profile');
    Route::get('/assigned-profiles', [ServiceController::class, 'assignedProfiles'])->name('assigned.profiles');
    // Shortlist endpoints
    Route::post('/add-to-shortlist', [ServiceController::class, 'addToShortlist'])->name('shortlist.add');
    Route::get('/shortlists/{profileId}', [ServiceController::class, 'getShortlistsForProfile'])->name('shortlist.list');
    // Generate a new profile id for Assign-from-Other flow
    Route::get('/generate-profile-id', [ServiceController::class, 'generateProfileId'])->name('generate.profile.id');
    // Upload photo endpoint (uploads file and returns upload id + url)
    Route::post('/upload-photo', [ServiceController::class, 'uploadPhoto'])->name('upload.photo');
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

use App\Models\Member;
use App\Models\Education;
use App\Models\MaritalStatus;

Route::get('/service-details/{id}/{name}', function ($id, $name) {
    // Find the service
    $service = Service::where('profile_id', $id)->first();
    if (!$service) {
        abort(404, 'Service not found for this profile');
    }
    // Find the user associated with this service
    $user = null;
    if ($service->user_id) {
        $user = \App\Models\User::find($service->user_id);
    }
    // If user not found via service, try finding by code
    if (!$user) {
        $user = \App\Models\User::where('code', $id)->first();
    }
    // CONTACT DETAILS - Auto-fill from users table if empty in service
    if ($user) {
        if (empty($service->contact_customer_name)) {
            $service->contact_customer_name = $user->first_name ?? '';
        }
        if (empty($service->contact_email)) {
            $service->contact_email = $user->email ?? '';
        }
        if (empty($service->contact_whatsapp_no)) {
            $service->contact_whatsapp_no = $user->phone ?? '';
        }
        if (empty($service->contact_mobile_no)) {
            $service->contact_mobile_no = $user->phone ?? '';
        }
    }
    // Get member data
    $member = null;
    $member_age = null;
    $member_marital_status = null;
    $member_education = null;
    $member_occupation = null;
    $member_income = null;
    $member_family_status = null;
    $member_father_details = null;
    $member_mother_details = null;
    $member_sibling_details = null;
    // Fetch family details if user exists (these always override member table)
    if ($user) {
        $family = \App\Models\Family::where('user_id', $user->id)->first();
        if ($family) {
            $member_father_details = $family->father;
            if ($family->father_occupation) {
                $member_father_details .= $family->father ? (', ' . $family->father_occupation) : $family->father_occupation;
            }
            $member_mother_details = $family->mother;
            if ($family->mother_occupation) {
                $member_mother_details .= $family->mother ? (', ' . $family->mother_occupation) : $family->mother_occupation;
            }
            // Sibling details
            $sibling_parts = [];
            if ($family->no_of_sisters && $family->no_of_sisters > 0) {
                $sibling_parts[] = $family->no_of_sisters . ' sister' . ($family->no_of_sisters > 1 ? 's' : '');
            }
            if ($family->no_of_brothers && $family->no_of_brothers > 0) {
                $sibling_parts[] = $family->no_of_brothers . ' brother' . ($family->no_of_brothers > 1 ? 's' : '');
            }
            if ($family->about_sibling) {
                $sibling_parts[] = $family->about_sibling;
            }
            $member_sibling_details = count($sibling_parts) ? implode(', ', $sibling_parts) : null;
        }
    }
    $member_caste = null;
    $member_subcaste = null;
    if ($user) {
        $member = \App\Models\Member::where('user_id', $user->id)->first();
        if ($member) {
            // Calculate age from birthday
            if ($member->birthday) {
                $member_age = \Carbon\Carbon::parse($member->birthday)->age;
            }
            // Get marital status name
            if ($member->marital_status_id) {
                $maritalStatus = \App\Models\MaritalStatus::find($member->marital_status_id);
                $member_marital_status = $maritalStatus ? $maritalStatus->name : null;
            }
            // Get other member details
            // Fetch occupation from careers.designation
            $member_occupation = null;
            $member_income = null;
            if ($user) {
                $career = \App\Models\Career::where('user_id', $user->id)->first();
                if ($career) {
                    $member_occupation = $career->designation;
                }
            }
            // Fetch income from annual_salary_ranges (min_salary-max_salary)
            if ($member && $member->annual_salary_range_id) {
                $salaryRange = \App\Models\AnnualSalaryRange::find($member->annual_salary_range_id);
                if ($salaryRange) {
                    $member_income = number_format($salaryRange->min_salary, 2) . '-' . number_format($salaryRange->max_salary, 2);
                }
            }
            $member_family_status = $member->family_status;
            // Do NOT override family details with member table values
            // Fetch caste and subcaste from spiritual_backgrounds
            $spiritual = \App\Models\SpiritualBackground::where('user_id', $user->id)->first();
            if ($spiritual) {
                // Caste = religion name
                $religion = \App\Models\Religion::find($spiritual->religion_id);
                $member_caste = $religion ? $religion->name : null;
                // Subcaste = caste name
                $caste = \App\Models\Caste::find($spiritual->caste_id);
                $member_subcaste = $caste ? $caste->name : null;
            }
        }
        // Get education degree
        $education = \App\Models\Education::where('user_id', $user->id)->first();
        if ($education) {
            $member_education = $education->degree;
        }
    }
    // Auto-fill service fields if empty
    if (empty($service->member_age) && $member_age !== null) {
        $service->member_age = $member_age;
    }
    if (empty($service->member_marital_status) && $member_marital_status !== null) {
        $service->member_marital_status = $member_marital_status;
    }
    if (empty($service->member_education) && $member_education !== null) {
        $service->member_education = $member_education;
    }
    if (empty($service->member_occupation) && $member_occupation !== null) {
        $service->member_occupation = $member_occupation;
    }
    if (empty($service->member_income) && $member_income !== null) {
        $service->member_income = $member_income;
    }
    if (empty($service->member_family_status) && $member_family_status !== null) {
        $service->member_family_status = $member_family_status;
    }
    if (empty($service->member_father_details) && $member_father_details !== null) {
        $service->member_father_details = $member_father_details;
    }
    if (empty($service->member_mother_details) && $member_mother_details !== null) {
        $service->member_mother_details = $member_mother_details;
    }
    if (empty($service->member_sibling_details) && $member_sibling_details !== null) {
        $service->member_sibling_details = $member_sibling_details;
    }
    if (empty($service->member_caste) && $member_caste !== null) {
        $service->member_caste = $member_caste;
    }
    if (empty($service->member_subcaste) && $member_subcaste !== null) {
        $service->member_subcaste = $member_subcaste;
    }
    // Partner preferences: try to fetch from partner_expectations by user (robust column names)
    if ($user) {
        $pe = \App\Models\PartnerExpectation::where('user_id', $user->id)->first();
        if ($pe) {
            // helper to prefer id->lookup or string directly
            $getIdOrValue = function ($modelClass, $idOrValue) {
                if (!$idOrValue) return null;
                // numeric -> lookup
                if (is_numeric($idOrValue)) {
                    $m = $modelClass::find($idOrValue);
                    return $m ? ($m->name ?? ($m->value ?? null)) : null;
                }
                // string -> use directly
                return $idOrValue;
            };

            if (empty($service->preferred_weight) && ($pe->weight ?? null)) {
                $service->preferred_weight = $pe->weight;
            }
            if (empty($service->preferred_education) && ($pe->education ?? null)) {
                $service->preferred_education = $pe->education;
            }

            // religion: try several keys
            $religionKey = $pe->religion_id ?? $pe->religion ?? null;
            if (empty($service->preferred_religion) && $religionKey) {
                $service->preferred_religion = $getIdOrValue('\App\\Models\\Religion', $religionKey);
            }

            // caste
            $casteKey = $pe->caste_id ?? $pe->caste ?? null;
            if (empty($service->preferred_caste) && $casteKey) {
                $service->preferred_caste = $getIdOrValue('\App\\Models\\Caste', $casteKey);
            }

            // subcaste
            $subKey = $pe->sub_caste_id ?? $pe->subcaste ?? $pe->sub_cast_id ?? null;
            if (empty($service->preferred_subcaste) && $subKey) {
                $service->preferred_subcaste = $getIdOrValue('\App\\Models\\SubCaste', $subKey);
            }

            // marital status
            $msKey = $pe->marital_status_id ?? $pe->marital_status ?? null;
            if (empty($service->preferred_marital_status) && $msKey) {
                $service->preferred_marital_status = $getIdOrValue('\App\\Models\\MaritalStatus', $msKey);
            }

            // profession / occupation
            $prof = $pe->profession ?? $pe->profession_title ?? $pe->profession_id ?? null;
            if (empty($service->preferred_occupation) && $prof) {
                // if numeric, try to lookup in careers? but partner expectation likely stores string
                $service->preferred_occupation = is_numeric($prof) ? (\App\Models\Career::find($prof)->designation ?? null) : $prof;
            }

            // family value
            $fvKey = $pe->family_value_id ?? $pe->family_value ?? null;
            if (empty($service->preferred_family_status) && $fvKey) {
                $service->preferred_family_status = $getIdOrValue('\App\\Models\\FamilyValue', $fvKey);
            }
        }
    }
    return view('profile.servicedetails', compact('service'));
})->name('service.details');

// Route for creating new service details (without existing service)
Route::get('/profile/{id}/servicedetails', function ($id) {
    // Create a new service object with fetched data
    $service = new Service();
    $service->profile_id = $id;
    // Find user by profile ID (code)
    $user = \App\Models\User::where('code', $id)->first();
    if ($user) {
        $service->user_id = $user->id;
    // CONTACT DETAILS - Fetch from users table (customer fields) and assign to service
    $customer_name = $user->first_name ?? '';
    $customer_email = $user->email ?? '';
    $customer_whatsapp = $user->phone ?? '';
    // Assign to service so the view has server-side values
    $service->contact_customer_name = $customer_name;
    $service->contact_email = $customer_email;
    $service->contact_whatsapp_no = $customer_whatsapp;
    $service->contact_mobile_no = $customer_whatsapp;

    // Get member data
    $member = \App\Models\Member::where('user_id', $user->id)->first();
        if ($member) {
            // Calculate age from birthday
            if ($member->birthday) {
                $service->member_age = \Carbon\Carbon::parse($member->birthday)->age;
            }
            // Get marital status name
            if ($member->marital_status_id) {
                $maritalStatus = \App\Models\MaritalStatus::find($member->marital_status_id);
                $service->member_marital_status = $maritalStatus ? $maritalStatus->name : '';
            }
            // Fill other member details
            $service->member_name = $user->name ?? ($user->first_name . ' ' . $user->last_name);
            // Fetch occupation from careers.designation
            $career = \App\Models\Career::where('user_id', $user->id)->first();
            if ($career) {
                $service->member_occupation = $career->designation;
            }
            // Fetch income from annual_salary_ranges (min_salary-max_salary)
            if ($member->annual_salary_range_id) {
                $salaryRange = \App\Models\AnnualSalaryRange::find($member->annual_salary_range_id);
                if ($salaryRange) {
                    $service->member_income = number_format($salaryRange->min_salary, 2) . '-' . number_format($salaryRange->max_salary, 2);
                }
            }
            $service->member_family_status = $member->family_status;
            // Fetch family details if user exists
            $family = \App\Models\Family::where('user_id', $user->id)->first();
            if ($family) {
                $service->member_father_details = $family->father;
                if ($family->father_occupation) {
                    $service->member_father_details .= $family->father ? (', ' . $family->father_occupation) : $family->father_occupation;
                }
                $service->member_mother_details = $family->mother;
                if ($family->mother_occupation) {
                    $service->member_mother_details .= $family->mother ? (', ' . $family->mother_occupation) : $family->mother_occupation;
                }
                // Sibling details
                $sibling_parts = [];
                if ($family->no_of_sisters && $family->no_of_sisters > 0) {
                    $sibling_parts[] = $family->no_of_sisters . ' sister' . ($family->no_of_sisters > 1 ? 's' : '');
                }
                if ($family->no_of_brothers && $family->no_of_brothers > 0) {
                    $sibling_parts[] = $family->no_of_brothers . ' brother' . ($family->no_of_brothers > 1 ? 's' : '');
                }
                if ($family->about_sibling) {
                    $sibling_parts[] = $family->about_sibling;
                }
                $service->member_sibling_details = count($sibling_parts) ? implode(', ', $sibling_parts) : null;
            }
            $service->member_caste = $member->caste;
            $service->member_subcaste = $member->subcaste;
        }
        // Get education degree
        $education = \App\Models\Education::where('user_id', $user->id)->first();
        if ($education) {
            $service->member_education = $education->degree;
        }
        // Partner preferences from partner_expectations (populate preferred_* fields)
        $pe = \App\Models\PartnerExpectation::where('user_id', $user->id)->first();
        if ($pe) {
            if (empty($service->preferred_weight) && $pe->weight) {
                $service->preferred_weight = $pe->weight;
            }
            if (empty($service->preferred_education) && $pe->education) {
                $service->preferred_education = $pe->education;
            }
            if (empty($service->preferred_religion) && $pe->religion_id) {
                $rel = \App\Models\Religion::find($pe->religion_id);
                $service->preferred_religion = $rel ? $rel->name : null;
            }
            if (empty($service->preferred_caste) && $pe->caste_id) {
                $cast = \App\Models\Caste::find($pe->caste_id);
                $service->preferred_caste = $cast ? $cast->name : null;
            }
            if (empty($service->preferred_subcaste) && $pe->sub_caste_id) {
                $sub = \App\Models\SubCaste::find($pe->sub_caste_id);
                $service->preferred_subcaste = $sub ? $sub->name : null;
            }
            if (empty($service->preferred_marital_status) && $pe->marital_status_id) {
                $ms = \App\Models\MaritalStatus::find($pe->marital_status_id);
                $service->preferred_marital_status = $ms ? $ms->name : null;
            }
            if (empty($service->preferred_occupation) && $pe->profession) {
                $service->preferred_occupation = $pe->profession;
            }
            if (empty($service->preferred_family_status) && $pe->family_value_id) {
                $fv = \App\Models\FamilyValue::find($pe->family_value_id);
                $service->preferred_family_status = $fv ? $fv->value : null;
            }
        }
    }
    return view('profile.servicedetails', compact('service'));
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

// Update service (AJAX) - allow both PUT and POST for method spoofing compatibility
Route::match(['put', 'post'], '/service/{id}', [ServiceController::class, 'update'])->name('service.update');

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


// Debug: return partner_expectations and resolved lookups for a profile/user
Route::get('/debug/partner-expectations/{profileId}', function ($profileId) {
    try {
        // Find user by id, code, or service.member_name
        $user = \App\Models\User::find($profileId);
        if (!$user) {
            $user = \App\Models\User::where('code', $profileId)->first();
        }
        if (!$user) {
            $service = \App\Models\Service::where('profile_id', $profileId)->first();
            if ($service && $service->member_name) {
                $user = \App\Models\User::where('name', 'like', '%' . $service->member_name . '%')->first();
            }
        }
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found for: ' . $profileId]);
        }

        $pe = \App\Models\PartnerExpectation::where('user_id', $user->id)->first();

        $resolved = [
            'partner_expectation' => $pe ? $pe->toArray() : null,
            'resolved' => []
        ];

        if ($pe) {
            // Resolve lookups
            $resolved['resolved']['religion'] = null;
            if (!empty($pe->religion_id)) {
                $r = \App\Models\Religion::find($pe->religion_id);
                $resolved['resolved']['religion'] = $r ? $r->name : null;
            }
            $resolved['resolved']['caste'] = null;
            if (!empty($pe->caste_id)) {
                $c = \App\Models\Caste::find($pe->caste_id);
                $resolved['resolved']['caste'] = $c ? $c->name : null;
            }
            $resolved['resolved']['sub_caste'] = null;
            if (!empty($pe->sub_caste_id)) {
                $s = \App\Models\SubCaste::find($pe->sub_caste_id);
                $resolved['resolved']['sub_caste'] = $s ? $s->name : null;
            }
            $resolved['resolved']['marital_status'] = null;
            if (!empty($pe->marital_status_id)) {
                $m = \App\Models\MaritalStatus::find($pe->marital_status_id);
                $resolved['resolved']['marital_status'] = $m ? $m->name : null;
            }
            $resolved['resolved']['family_value'] = null;
            if (!empty($pe->family_value_id)) {
                $fv = \App\Models\FamilyValue::find($pe->family_value_id);
                $resolved['resolved']['family_value'] = $fv ? $fv->value : null;
            }
        }

        return response()->json(['success' => true, 'user_id' => $user->id, 'data' => $resolved]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()]);
    }
});

Route::get('/staff-target-assign', function () {
    return view('stafftarget');
})->name('stafftarget.page');

Route::get('/staff-productivity', function () {
    return view('staffproductivity');
})->name('staffproductivity.page');

// Expense routes
Route::middleware('auth')->group(function () {
    Route::get('/expense-page', [ExpenseController::class, 'index'])->name('expense.page');
    Route::post('/expense', [ExpenseController::class, 'store'])->name('expense.store');
    Route::put('/expense/{expense}', [ExpenseController::class, 'update'])->name('expense.update');
    Route::delete('/expense/{expense}', [ExpenseController::class, 'destroy'])->name('expense.delete');
    Route::get('/expense/{expense}', [ExpenseController::class, 'show'])->name('expense.show');
});

Route::middleware('auth')->group(function () {
    // Sales form page
    Route::get('/add-sale', [SaleController::class, 'create'])->name('addsale.page');
    
    // Store sale
    Route::post('/add-sale', [SaleController::class, 'store'])->name('sale.store');
    
    // Get profile info for auto-fetch
    Route::get('/get-profile-info/{profileId}', [SaleController::class, 'getProfileInfo']);
});

// Add New Service page with optional prefill from sale
Route::get('/profile/newservice', function (\Illuminate\Http\Request $request) {
    $profile_id = $request->query('profile_id');
    $member_name = $request->query('member_name');
    $from_sale = $request->query('from_sale');
    // You may want to fetch staff users or other data as needed for the form
    $staffUsers = \App\Models\User::where('user_type', 'staff')->orderBy('first_name')->get(['id', 'first_name', 'name']);
    return view('profile.newservice', compact('profile_id', 'member_name', 'from_sale', 'staffUsers'));
})->name('profile.newservice');
// Fetch member data for service form auto-population
Route::post('/get-member-data', function (Request $request) {
    try {
        $profileIdOrUserId = $request->input('user_id');
        
        $user = null;
        
        // First, try to find by user_id directly
        $user = \App\Models\User::find($profileIdOrUserId);
        
        // If not found, try finding user by code field (profile_id)
        if (!$user) {
            $user = \App\Models\User::where('code', $profileIdOrUserId)->first();
        }
        
        // If still not found, try by member_name in services table
        if (!$user) {
            $service = \App\Models\Service::where('profile_id', $profileIdOrUserId)->first();
            if ($service && $service->member_name) {
                $user = \App\Models\User::where('name', 'like', '%' . $service->member_name . '%')->first();
            }
        }
        
        if (!$user) {
            return response()->json([
                'success' => false, 
                'message' => 'User not found. Profile ID: ' . $profileIdOrUserId . '. Please ensure the profile exists in the system.'
            ]);
        }

        // CONTACT DETAILS - Fetch from users table (customer fields)
        $customer_name = $user->first_name ?? '';
        $customer_email = $user->email ?? '';
        $customer_whatsapp = $user->phone ?? '';

        // Get member data
        $member = \App\Models\Member::where('user_id', $user->id)->first();
        if (!$member) {
            return response()->json([
                'success' => false, 
                'message' => 'Member details not found for this user'
            ]);
        }
        
        // Calculate age from birthday
        $age = null;
        if ($member->birthday) {
            $age = \Carbon\Carbon::parse($member->birthday)->age;
        }
        
        // Get education degree from education table
        $education = \App\Models\Education::where('user_id', $user->id)->first();
        $educationDegree = $education ? $education->degree : '';
        
        // Get marital status name from marital_statuses table
        $maritalStatusName = '';
        if ($member->marital_status_id) {
            $maritalStatus = \App\Models\MaritalStatus::find($member->marital_status_id);
            $maritalStatusName = $maritalStatus ? $maritalStatus->name : '';
        }
        
        // Get career information
        $occupation = '';
        $career = \App\Models\Career::where('user_id', $user->id)->first();
        if ($career) {
            $occupation = $career->designation ?? '';
        }
        
        // Get income from annual_salary_ranges
        $income = '';
        if ($member->annual_salary_range_id) {
            $salaryRange = \App\Models\AnnualSalaryRange::find($member->annual_salary_range_id);
            if ($salaryRange) {
                $income = number_format($salaryRange->min_salary, 2) . '-' . number_format($salaryRange->max_salary, 2);
            }
        }
        
        // Get family details
        $father_details = '';
        $mother_details = '';
        $sibling_details = '';
        $family = \App\Models\Family::where('user_id', $user->id)->first();
        if ($family) {
            $father_details = $family->father ?? '';
            if ($family->father_occupation) {
                $father_details .= $family->father ? (', ' . $family->father_occupation) : $family->father_occupation;
            }
            
            $mother_details = $family->mother ?? '';
            if ($family->mother_occupation) {
                $mother_details .= $family->mother ? (', ' . $family->mother_occupation) : $family->mother_occupation;
            }
            
            $sibling_parts = [];
            if ($family->no_of_sisters && $family->no_of_sisters > 0) {
                $sibling_parts[] = $family->no_of_sisters . ' sister' . ($family->no_of_sisters > 1 ? 's' : '');
            }
            if ($family->no_of_brothers && $family->no_of_brothers > 0) {
                $sibling_parts[] = $family->no_of_brothers . ' brother' . ($family->no_of_brothers > 1 ? 's' : '');
            }
            if ($family->about_sibling) {
                $sibling_parts[] = $family->about_sibling;
            }
            $sibling_details = count($sibling_parts) ? implode(', ', $sibling_parts) : '';
        }
        
        // Get caste and subcaste from spiritual_backgrounds
        $caste = '';
        $subcaste = '';
        $spiritual = \App\Models\SpiritualBackground::where('user_id', $user->id)->first();
        if ($spiritual) {
            $religion = \App\Models\Religion::find($spiritual->religion_id);
            $caste = $religion ? $religion->name : '';
            
            $casteObj = \App\Models\Caste::find($spiritual->caste_id);
            $subcaste = $casteObj ? $casteObj->name : '';
        }
        
        // Fetch partner preferences from partner_expectations table
        $preferred_weight = '';
        $preferred_education = '';
        $preferred_religion = '';
        $preferred_caste = '';
        $preferred_subcaste = '';
        $preferred_marital_status = '';
        $preferred_occupation = '';
        $preferred_family_status = '';
        $preferred_eating_habits = '';
        $preferred_annual_income = '';
        // Initialize optional preferred fields to avoid undefined variable notices
        $preferred_age_min = '';
        $preferred_age_max = '';
        $preferred_height = '';
        $preferred_complexion = '';
        $preferred_body_type = '';
        $preferred_smoking = '';
        $preferred_drinking = '';
        
        $pe = \App\Models\PartnerExpectation::where('user_id', $user->id)->first();
        if ($pe) {
            // Weight - direct column value
            $preferred_weight = $pe->weight ?? '';
            
            // Education - direct column value
            $preferred_education = $pe->education ?? '';
            
            // Religion - look up from religions table
            if ($pe->religion_id) {
                $religion = \App\Models\Religion::find($pe->religion_id);
                $preferred_religion = $religion ? $religion->name : '';
            }
            
            // Caste - look up from castes table
            if ($pe->caste_id) {
                $casteObj = \App\Models\Caste::find($pe->caste_id);
                $preferred_caste = $casteObj ? $casteObj->name : '';
            }
            
            // Subcaste - look up from sub_castes table
            if ($pe->sub_caste_id) {
                $subcasteObj = \App\Models\SubCaste::find($pe->sub_caste_id);
                $preferred_subcaste = $subcasteObj ? $subcasteObj->name : '';
            }
            
            // Marital status - look up from marital_statuses table
            if ($pe->marital_status_id) {
                $maritalStatusObj = \App\Models\MaritalStatus::find($pe->marital_status_id);
                $preferred_marital_status = $maritalStatusObj ? $maritalStatusObj->name : '';
            }
            
            // Occupation - direct column value (profession)
            $preferred_occupation = $pe->profession ?? '';
            
            // Family status - look up from family_values table
            if ($pe->family_value_id) {
                $familyValueObj = \App\Models\FamilyValue::find($pe->family_value_id);
                $preferred_family_status = $familyValueObj ? $familyValueObj->value : '';
            }
            
            // Eating habits - direct column value
            $preferred_eating_habits = $pe->eating_habits ?? $pe->diet ?? '';

            // Annual income - direct column value
            $preferred_annual_income = $pe->annual_income ?? '';

            // Age - prefer explicit min/max columns if present and normalize
            $preferred_age_min = is_numeric($pe->preferred_age_min) ? (int)$pe->preferred_age_min : null;
            $preferred_age_max = is_numeric($pe->preferred_age_max) ? (int)$pe->preferred_age_max : null;
            // If both exist and are reversed, swap them
            if ($preferred_age_min !== null && $preferred_age_max !== null && $preferred_age_min > $preferred_age_max) {
                [$preferred_age_min, $preferred_age_max] = [$preferred_age_max, $preferred_age_min];
            }

            // Height, complexion, body type, smoking and drinking
            $preferred_height = $pe->height ?? '';
            $preferred_complexion = $pe->complexion ?? '';
            $preferred_body_type = $pe->body_type ?? '';
            $preferred_smoking = $pe->smoking_acceptable ?? '';
            $preferred_drinking = $pe->drinking_acceptable ?? '';
        }
        
        return response()->json([
            'success' => true,
            // CONTACT DETAILS - From users table (both modern and backward-compatible keys)
            'customer_name' => $customer_name,
            'customer_email' => $customer_email,
            'customer_whatsapp' => $customer_whatsapp,
            // Backward-compatible keys used by the form/view
            'contact_customer_name' => $customer_name,
            'contact_email' => $customer_email,
            'contact_whatsapp_no' => $customer_whatsapp,
            'contact_mobile_no' => $customer_whatsapp,
            
            'member_name' => $user->name ?? ($user->first_name . ' ' . $user->last_name),
            'birthday' => $member->birthday ? $member->birthday->format('Y-m-d') : '',
            'age' => $age,
            'education' => $educationDegree,
            'marital_status' => $maritalStatusName,
            'occupation' => $occupation,
            'income' => $income,
            'family_status' => $member->family_status ?? '',
            'father_details' => $father_details,
            'mother_details' => $mother_details,
            'sibling_details' => $sibling_details,
            'caste' => $caste,
            'subcaste' => $subcaste,
            'preferred_age_min' => $preferred_age_min,
            'preferred_age_max' => $preferred_age_max,
            // For backward compatibility provide preferred_age as string
            'preferred_age' => ($preferred_age_min !== null && $preferred_age_max !== null) ? ($preferred_age_min . '-' . $preferred_age_max) : (($preferred_age_min !== null) ? (string)$preferred_age_min : (($preferred_age_max !== null) ? (string)$preferred_age_max : '')),
            'preferred_weight' => $preferred_weight,
            'preferred_education' => $preferred_education,
            'preferred_religion' => $preferred_religion,
            'preferred_caste' => $preferred_caste,
            'preferred_subcaste' => $preferred_subcaste,
            'preferred_marital_status' => $preferred_marital_status,
            'preferred_occupation' => $preferred_occupation,
            'preferred_family_status' => $preferred_family_status,
            'preferred_eating_habits' => $preferred_eating_habits,
            'preferred_annual_income' => $preferred_annual_income,
            'preferred_height' => $preferred_height,
            'preferred_complexion' => $preferred_complexion,
            'preferred_body_type' => $preferred_body_type,
            'preferred_smoking' => $preferred_smoking,
            'preferred_drinking' => $preferred_drinking
        ]);
        
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Error in get-member-data', [
            'error' => $e->getMessage(),
            'user_input' => $request->input('user_id')
        ]);
        return response()->json([
            'success' => false, 
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
});