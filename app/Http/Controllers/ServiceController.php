<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class ServiceController extends Controller
{
    // Store a new service (admin only)
    public function store(Request $request)
    {
        try {

            
            // Basic validation
            $request->validate([
                'profile_id' => 'required|string',
                'member_name' => 'required|string',
            ]);
            
            $data = $request->all();
            
            // Handle different field mappings from different forms
            $data['name'] = $data['service_name'] ?? $data['member_name'] ?? null;
            $data['service_executive'] = $data['service_executive'] ?? Auth::user()->first_name ?? 'admin';
            
            // Set default service_name if not provided (for admin modal)
            if (!isset($data['service_name'])) {
                $data['service_name'] = 'General Service';
            }
            
            // Determine status based on completed fields
            $hasServiceDetails = !empty($data['service_name']) && !empty($data['amount_paid']) && !empty($data['start_date']) && !empty($data['expiry_date']);
            $hasContactDetails = !empty($data['contact_mobile_no']) && !empty($data['contact_customer_name']);
            
            if ($hasServiceDetails && $hasContactDetails) {
                $data['status'] = 'active';
            } else {
                $data['status'] = 'new';
            }
            
            // Upsert: update if exists, else create (using only profile_id to prevent duplicates)
            $service = Service::updateOrCreate(
                [
                    'profile_id' => $data['profile_id']
                ],
                $data
            );
            

            
            // Check if this is an AJAX request (from admin modal)
            if ($request->expectsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json(['success' => true, 'message' => 'Service saved successfully!']);
            }
            
            // Regular form submission - redirect to new services page
            return redirect()->route('new.service')->with('success', 'Service saved successfully!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in ServiceController store', ['errors' => $e->errors()]);
            
            if ($request->expectsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json(['success' => false, 'message' => 'Validation failed: ' . implode(', ', $e->validator->errors()->all())], 422);
            }
            
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Error in ServiceController store', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            // Handle errors
            if ($request->expectsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
            }
            
            return redirect()->back()->withErrors(['error' => 'Failed to save service: ' . $e->getMessage()]);
        }
    }

    // Get service for editing
    public function edit($id)
    {
        try {
            $service = Service::findOrFail($id);
            
            // Check if user has permission to edit this service
            $user = Auth::user();
            if (!$user->is_admin && $service->service_executive !== $user->first_name) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            
            return response()->json(['service' => $service]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Service not found'], 404);
        }
    }

    // Update service
    public function update(Request $request, $id)
    {
        try {
            $service = Service::findOrFail($id);
            
            // Check if user has permission to edit this service
            $user = Auth::user();
            if (!$user->is_admin && $service->service_executive !== $user->first_name) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            
            // Validate request
            $request->validate([
                'profile_id' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'member_gender' => 'nullable|in:male,female,other',
                'contact_mobile_no' => 'required|string|max:20',
                'contact_alternate' => 'nullable|string|max:20',
                'service_executive' => 'required|string|max:255',
                'rm_change' => 'nullable|string|max:255',
                'edit_comment' => 'required|string|min:10|max:500'
            ]);
            
            // Handle RM Change tracking
            $updateData = [
                'profile_id' => $request->profile_id,
                'name' => $request->name,
                'member_gender' => $request->member_gender,
                'contact_mobile_no' => $request->contact_mobile_no,
                'contact_alternate' => $request->contact_alternate,
                'edit_comment' => $request->edit_comment,
                'edit_flag' => 'Y'
            ];

            // Check if RM is being changed
            if ($request->rm_change && $request->rm_change !== $service->service_executive) {
                // Store previous service executive
                $updateData['previous_service_executive'] = $service->service_executive;
                $updateData['service_executive'] = $request->rm_change;
                $updateData['rm_change'] = $request->rm_change;

                // Update RM change history
                $currentHistory = $service->rm_change_history ? json_decode($service->rm_change_history, true) : [];
                $currentHistory[] = [
                    'from' => $service->service_executive,
                    'to' => $request->rm_change,
                    'changed_at' => now()->toDateTimeString(),
                    'changed_by' => $user->first_name ?? $user->name,
                    'comment' => $request->edit_comment
                ];
                $updateData['rm_change_history'] = json_encode($currentHistory);
            } else {
                // Regular update without RM change
                $updateData['service_executive'] = $request->service_executive;
            }

            // Update service
            $service->update($updateData);
            
            return response()->json(['success' => true, 'message' => 'Service updated successfully']);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            $message = 'Validation failed';
            
            // Provide specific message for comment field
            if (isset($errors['edit_comment'])) {
                $message = 'Please provide a valid comment (minimum 10 characters) explaining the reason for this edit';
            }
            
            return response()->json(['success' => false, 'message' => $message, 'errors' => $errors], 422);
        } catch (\Exception $e) {
            Log::error('Error updating service', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to update service'], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $service = Service::findOrFail($id);
            
            // Check if user has permission to delete this service
            $user = Auth::user();
            if (!$user->is_admin && !($user->user_type === 'staff' && $service->service_executive === $user->first_name)) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            
            // Validate request - require comment for deletion
            $request->validate([
                'delete_comment' => 'required|string|min:10|max:500'
            ]);
            
            // Soft delete - update the deleted flag and add comment
            $service->update([
                'deleted' => 1,
                'delete_comment' => $request->delete_comment,
                'deleted_at' => now(),
                'deleted_by' => $user->id
            ]);
            
            return response()->json(['success' => true, 'message' => 'Service deleted successfully']);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            $message = 'Validation failed';
            
            // Provide specific message for comment field
            if (isset($errors['delete_comment'])) {
                $message = 'Please provide a valid comment (minimum 10 characters) explaining the reason for deletion';
            }
            
            return response()->json(['success' => false, 'message' => $message, 'errors' => $errors], 422);
        } catch (\Exception $e) {
            Log::error('Error deleting service', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to delete service'], 500);
        }
    }

    // List expired/deleted services (admin only)
    public function expiredServices()
    {
        $user = Auth::user();
        
        // Only admins can view expired services
        if (!$user->is_admin) {
            abort(403, 'Unauthorized');
        }
        
        // Get per page count from request, default to 10
        $perPage = request('per_page', 10);
        
        // Validate per page value
        if (!in_array($perPage, [10, 50, 100])) {
            $perPage = 10;
        }
        
        // Get search query
        $search = request('search');
        
        // Build query with search functionality for deleted services only
        $query = Service::where('deleted', 1);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('profile_id', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%")
                  ->orWhere('plan_name', 'LIKE', "%{$search}%")
                  ->orWhere('service_executive', 'LIKE', "%{$search}%")
                  ->orWhere('contact_mobile_no', 'LIKE', "%{$search}%")
                  ->orWhere('contact_customer_name', 'LIKE', "%{$search}%");
            });
        }
        
        // Use dynamic pagination - Latest deleted first
        $services = $query->orderByDesc('deleted_at')
                          ->orderByDesc('created_at')
                          ->paginate($perPage);
        
        // Append parameters to pagination links
        $services->appends(['per_page' => $perPage, 'search' => $search]);
        
        return view('profile.expiredservices', compact('services'));
    }

    // List services for the logged-in executive
    public function executiveServices()
    {
        $user = Auth::user();
        
        // Get per page count from request, default to 10
        $perPage = request('per_page', 10);
        
        // Validate per page value
        if (!in_array($perPage, [10, 50, 100])) {
            $perPage = 10;
        }
        
        // Get search query
        $search = request('search');
        
        // Build query with search functionality for executive services (exclude deleted)
        $query = Service::where('service_executive', $user->first_name)
                        ->where('deleted', 0);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('profile_id', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%")
                  ->orWhere('plan_name', 'LIKE', "%{$search}%")
                  ->orWhere('contact_mobile_no', 'LIKE', "%{$search}%")
                  ->orWhere('contact_customer_name', 'LIKE', "%{$search}%");
            });
        }
        
        // Use dynamic pagination for executive services - Latest first
        $services = $query->orderByDesc('created_at')
                          ->orderByDesc('id')
                          ->paginate($perPage);
        
        // Append parameters to pagination links
        $services->appends(['per_page' => $perPage, 'search' => $search]);
        
        // Get all staff users for dropdown
        $staffUsers = \App\Models\User::where('user_type', 'staff')
                                     ->orderBy('first_name')
                                     ->get(['id', 'first_name', 'name']);
        
        return view('profile.newservice', compact('services', 'staffUsers', 'perPage', 'search'));
    }

    // List all services (admin view)
    public function allServices()
    {
        // Get per page count from request, default to 10
        $perPage = request('per_page', 10);
        
        // Validate per page value
        if (!in_array($perPage, [10, 50, 100])) {
            $perPage = 10;
        }
        
        // Get search query
        $search = request('search');
        
        // Build query with search functionality (exclude deleted)
        $query = Service::where('deleted', 0);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('profile_id', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%")
                  ->orWhere('plan_name', 'LIKE', "%{$search}%")
                  ->orWhere('service_executive', 'LIKE', "%{$search}%")
                  ->orWhere('contact_mobile_no', 'LIKE', "%{$search}%")
                  ->orWhere('contact_customer_name', 'LIKE', "%{$search}%");
            });
        }
        
        // Use dynamic pagination - Latest services first
        $services = $query->orderByDesc('created_at')
                          ->orderByDesc('id')
                          ->paginate($perPage);
        
        // Append parameters to pagination links
        $services->appends(['per_page' => $perPage, 'search' => $search]);
        
        // Get all staff users for dropdown
        $staffUsers = \App\Models\User::where('user_type', 'staff')
                                     ->orderBy('first_name')
                                     ->get(['id', 'first_name', 'name']);
        
        return view('profile.newservice', compact('services', 'staffUsers', 'perPage', 'search'));
    }

    // Service Dashboard with dynamic counts
    public function servicesDashboard()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login');
            }
            
            if ($user->is_admin) {
                // Admin sees all services across all staff (exclude deleted)
                $totalServices = Service::where('deleted', 0)->count();
                $newServices = Service::where('deleted', 0)->where('status', 'new')->count();
                $activeServices = Service::where('deleted', 0)->where('status', 'active')->count();
                $completedServices = Service::where('deleted', 0)->where('status', 'completed')->count();
                $expiredServices = Service::where('deleted', 1)->count();
                
                // Get recent services for all staff (last 15 for admin, exclude deleted)
                $recentServices = Service::where('deleted', 0)
                                        ->orderBy('created_at', 'desc')
                                        ->limit(15)
                                        ->get();
            } else {
                // Staff sees only their assigned services
                // Use first_name, or fallback to username/email prefix if first_name is null
                $executiveName = $user->first_name ?? explode('@', $user->email)[0] ?? 'Unknown';
                
                $totalServices = Service::where('service_executive', $executiveName)
                                        ->where('deleted', 0)->count();
                $newServices = Service::where('service_executive', $executiveName)
                                     ->where('deleted', 0)
                                     ->where('status', 'new')
                                     ->count();
                $activeServices = Service::where('service_executive', $executiveName)
                                        ->where('deleted', 0)
                                        ->where('status', 'active')
                                        ->count();
                $completedServices = Service::where('service_executive', $executiveName)
                                           ->where('deleted', 0)
                                           ->where('status', 'completed')
                                           ->count();
                $expiredServices = Service::where('service_executive', $executiveName)
                                         ->where('deleted', 1)
                                         ->count();

                // Get recent services for current service executive (last 10, exclude deleted)
                $recentServices = Service::where('service_executive', $executiveName)
                                        ->where('deleted', 0)
                                        ->orderBy('created_at', 'desc')
                                        ->limit(10)
                                        ->get();
            }

            // Get all staff users for dropdown
            $staffUsers = \App\Models\User::where('user_type', 'staff')
                                         ->orderBy('first_name')
                                         ->get(['id', 'first_name', 'name']);

            return view('profile.services', compact('totalServices', 'newServices', 'activeServices', 'completedServices', 'expiredServices', 'recentServices', 'staffUsers'));
            
        } catch (\Exception $e) {
            // Log the error and return a fallback response
            Log::error('Services Dashboard Error: ' . $e->getMessage());
            
            // Return with default values if there's an error
            $totalServices = 0;
            $newServices = 0;
            $activeServices = 0;
            $completedServices = 0;
            $expiredServices = 0;
            $recentServices = collect([]);
            
            // Get all staff users for dropdown even in error case
            $staffUsers = \App\Models\User::where('user_type', 'staff')
                                         ->orderBy('first_name')
                                         ->get(['id', 'first_name', 'name']);
            
            return view('profile.services', compact('totalServices', 'newServices', 'activeServices', 'completedServices', 'expiredServices', 'recentServices', 'staffUsers'));
        }
    }

    // Update service status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:new,active,completed,cancelled'
        ]);

        $service = Service::findOrFail($id);
        $service->status = $request->status;
        $service->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    // Save service section (progressive saving)
    public function saveSection(Request $request)
    {
        try {
            Log::info('SaveSection called', [
                'data' => $request->all(),
                'section' => $request->input('section')
            ]);

            // Basic validation
            $request->validate([
                'profile_id' => 'required|string',
                'section' => 'required|string'
            ]);

            $data = $request->all();
            $section = $data['section'];
            unset($data['section']); // Remove section from data to save

            // Handle different field mappings
            $data['service_executive'] = $data['service_executive'] ?? Auth::user()->first_name ?? 'admin';
            
            // Find or create service record first
            $service = Service::updateOrCreate(
                ['profile_id' => $data['profile_id']],
                $data
            );

            // Now check the complete service record to determine status
            $hasServiceDetails = !empty($service->service_name) && !empty($service->amount_paid) && !empty($service->start_date) && !empty($service->expiry_date);
            $hasContactDetails = !empty($service->contact_mobile_no) && !empty($service->contact_customer_name);
            
            if ($hasServiceDetails && $hasContactDetails) {
                $service->status = 'active';
                $service->save();
            } else {
                $service->status = 'new';
                $service->save();
            }

            Log::info('Section saved successfully', [
                'section' => $section,
                'service_id' => $service->id,
                'status' => $service->status
            ]);

            return response()->json([
                'success' => true, 
                'message' => ucfirst($section) . ' section saved successfully',
                'service_id' => $service->id,
                'status' => $service->status
            ]);

        } catch (\Exception $e) {
            Log::error('Error in saveSection', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false, 
                'message' => 'Error saving section: ' . $e->getMessage()
            ], 500);
        }
    }

    // Shortlist from INA
    public function shortlistIna($serviceId)
    {
        $service = Service::find($serviceId);
        
        if (!$service) {
            // Create a dummy service for the view if service doesn't exist
            $service = new Service(['id' => $serviceId, 'name' => 'Service ' . $serviceId]);
        }
        
        return view('profile.shortlist-ina', compact('service'));
    }

    // Shortlist from Others
    public function shortlistOthers($serviceId)
    {
        $service = Service::find($serviceId);
        
        if (!$service) {
            // Create a dummy service for the view if service doesn't exist
            $service = new Service(['id' => $serviceId, 'name' => 'Service ' . $serviceId]);
        }
        
        return view('profile.shortlist-others', compact('service'));
    }

    // View Prospects
    public function viewProspects($serviceId)
    {
        $service = Service::find($serviceId);
        
        if (!$service) {
            // Create a dummy service for the view if service doesn't exist
            $service = new Service(['id' => $serviceId, 'name' => 'Service ' . $serviceId]);
        }
        
        return view('profile.view-prospects', compact('service'));
    }

    // Client Details
    public function clientDetails($clientId)
    {
        $service = Service::find($clientId);
        
        if (!$service) {
            // Create a dummy service for the view if service doesn't exist
            $service = new Service(['id' => $clientId, 'name' => 'Client ' . $clientId]);
        }
        
        return view('profile.view-client-details', compact('service'));
    }

    // Search profiles (for shortlist-ina)
    public function searchProfiles(Request $request)
    {
        try {
            // Validate the search criteria
            $request->validate([
                'age_min' => 'nullable|integer|min:18|max:100',
                'age_max' => 'nullable|integer|min:18|max:100',
                'height_min' => 'nullable|integer|min:100|max:250',
                'height_max' => 'nullable|integer|min:100|max:250',
                'weight_min' => 'nullable|integer|min:30|max:200',
                'weight_max' => 'nullable|integer|min:30|max:200',
                'religion' => 'nullable|string',
                'caste' => 'nullable|string',
                'district' => 'nullable|string',
                'education' => 'nullable|string',
                'occupation' => 'nullable|string',
                'location' => 'nullable|string',
                'marital_status' => 'nullable|string',
                'registered_from' => 'nullable|date',
                'registered_to' => 'nullable|date',
                'profile_id' => 'nullable|string',
            ]);

            // Build the search query with all related data
            $query = \App\Models\Member::with([
                'user',
                'user.physicalAttribute',
                'user.spiritualBackground.religion',
                'user.spiritualBackground.caste', 
                'user.career',
                'user.profilePhoto',
                'user.primaryAddress.city',
                'maritalStatus'
            ])->whereNotNull('birthday');
                
            // Debug: Let's temporarily remove user restrictions to test age filtering
            // ->whereHas('user', function($q) {
            //     $q->where('approved', 1)
            //       ->where('blocked', 0)
            //       ->where('deactivated', 0);
            // });

            // Apply age range filter
            if ($request->filled('age_min') || $request->filled('age_max')) {
                $minAge = $request->age_min;
                $maxAge = $request->age_max;
                $query->byAgeRange($minAge, $maxAge);
            }

            // Apply height range filter
            if ($request->filled('height_min') || $request->filled('height_max')) {
                Log::info('Height filter applied', [
                    'height_min' => $request->height_min,
                    'height_max' => $request->height_max
                ]);
                
                $query->whereHas('user.physicalAttribute', function($q) use ($request) {
                    if ($request->filled('height_min')) {
                        $q->where('height', '>=', $request->height_min);
                    }
                    if ($request->filled('height_max')) {
                        $q->where('height', '<=', $request->height_max);
                    }
                });
            }

            // Apply weight range filter
            if ($request->filled('weight_min') || $request->filled('weight_max')) {
                Log::info('Weight filter applied', [
                    'weight_min' => $request->weight_min,
                    'weight_max' => $request->weight_max
                ]);
                
                $query->whereHas('user.physicalAttribute', function($q) use ($request) {
                    if ($request->filled('weight_min')) {
                        $q->where('weight', '>=', $request->weight_min);
                    }
                    if ($request->filled('weight_max')) {
                        $q->where('weight', '<=', $request->weight_max);
                    }
                });
            }

            // Apply gender filter (convert text to numeric: male=1, female=2)
            if ($request->filled('gender')) {
                $genderValue = null;
                if (strtolower($request->gender) === 'male') {
                    $genderValue = 1;
                } elseif (strtolower($request->gender) === 'female') {
                    $genderValue = 2;
                }
                
                if ($genderValue) {
                    Log::info('Gender filter applied', [
                        'requested_gender' => $request->gender,
                        'database_value' => $genderValue
                    ]);
                    $query->where('gender', $genderValue);
                }
            }

            // Apply marital status filter
            if ($request->filled('marital_status')) {
                $query->where('marital_status_id', $request->marital_status);
            }

            // Apply district filter using address/city relationships
            if ($request->filled('district')) {
                Log::info('District filter applied', ['district' => $request->district]);
                $district = $request->district;
                
                $query->where(function($mainQ) use ($district) {
                    // Search in all addresses
                    $mainQ->whereHas('user.addresses.city', function($q) use ($district) {
                        $q->where(function($subQ) use ($district) {
                            $subQ->where('name', 'like', '%' . $district . '%')
                                 ->orWhere('name', strtolower($district))
                                 ->orWhere('name', strtoupper($district))
                                 ->orWhere('name', ucfirst(strtolower($district)))
                                 ->orWhere('name', ucwords(strtolower($district)));
                        });
                    });
                });
            }

            // Apply registration date filters
            if (($request->filled('registered_from') && $request->registered_from != '') || 
                ($request->filled('registered_to') && $request->registered_to != '')) {
                
                $fromDate = $request->registered_from;
                $toDate = $request->registered_to;
                
                Log::info('Registration date range filter applied', [
                    'from_date' => $fromDate,
                    'to_date' => $toDate
                ]);
                
                $query->whereHas('user', function($q) use ($fromDate, $toDate) {
                    if ($fromDate && $fromDate != '') {
                        $q->whereDate('created_at', '>=', $fromDate);
                    }
                    if ($toDate && $toDate != '') {
                        $q->whereDate('created_at', '<=', $toDate);
                    }
                });
            }

            // Apply caste filter (actually religion filter through spiritual_background -> religions)
            if ($request->filled('caste')) {
                Log::info('Religion filter applied', ['religion' => $request->caste]);
                
                $query->whereHas('user.spiritualBackground.religion', function($q) use ($request) {
                    $religion = $request->caste;
                    $q->where('name', 'like', '%' . $religion . '%')
                      ->orWhere('name', strtolower($religion))
                      ->orWhere('name', strtoupper($religion))
                      ->orWhere('name', ucfirst(strtolower($religion)));
                });
            }

            // Apply profile ID filter (search by users.code field)
            if ($request->filled('profile_id')) {
                Log::info('Profile ID filter applied', ['profile_id' => $request->profile_id]);
                $profileId = $request->profile_id;
                
                $query->whereHas('user', function($userQ) use ($profileId) {
                    $userQ->where('code', $profileId)
                          ->orWhere('code', 'like', '%' . $profileId . '%');
                });
                
                Log::info('Profile ID search completed', [
                    'profile_id' => $profileId,
                    'search_method' => 'users.code_field'
                ]);
            }

            // Apply location filter (search by city name through addresses)
            if ($request->filled('location')) {
                Log::info('Location filter applied', ['location' => $request->location]);
                $location = $request->location;
                
                $query->whereHas('user.primaryAddress.city', function($q) use ($location) {
                    $q->where('name', 'like', '%' . $location . '%')
                      ->orWhere('name', strtolower($location))
                      ->orWhere('name', strtoupper($location))
                      ->orWhere('name', ucfirst(strtolower($location)));
                });
            }



            // Debug: Count before final execution
            $countBeforeFilters = \App\Models\Member::whereNotNull('birthday')->count();
            Log::info('Count before all filters', ['count' => $countBeforeFilters]);
            
            // Debug: Check some sample user creation dates
            $sampleUsers = \App\Models\User::select('id', 'created_at')->orderBy('created_at', 'desc')->limit(5)->get();
            Log::info('Sample user creation dates', [
                'users' => $sampleUsers->map(function($user) {
                    return ['id' => $user->id, 'created_at' => $user->created_at->format('Y-m-d H:i:s')];
                })->toArray()
            ]);
            
            // Get total count and execute search
            $totalMembers = \App\Models\Member::count();
            
            // Debug: Count after all filters
            $countAfterFilters = $query->count();
            Log::info('Count after all filters', ['count' => $countAfterFilters]);
            
            // Limit results to prevent memory issues (show first 100 matches)
            $matchingProfiles = $query->limit(100)->get();
            $matchingCount = $countAfterFilters; // Use the total count, not just the limited results

            // Prepare search results data with detailed profile information
            $searchResults = $matchingProfiles->map(function($member) {
                try {
                    $physicalAttr = $member->user->physicalAttribute ?? null;
                    $spiritualBg = $member->user->spiritualBackground ?? null;
                    $career = $member->user->career ?? null;
                    $profilePhoto = $member->user->profilePhoto ?? null;
                    

                    
                    return [
                        'id' => $member->id,
                        'user_id' => $member->user_id,
                        'name' => $member->user->first_name . ' ' . $member->user->last_name,
                        'age' => $member->age,
                        'gender' => $member->gender == 1 ? 'Male' : ($member->gender == 2 ? 'Female' : 'Not specified'),
                        'code' => $member->user->code,
                        'email' => $member->user->email,
                        'phone' => $member->user->phone,
                        'created_at' => $member->user->created_at->format('Y-m-d'),
                        
                        // Physical attributes
                        'height' => $physicalAttr ? $physicalAttr->height : 'Not specified',
                        'weight' => $physicalAttr ? $physicalAttr->weight : 'Not specified',
                        
                        // Spiritual background
                        'religion' => $spiritualBg && $spiritualBg->religion ? $spiritualBg->religion->name : 'Not specified',
                        'caste' => $spiritualBg && $spiritualBg->caste ? $spiritualBg->caste->name : 'Not specified',
                        
                        // Marital status
                        'marital_status' => $member->maritalStatus ? $member->maritalStatus->name : 'Not specified',
                        
                        // Career information
                        'designation' => $career && $career->designation ? $career->designation : 'Not specified',
                        'company' => $career && $career->company ? $career->company : 'Not specified',
                        'career' => $career ? trim(($career->designation ?? '') . ' ' . ($career->company ? 'at ' . $career->company : '')) : 'Not specified',
                        
                        // Location from addresses table via city relationship
                        'location' => ($member->user->primaryAddress && $member->user->primaryAddress->city) 
                            ? $member->user->primaryAddress->city->name 
                            : ($member->user->district ?? $member->user->city ?? 'Not specified'),
                        
                        // Profile image - try multiple possible paths
                        'photo_url' => $member->user->photo ? $this->getPhotoUrl($member->user->photo) : null,
                        'has_photo' => $member->user->photo ? true : false,

                    ];
                } catch (\Exception $e) {
                    // Fallback if relationships don't exist
                    return [
                        'id' => $member->id,
                        'user_id' => $member->user_id,
                        'name' => $member->user->first_name . ' ' . $member->user->last_name,
                        'age' => $member->age,
                        'gender' => $member->gender == 1 ? 'Male' : ($member->gender == 2 ? 'Female' : 'Not specified'),
                        'code' => $member->user->code,
                        'email' => $member->user->email,
                        'phone' => $member->user->phone,
                        'created_at' => $member->user->created_at->format('Y-m-d'),
                        'height' => 'Not available',
                        'weight' => 'Not available',
                        'religion' => 'Not available',
                        'caste' => 'Not available',
                        'marital_status' => 'Not available',
                        'career' => 'Not available',
                        'location' => $member->user->district ?? $member->user->city ?? 'Not available',
                        'photo_url' => null,
                        'has_photo' => false,

                    ];
                }
            });

            Log::info('Profile search completed', [
                'search_criteria' => $request->all(),
                'total_found' => $matchingCount,
                'user' => Auth::user()->first_name ?? 'Unknown'
            ]);
            
            // Check if it's an AJAX request
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Search completed successfully!',
                    'data' => [
                        'total_profiles' => $totalMembers,
                        'matching_profiles' => $matchingCount,
                        'search_criteria' => $request->all(),
                        'results' => $searchResults
                    ]
                ]);
            }

            // Regular form submission - redirect back with success and results
            $displayMessage = "Search completed successfully! Found {$matchingCount} matching profiles out of {$totalMembers} total profiles.";
            if ($matchingCount > 100) {
                $displayMessage .= " (Showing first 100 results)";
            }
            
            return redirect()->back()
                ->with('success', $displayMessage)
                ->with('search_results', $searchResults)
                ->with('search_criteria', $request->all());

        } catch (\Exception $e) {
            Log::error('Error in searchProfiles', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            // Check if it's an AJAX request
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Search failed: ' . $e->getMessage()
                ], 500);
            }

            // Regular form submission - redirect back with error
            return redirect()->back()->withErrors(['error' => 'Search failed: ' . $e->getMessage()])->withInput();
        }
    }

    // Assign profile from other sources (for shortlist-others)
    // Helper method to get photo URL from uploads table
    private function getPhotoUrl($photoId)
    {
        if (!$photoId) return null;
        
        try {
            $upload = \App\Models\Upload::find($photoId);
            if ($upload && $upload->file_name) {
                // Check if file exists
                $filePath = public_path($upload->file_name);
                if (file_exists($filePath)) {
                    return asset($upload->file_name);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error getting photo URL for ID ' . $photoId, ['error' => $e->getMessage()]);
        }
        
        return null;
    }

    public function assignProfile(Request $request)
    {
        try {
            // Validate the assignment data
            $request->validate([
                'profile_id' => 'required|string',
                'other_site_member_id' => 'required|string',
                'other_site_url' => 'nullable|url',
                'notes' => 'nullable|string|max:500'
            ]);

            // Here you would implement actual profile assignment logic
            // For now, return success message
            
            Log::info('Profile assignment requested', [
                'profile_id' => $request->profile_id,
                'other_site_member_id' => $request->other_site_member_id,
                'user' => Auth::user()->first_name ?? 'Unknown'
            ]);

            // Check if it's an AJAX request
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profile assigned successfully!',
                    'data' => [
                        'profile_id' => $request->profile_id,
                        'other_site_member_id' => $request->other_site_member_id,
                        'assigned_by' => Auth::user()->first_name ?? 'Unknown',
                        'assigned_at' => now()->format('Y-m-d H:i:s')
                    ]
                ]);
            }

            // Regular form submission - redirect back with success
            return redirect()->back()->with('success', 'Profile assigned successfully! Profile ID: ' . $request->profile_id . ' has been assigned from other site.');

        } catch (\Exception $e) {
            Log::error('Error in assignProfile', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);

            // Check if it's an AJAX request
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignment failed: ' . $e->getMessage()
                ], 500);
            }

            // Regular form submission - redirect back with error
            return redirect()->back()->withErrors(['error' => 'Assignment failed: ' . $e->getMessage()])->withInput();
        }
    }
}
