<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    // Store a new service (admin only)
    public function store(Request $request)
    {
        try {
            // Log the request for debugging
            Log::info('ServiceController store called', [
                'method' => $request->method(),
                'ajax' => $request->ajax(),
                'expectsJson' => $request->expectsJson(),
                'headers' => $request->headers->all(),
                'data' => $request->all()
            ]);
            
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
            
            Log::info('Service created/updated successfully', ['service_id' => $service->id]);
            
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

    // List services for the logged-in executive
    public function executiveServices()
    {
        $user = Auth::user();
        $services = Service::where('service_executive', $user->first_name)->orderByDesc('created_at')->get();
        return view('profile.newservice', compact('services'));
    }

    // List all services (admin view)
    public function allServices()
    {
        $services = Service::orderByDesc('created_at')->get();
        return view('profile.newservice', compact('services'));
    }

    // Service Dashboard with dynamic counts
    public function servicesDashboard()
    {
        $user = Auth::user();
        
        if ($user && $user->is_admin) {
            // Admin sees all services across all staff
            $totalServices = Service::count();
            $newServices = Service::where('status', 'new')->count();
            $activeServices = Service::where('status', 'active')->count();
            $completedServices = Service::where('status', 'completed')->count();
            
            // Get recent services for all staff (last 15 for admin)
            $recentServices = Service::orderBy('created_at', 'desc')
                                    ->limit(15)
                                    ->get();
        } else {
            // Staff sees only their assigned services
            $totalServices = Service::where('service_executive', $user->first_name)->count();
            $newServices = Service::where('service_executive', $user->first_name)
                                 ->where('status', 'new')
                                 ->count();
            $activeServices = Service::where('service_executive', $user->first_name)
                                    ->where('status', 'active')
                                    ->count();
            $completedServices = Service::where('service_executive', $user->first_name)
                                       ->where('status', 'completed')
                                       ->count();

            // Get recent services for current service executive (last 10)
            $recentServices = Service::where('service_executive', $user->first_name)
                                    ->orderBy('created_at', 'desc')
                                    ->limit(10)
                                    ->get();
        }

        return view('profile.services', compact('totalServices', 'newServices', 'activeServices', 'completedServices', 'recentServices'));
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
}
