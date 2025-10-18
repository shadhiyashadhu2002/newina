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
            $user = Auth::user();
            $data['service_executive'] = $data['service_executive'] ?? ($user && $user->first_name ? $user->first_name : ($user->name ?? 'admin'));
            // Normalize service_executive to lowercase trimmed string for consistent filtering
            $data['service_executive'] = strtolower(trim($data['service_executive']));
            $data['tracking_updated_by'] = ($user && $user->first_name && trim($user->first_name) !== '') ? $user->first_name : ($user->name ?? 'Unknown');
            
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
            

            
            // Remove only the completed profile from the badge session array
            $pending = session('show_service_badge', []);
            $profileId = $data['profile_id'] ?? null;
            if ($profileId && is_array($pending)) {
                $pending = array_filter($pending, function($item) use ($profileId) {
                    return $item['profile_id'] != $profileId;
                });
                // Reindex array
                $pending = array_values($pending);
                if (count($pending) > 0) {
                    session(['show_service_badge' => $pending]);
                } else {
                    session()->forget('show_service_badge');
                }
            } else {
                session()->forget('show_service_badge');
            }
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
        Log::info('ServiceController update called', [
            'method' => $request->method(),
            'all' => $request->all(),
            'user' => Auth::user() ? (array) Auth::user() : null
        ]);
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
                'edit_flag' => 'Y',
                'tracking_updated_by' => ($user->first_name && trim($user->first_name) !== '') ? $user->first_name : ($user->name ?? 'Unknown'),
            ];
            // Save status/tracking_status if present and not null/empty
            if ($request->has('status') && !is_null($request->status) && $request->status !== '') {
                $updateData['status'] = $request->status;
            }

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
            Log::error('ValidationException in update', ['errors' => $errors, 'exception' => $e->getMessage()]);
            // Provide specific message for comment field
            if (isset($errors['edit_comment'])) {
                $message = 'Please provide a valid comment (minimum 10 characters) explaining the reason for this edit';
            }
            return response()->json(['success' => false, 'message' => $message, 'errors' => $errors], 422);
        } catch (\Exception $e) {
            Log::error('Exception in update', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
                'user' => Auth::user() ? (array) Auth::user() : null
            ]);
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
    $execName = strtolower(trim($user->first_name ?? explode('@', $user->email)[0] ?? ''));
    $query = Service::whereRaw('LOWER(TRIM(service_executive)) = ?', [$execName])
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

    // List active services (for admin show all, for staff show assigned)
    public function activeServiceList()
    {
        $user = Auth::user();

        // Get per page count from request, default to 10
        $perPage = request('per_page', 10);
        if (!in_array($perPage, [10, 50, 100])) {
            $perPage = 10;
        }

        $search = request('search');

        // Build query for active services
        $query = Service::where('status', 'active')->where('deleted', 0);

        // If not admin, limit to this executive
        if ($user && !$user->is_admin) {
            $executiveName = strtolower(trim($user->first_name ?? explode('@', $user->email)[0] ?? 'Unknown'));
            $query->whereRaw('LOWER(TRIM(service_executive)) = ?', [$executiveName]);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('profile_id', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%")
                  ->orWhere('service_executive', 'LIKE', "%{$search}%")
                  ->orWhere('contact_mobile_no', 'LIKE', "%{$search}%");
            });
        }

        $services = $query->orderByDesc('start_date')
                          ->orderByDesc('created_at')
                          ->paginate($perPage);

        // append query params
        $services->appends(['per_page' => $perPage, 'search' => $search]);

        return view('profile.activeservice', compact('services', 'perPage', 'search'));
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
                $newServices = Service::where('deleted', 0)->whereIn('status', ['pending', 'new'])->count();
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
                $executiveName = strtolower(trim($user->first_name ?? explode('@', $user->email)[0] ?? 'Unknown'));
                
                $totalServices = Service::whereRaw('LOWER(TRIM(service_executive)) = ?', [$executiveName])
                                        ->where('deleted', 0)->count();
                $newServices = Service::whereRaw('LOWER(TRIM(service_executive)) = ?', [$executiveName])
                                     ->where('deleted', 0)
                                     ->whereIn('status', ['pending', 'new'])
                                     ->count();
                $activeServices = Service::whereRaw('LOWER(TRIM(service_executive)) = ?', [$executiveName])
                                        ->where('deleted', 0)
                                        ->where('status', 'active')
                                        ->count();
                $completedServices = Service::whereRaw('LOWER(TRIM(service_executive)) = ?', [$executiveName])
                                           ->where('deleted', 0)
                                           ->where('status', 'completed')
                                           ->count();
                $expiredServices = Service::whereRaw('LOWER(TRIM(service_executive)) = ?', [$executiveName])
                                         ->where('deleted', 1)
                                         ->count();

                // Get recent services for current service executive (last 10, exclude deleted)
                $recentServices = Service::whereRaw('LOWER(TRIM(service_executive)) = ?', [$executiveName])
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

    // Alternative services page (dashboard-like) requested by user
    public function servicesPage(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->is_admin ?? false;

        // Use normalized comparisons for service_executive when filtering for staff
        if ($isAdmin) {
            // ADMIN VIEW - Show ALL services (exclude deleted)
            $totalServices = Service::where('deleted', 0)->count();
            // Accept both 'pending' and legacy 'new' as new services
            $newServices = Service::where('deleted', 0)->whereIn('status', ['pending', 'new'])->count();
            // Active services are those marked 'active' and not expired
            $activeServices = Service::where('deleted', 0)
                                     ->where('status', 'active')
                                     ->where('expiry_date', '>=', now())
                                     ->count();
            $completedServices = Service::where('deleted', 0)->where('status', 'completed')->count();
            $expiredServices = Service::where('deleted', 0)->where('expiry_date', '<', now())->count();

            $recentServices = Service::where('deleted', 0)->latest()->take(10)->get();

        } else {
            // STAFF VIEW - Show only their assigned services
            $staffName = strtolower(trim($user->first_name ?? explode('@', $user->email)[0] ?? ''));

            $totalServices = Service::whereRaw('LOWER(TRIM(service_executive)) = ?', [$staffName])
                                    ->where('deleted', 0)
                                    ->count();
            $newServices = Service::whereRaw('LOWER(TRIM(service_executive)) = ?', [$staffName])
                                 ->where('deleted', 0)
                                 ->whereIn('status', ['pending', 'new'])
                                 ->count();
            $activeServices = Service::whereRaw('LOWER(TRIM(service_executive)) = ?', [$staffName])
                                    ->where('deleted', 0)
                                    ->where('status', 'active')
                                    ->where('expiry_date', '>=', now())
                                    ->count();
            $completedServices = Service::whereRaw('LOWER(TRIM(service_executive)) = ?', [$staffName])
                                       ->where('deleted', 0)
                                       ->where('status', 'completed')
                                       ->count();
            $expiredServices = Service::whereRaw('LOWER(TRIM(service_executive)) = ?', [$staffName])
                                     ->where('deleted', 0)
                                     ->where('expiry_date', '<', now())
                                     ->count();

            $recentServices = Service::whereRaw('LOWER(TRIM(service_executive)) = ?', [$staffName])
                                    ->where('deleted', 0)
                                    ->latest()
                                    ->take(10)
                                    ->get();
        }

        return view('services-dashboard', [
            'totalServices' => $totalServices,
            'newServices' => $newServices,
            'activeServices' => $activeServices,
            'completedServices' => $completedServices,
            'expiredServices' => $expiredServices,
            'recentServices' => $recentServices,
            'isAdmin' => $isAdmin
        ]);
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

            // Basic validation (profile_id optional for Assign-from-Other flow)
            $request->validate([
                'profile_id' => 'nullable|string',
                'section' => 'required|string'
            ]);

            $data = $request->all();
            $section = $data['section'];
            unset($data['section']); // Remove section from data to save

            // Remove any raw file inputs from the data array so updateOrCreate doesn't try to store
            // temporary file paths into integer columns (e.g., 'photo'). Files are handled separately.
            if (isset($data['photo'])) unset($data['photo']);
            if (isset($data['photoData'])) unset($data['photoData']);

            // Handle different field mappings
            $data['service_executive'] = $data['service_executive'] ?? Auth::user()->first_name ?? 'admin';
            $data['service_executive'] = strtolower(trim($data['service_executive']));

            // Ensure 'name' column is populated (DB requires it)
            // Prefer explicit 'name', then 'service_name', then 'member_name'
            if (empty($data['name'])) {
                if (!empty($data['service_name'])) {
                    $data['name'] = $data['service_name'];
                } elseif (!empty($data['member_name'])) {
                    $data['name'] = $data['member_name'];
                } else {
                    $data['name'] = 'Unknown';
                }
            }
            
            // If profile_id not provided, generate a unique one server-side
            if (empty($data['profile_id'])) {
                // Simple generator: timestamp + random suffix to reduce collision chance
                $data['profile_id'] = 'AUTO-' . time() . '-' . substr(bin2hex(random_bytes(4)), 0, 6);
                Log::info('Generated profile_id for saveSection', ['profile_id' => $data['profile_id']]);
            }

            // Find or create service record first (upsert by profile_id)
            try {
                $service = Service::updateOrCreate(
                    ['profile_id' => $data['profile_id']],
                    $data
                );
            } catch (\Illuminate\Database\QueryException $qe) {
                // If the insert failed due to missing required 'name' column, retry with a default name
                Log::error('QueryException in saveSection updateOrCreate', ['error' => $qe->getMessage(), 'data' => $data]);
                if (strpos($qe->getMessage(), "Field 'name' doesn't have a default value") !== false || strpos($qe->getMessage(), 'doesn\'t have a default value') !== false) {
                    $data['name'] = $data['name'] ?? ($data['member_name'] ?? 'Unknown');
                    $service = Service::updateOrCreate(['profile_id' => $data['profile_id']], $data);
                } else {
                    // rethrow if it's an unrelated DB error
                    throw $qe;
                }
            }

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

            // If this save came from the Assign-from-Other flow or includes other-site fields,
            // mark the record with who assigned it so assignedProfiles() can find it.
           try {
    $shouldMarkAssigned = ($section === 'others') || (!empty($data['other_site_member_id'])) || (!empty($data['profile_source'])) || (!empty($data['assigned_at']));
    if ($shouldMarkAssigned) {
        $assignedBy = Auth::user()->first_name ?? Auth::user()->name ?? '';
        $service->assigned_by = strtolower(trim($assignedBy)); // NORMALIZE TO LOWERCASE TRIMMED
        // only set assigned_at if not already set
        if (empty($service->assigned_at)) {
            $service->assigned_at = now();
        }
        $service->save();
        Log::info('Service marked as assigned', [
            'service_id' => $service->id, 
            'assigned_by' => $service->assigned_by,
            'profile_id' => $service->profile_id
        ]);
    }
} catch (\Exception $e) {
    Log::warning('Could not mark service as assigned', ['error' => $e->getMessage(), 'service_id' => $service->id]);
}

            // Handle an uploaded photo (if provided) and link it via uploads table
            try {
                // If the client uploaded earlier and provided photo_id, prefer that and skip processing
                if ($request->has('photo_id') && !empty($request->input('photo_id'))) {
                    $photoId = (int) $request->input('photo_id');
                    Log::info('Linking uploaded photo id to service', ['service_id' => $service->id, 'photo_id' => $photoId]);
                    if ($photoId > 0) {
                        $service->photo = $photoId;
                        $service->save();
                    }
                } else {
                    // Only process a direct file upload if no photo_id was supplied
                    if ($request->hasFile('photo')) {
                        $file = $request->file('photo');
                        // Ensure the uploaded tmp file still exists and is readable before operating on it
                        $realPath = $file && method_exists($file, 'getRealPath') ? $file->getRealPath() : null;
                        if ($file && $file->isValid() && $realPath && file_exists($realPath) && is_readable($realPath)) {
                            // Create a unique filename and move to public/uploads
                            $destPath = public_path('uploads');
                            if (!file_exists($destPath)) mkdir($destPath, 0755, true);
                            $filename = time() . '_' . bin2hex(random_bytes(4)) . '.' . $file->getClientOriginalExtension();
                            $moved = $file->move($destPath, $filename);

                            // Determine size from the moved file on disk to avoid tmp stat issues
                            $movedPath = $destPath . DIRECTORY_SEPARATOR . $filename;
                            $size = null;
                            if (file_exists($movedPath)) {
                                $size = filesize($movedPath);
                            }

                            // Create an Upload record
                            $upload = \App\Models\Upload::create([
                                'file_original_name' => $file->getClientOriginalName(),
                                'file_name' => 'uploads/' . $filename,
                                'user_id' => Auth::id() ?? null,
                                'file_size' => $size,
                                'extension' => $file->getClientOriginalExtension(),
                                'type' => $file->getClientMimeType(),
                            ]);

                            if ($upload && $upload->id) {
                                $service->photo = $upload->id;
                                $service->save();
                            }
                        } else {
                            Log::warning('Uploaded photo tmp file missing or unreadable; skipping file save', ['realPath' => $realPath, 'service_id' => $service->id]);
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Error saving uploaded photo for service', ['error' => $e->getMessage(), 'service_id' => $service->id]);
            }

            // If partner section, persist partner expectations to partner_expectations table
            if ($section === 'partner') {
                try {
                    $profileId = $service->profile_id;
                    // Try to find user by id or code
                    $user = null;
                    if (is_numeric($profileId)) {
                        $user = \App\Models\User::find($profileId);
                    }
                    if (!$user) {
                        $user = \App\Models\User::where('code', $profileId)->first();
                    }

                    if ($user) {
                        $peData = [];
                        // Map incoming frontend keys to DB columns
                        if ($request->has('preferred_age')) {
                            $val = $request->input('preferred_age');
                            // If a range like 25-30 provided, split
                            if (strpos($val, '-') !== false) {
                                [$min, $max] = array_map('trim', explode('-', $val, 2));
                                $peData['preferred_age_min'] = is_numeric($min) ? (int)$min : null;
                                $peData['preferred_age_max'] = is_numeric($max) ? (int)$max : null;
                            } else {
                                $peData['preferred_age_min'] = is_numeric($val) ? (int)$val : null;
                            }
                        }
                        if ($request->has('preferred_age_min')) {
                            $peData['preferred_age_min'] = is_numeric($request->input('preferred_age_min')) ? (int)$request->input('preferred_age_min') : null;
                        }
                        if ($request->has('preferred_age_max')) {
                            $peData['preferred_age_max'] = is_numeric($request->input('preferred_age_max')) ? (int)$request->input('preferred_age_max') : null;
                        }
                        if ($request->has('preferred_height')) {
                            $peData['preferred_height'] = $request->input('preferred_height');
                        }
                        if ($request->has('preferred_weight')) {
                            $peData['preferred_weight'] = $request->input('preferred_weight');
                        }
                        if ($request->has('preferred_complexion')) {
                            $peData['complexion'] = $request->input('preferred_complexion');
                        }
                        if ($request->has('preferred_body_type')) {
                            $peData['preferred_body_type'] = $request->input('preferred_body_type');
                        }
                        if ($request->has('preferred_education')) {
                            $peData['preferred_education'] = $request->input('preferred_education');
                        }
                        if ($request->has('preferred_occupation')) {
                            $peData['preferred_profession'] = $request->input('preferred_occupation');
                        }
                        if ($request->has('preferred_annual_income')) {
                            $peData['preferred_annual_income'] = $request->input('preferred_annual_income');
                        }
                        // smoking/drinking inputs could be strings like 'Yes'/'No' — normalize to boolean/null
                        if ($request->has('preferred_smoking')) {
                            $v = strtolower($request->input('preferred_smoking'));
                            if (in_array($v, ['yes','y','true','1'])) $peData['smoking_acceptable'] = 1;
                            elseif (in_array($v, ['no','n','false','0'])) $peData['smoking_acceptable'] = 0;
                            else $peData['smoking_acceptable'] = null;
                        }
                        if ($request->has('preferred_drinking')) {
                            $v = strtolower($request->input('preferred_drinking'));
                            if (in_array($v, ['yes','y','true','1'])) $peData['drinking_acceptable'] = 1;
                            elseif (in_array($v, ['no','n','false','0'])) $peData['drinking_acceptable'] = 0;
                            else $peData['drinking_acceptable'] = null;
                        }
                        if ($request->has('preferred_eating_habits')) {
                            $peData['diet'] = $request->input('preferred_eating_habits');
                        }

                        // Resolve textual lookups to IDs where possible
                        if ($request->has('preferred_religion')) {
                            $val = $request->input('preferred_religion');
                            $rel = \App\Models\Religion::where('name', 'like', $val)->orWhere('name', 'like', '%' . $val . '%')->first();
                            if ($rel) $peData['preferred_religion_id'] = $rel->id;
                        }
                        if ($request->has('preferred_caste')) {
                            $val = $request->input('preferred_caste');
                            $c = \App\Models\Caste::where('name', 'like', $val)->orWhere('name', 'like', '%' . $val . '%')->first();
                            if ($c) $peData['preferred_caste_id'] = $c->id;
                        }
                        if ($request->has('preferred_subcaste')) {
                            $val = $request->input('preferred_subcaste');
                            $sc = \App\Models\SubCaste::where('name', 'like', $val)->orWhere('name', 'like', '%' . $val . '%')->first();
                            if ($sc) $peData['preferred_sub_caste_id'] = $sc->id;
                        }
                        if ($request->has('preferred_marital_status')) {
                            $val = $request->input('preferred_marital_status');
                            $ms = \App\Models\MaritalStatus::where('name', 'like', $val)->orWhere('name', 'like', '%' . $val . '%')->first();
                            if ($ms) $peData['preferred_marital_status_id'] = $ms->id;
                        }
                        if ($request->has('preferred_family_status')) {
                            $val = $request->input('preferred_family_status');
                            $fv = \App\Models\FamilyValue::where('value', 'like', $val)->orWhere('value', 'like', '%' . $val . '%')->first();
                            if ($fv) $peData['preferred_family_value_id'] = $fv->id;
                        }
                        $peData['user_id'] = $user->id;

                        // Update or create partner expectation
                        \App\Models\PartnerExpectation::updateOrCreate(
                            ['user_id' => $user->id],
                            $peData
                        );
                    }
                } catch (\Exception $e) {
                    Log::error('Error saving partner expectations', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString(), 'request' => $request->all()]);
                }
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
                'profile_id' => $service->profile_id ?? ($data['profile_id'] ?? null),
                'status' => $service->status,
                'photo_id' => $service->photo ?? null,
                'photo_url' => $service->photo ? $this->getPhotoUrl($service->photo) : null,
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
            $service = new Service(['id' => $serviceId, 'profile_id' => $serviceId, 'name' => 'Service ' . $serviceId]);
        }
        
        return view('profile.shortlist-ina', compact('service'));
    }

    // Shortlist from Others
    public function shortlistOthers($serviceId)
    {
        $service = Service::find($serviceId);
        
        if (!$service) {
            // Create a dummy service for the view if service doesn't exist
            $service = new Service(['id' => $serviceId, 'profile_id' => $serviceId, 'name' => 'Service ' . $serviceId]);
        }
        
        return view('profile.shortlist-others', compact('service'));
    }

    // View Prospects
    public function viewProspects($serviceId)
    {
        $service = Service::find($serviceId);
        
        if (!$service) {
            // Create a dummy service for the view if service doesn't exist
            $service = new Service(['id' => $serviceId, 'profile_id' => $serviceId, 'name' => 'Service ' . $serviceId]);
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
                'weight_range' => 'nullable|string',
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

            // Apply weight range filter (from dropdown)
            if ($request->filled('weight_range')) {
                $range = explode('-', $request->weight_range);
                if (count($range) === 2) {
                    $minWeight = (int) $range[0];
                    $maxWeight = (int) $range[1];
                    Log::info('Weight filter applied', [
                        'weight_min' => $minWeight,
                        'weight_max' => $maxWeight
                    ]);
                    $query->whereHas('user.physicalAttribute', function($q) use ($minWeight, $maxWeight) {
                        $q->where('weight', '>=', $minWeight)
                          ->where('weight', '<=', $maxWeight);
                    });
                }
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
                $religion = strtolower($request->caste);
                $muslimAliases = ['muslim', 'islam'];
                $query->whereHas('user.spiritualBackground.religion', function($q) use ($religion, $muslimAliases) {
                    if (in_array($religion, $muslimAliases)) {
                        $q->where(function($subQ) {
                            $subQ->where('name', 'like', '%muslim%')
                                 ->orWhere('name', 'like', '%islam%');
                        });
                    } else {
                        $q->where('name', 'like', '%' . $religion . '%')
                          ->orWhere('name', strtolower($religion))
                          ->orWhere('name', strtoupper($religion))
                          ->orWhere('name', ucfirst(strtolower($religion)));
                    }
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
                return url('laravel/public/' . $upload->file_name);
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

    // Return assigned profiles for current user as JSON
    public function assignedProfiles(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Only return rows that were created via the "Assign Profile from Other Sources" flow.
        // Those rows will have the other_site_member_id or profile_source populated and will have assigned_by set.
    $assignedByName = strtolower(trim($user->first_name ?? $user->name ?? ''));

        $list = Service::where('deleted', 0)
                ->whereRaw('LOWER(TRIM(assigned_by)) = ?', [$assignedByName])
                ->where(function($q) {
                    $q->whereNotNull('other_site_member_id')
                      ->orWhereNotNull('profile_source')
                      ->orWhereNotNull('assigned_at');
                })
                // order by assigned_at when present, otherwise fallback to created_at
                ->orderByRaw("COALESCE(assigned_at, created_at) DESC")
                ->get(['profile_id','other_site_member_id','member_name','profile_source','service_name','start_date','contact_numbers','remarks','assigned_at','id','photo']);

        // Map to include photo_url for the frontend
        $mapped = $list->map(function($svc) {
            return [
                'profile_id' => $svc->profile_id,
                'other_site_member_id' => $svc->other_site_member_id,
                'member_name' => $svc->member_name,
                'profile_source' => $svc->profile_source,
                'service_name' => $svc->service_name,
                'start_date' => $svc->start_date,
                'contact_numbers' => $svc->contact_numbers,
                'remarks' => $svc->remarks,
                'assigned_at' => $svc->assigned_at,
                'id' => $svc->id,
                'photo_url' => $svc->photo ? $this->getPhotoUrl($svc->photo) : null,
                'has_photo' => $svc->photo ? true : false,
            ];
        });

        return response()->json(['success' => true, 'data' => $mapped]);
    }

    // Generate a unique profile id for frontend usage
    public function generateProfileId(Request $request)
    {
        try {
            $pid = 'AUTO-' . time() . '-' . substr(bin2hex(random_bytes(4)), 0, 6);
            return response()->json(['success' => true, 'profile_id' => $pid]);
        } catch (\Exception $e) {
            Log::error('Error generating profile id', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to generate profile id'], 500);
        }
    }

    // Upload a photo and return upload metadata (id + url)
    public function uploadPhoto(Request $request)
{
    try {
        if (!$request->hasFile('photo')) {
            return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
        }
        $file = $request->file('photo');
        if (!$file->isValid()) {
            return response()->json(['success' => false, 'message' => 'Invalid uploaded file'], 400);
        }

        $destPath = public_path('uploads');
        if (!file_exists($destPath)) mkdir($destPath, 0755, true);
        $filename = time() . '_' . substr(bin2hex(random_bytes(4)), 0, 8) . '.' . $file->getClientOriginalExtension();

        // Move the uploaded file first
        $file->move($destPath, $filename);

        // Get size from the moved file on disk to avoid issues with missing tmp file
        $movedPath = $destPath . DIRECTORY_SEPARATOR . $filename;
        $size = null;
        if (file_exists($movedPath)) {
            $size = filesize($movedPath);
        }

        $upload = \App\Models\Upload::create([
            'file_original_name' => $file->getClientOriginalName(),
            'file_name' => 'uploads/' . $filename,
            'user_id' => Auth::id() ?? null,
            'file_size' => $size,
            'extension' => $file->getClientOriginalExtension(),
            'type' => $file->getClientMimeType(),
        ]);

        // Generate correct URL accounting for Laravel app in subdirectory
        $correctUrl = url('laravel/public/uploads/' . $filename);

        return response()->json(['success' => true, 'upload_id' => $upload->id, 'url' => $correctUrl]);

    } catch (\Exception $e) {
        Log::error('Error uploading photo', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return response()->json(['success' => false, 'message' => 'Upload failed'], 500);
    }
}
    // Add a prospect to shortlist
    public function addToShortlist(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);

            // Debug: log incoming payload for troubleshooting 'others' shortlists
            try {
                Log::info('addToShortlist incoming', ['user_id' => $user->id ?? null, 'payload' => $request->all()]);
            } catch (\Exception $e) {
                // ignore logging errors
            }

            $data = $request->only([
                'profile_id','prospect_id','source','prospect_name','prospect_age','prospect_education','prospect_occupation','prospect_location','prospect_religion','prospect_caste','prospect_marital_status','prospect_height','prospect_weight','prospect_contact','contact_date','remark'
            ]);

            // Basic validation
            if (empty($data['profile_id'])) return response()->json(['success' => false, 'message' => 'profile_id required'], 422);
            if (empty($data['prospect_id']) && empty($data['prospect_name'])) return response()->json(['success' => false, 'message' => 'prospect_id or prospect_name required'], 422);

            // Store the user id (integer) in shortlisted_by to be compatible with legacy schema
            $data['shortlisted_by'] = (int) ($user->id ?? 0);
            // Legacy schema requires user_id NOT NULL — set to current user id as well
            $data['user_id'] = (int) ($user->id ?? 0);

            // Ensure prospect_age is an integer when present
            if (isset($data['prospect_age'])) {
                $data['prospect_age'] = is_numeric($data['prospect_age']) ? (int) $data['prospect_age'] : null;
            }

            $short = \App\Models\Shortlist::create($data);

            // Debug: log the created shortlist row
            try {
                Log::info('addToShortlist created', ['shortlist_id' => $short->id ?? null, 'profile_id' => $short->profile_id ?? null, 'source' => $short->source ?? null, 'prospect_id' => $short->prospect_id ?? null]);
            } catch (\Exception $e) {
                // ignore logging errors
            }

            return response()->json(['success' => true, 'shortlist' => $short]);
        } catch (\Exception $e) {
            Log::error('Error in addToShortlist', ['error' => $e->getMessage(), 'request' => $request->all(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['success' => false, 'message' => 'Failed to add to shortlist', 'error' => $e->getMessage()], 500);
        }
    }

    // Return all shortlist items for a given profile
    public function getShortlistsForProfile($profileId)
    {
        try {
            $user = Auth::user();
            if (!$user) return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);

            // Sometimes this route is called with a numeric service DB id instead of the
            // real profile_id (legacy data uses textual profile_id values). Try to
            // resolve a Service record first and prefer its profile_id when present.
            $resolvedProfileId = $profileId;
            try {
                $service = Service::find($profileId);
                if ($service && !empty($service->profile_id)) {
                    $resolvedProfileId = $service->profile_id;
                }
            } catch (\Exception $e) {
                // ignore resolution errors and fall back to provided param
            }

            // Query using both resolvedProfileId and the original param to handle
            // cases where client/server normalization differs (e.g., AUTO-... ids).
            $candidates = array_filter(array_unique([$resolvedProfileId, $profileId]));
            if (count($candidates) === 1) {
                $items = \App\Models\Shortlist::where('profile_id', $candidates[0])
                            ->whereNull('deleted_at')
                            ->orderByDesc('created_at')
                            ->get();
            } else {
                $items = \App\Models\Shortlist::whereIn('profile_id', $candidates)
                            ->whereNull('deleted_at')
                            ->orderByDesc('created_at')
                            ->get();
            }

            // Temporary debug logging: record what was requested and what was found
            try {
                $sources = $items->pluck('source')->unique()->values()->all();
                Log::info('getShortlistsForProfile debug', [
                    'requested' => $profileId,
                    'resolved' => $resolvedProfileId,
                    'found_count' => $items->count(),
                    'sources' => $sources,
                    'ids_preview' => $items->pluck('id')->take(10)->values()->all()
                ]);
            } catch (\Exception $e) {
                // ignore logging failures
            }

            // Ensure each item has a usable prospect_name for display. For INA source
            // the shortlist often stores only prospect_id; try to resolve the name from
            // the users table (match by code or id) when prospect_name is empty.
            $mapped = $items->map(function($it) {
                $arr = $it->toArray();
                if (empty($arr['prospect_name']) && !empty($arr['prospect_id'])) {
                    try {
                        $u = \App\Models\User::where('code', $arr['prospect_id'])->orWhere('id', $arr['prospect_id'])->first();
                        if ($u) {
                            $arr['prospect_name'] = $u->name ?? ($u->first_name ?? null);
                        }
                    } catch (\Exception $e) {
                        // ignore lookup failures — we'll return whatever we have
                    }
                }
                return $arr;
            })->toArray();

            if (empty($mapped)) {
                Log::info('No shortlist items found for profile lookup', ['requested' => $profileId, 'resolved' => $resolvedProfileId, 'user_id' => $user->id]);
            }

            return response()->json(['success' => true, 'data' => $mapped]);
        } catch (\Exception $e) {
            Log::error('Error in getShortlistsForProfile: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to retrieve shortlists'], 500);
        }
    }
}
