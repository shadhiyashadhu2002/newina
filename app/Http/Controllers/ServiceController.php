<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\User;
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
            ]);
            
            $data = $request->all();
            $section = $data['section'] ?? 'full';
            
            // Handle progressive saving by section
            if ($section !== 'full') {
                return $this->saveSection($request, $section);
            }
            
            // Handle different field mappings from different forms
            $data['name'] = $data['service_name'] ?? $data['member_name'] ?? null;
            $data['service_executive'] = $data['service_executive'] ?? Auth::user()->first_name ?? 'admin';
            
            // Set default service_name if not provided (for admin modal)
            if (!isset($data['service_name'])) {
                $data['service_name'] = 'General Service';
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
        
        // Get all staff users for service executive dropdown (in case executives can also add services, exclude admins)
        $staffUsers = User::where('user_type', 'staff')
                          ->where('is_admin', '!=', 1)
                          ->select('first_name', 'id')
                          ->orderBy('first_name')
                          ->get();
        
        return view('profile.newservice', compact('services', 'staffUsers'));
    }

    // List all services (admin view)
    public function allServices()
    {
        $services = Service::orderByDesc('created_at')->get();
        
        // Get all staff users for service executive dropdown (exclude admins)
        $staffUsers = User::where('user_type', 'staff')
                          ->where('is_admin', '!=', 1)
                          ->select('first_name', 'id')
                          ->orderBy('first_name')
                          ->get();
        
        return view('profile.newservice', compact('services', 'staffUsers'));
    }

    // Handle progressive saving by section
    private function saveSection(Request $request, $section)
    {
        try {
            $data = $request->all();
            $profileId = $data['profile_id'];
            
            // Prepare data for the specific section
            $updateData = [];
            
            switch ($section) {
                case 'service':
                    $updateData = [
                        'service_name' => $data['service_name'] ?? null,
                        'amount_paid' => $data['amount_paid'] ?? null,
                        'success_fee' => $data['success_fee'] ?? null,
                        'start_date' => $data['start_date'] ?? null,
                        'expiry_date' => $data['expiry_date'] ?? null,
                        'service_details' => $data['service_details'] ?? null,
                        'service_executive' => Auth::user()->first_name ?? 'admin',
                    ];
                    break;
                    
                case 'member':
                    $updateData = [
                        'member_name' => $data['member_name'] ?? null,
                        'member_age' => $data['member_age'] ?? null,
                        'member_education' => $data['member_education'] ?? null,
                        'member_occupation' => $data['member_occupation'] ?? null,
                        'member_income' => $data['member_income'] ?? null,
                        'member_marital_status' => $data['member_marital_status'] ?? null,
                        'member_family_status' => $data['member_family_status'] ?? null,
                        'member_father_details' => $data['member_father_details'] ?? null,
                        'member_mother_details' => $data['member_mother_details'] ?? null,
                        'member_sibling_details' => $data['member_sibling_details'] ?? null,
                        'member_caste' => $data['member_caste'] ?? null,
                        'member_subcaste' => $data['member_subcaste'] ?? null,
                    ];
                    break;
                    
                case 'partner':
                    $updateData = [
                        'preferred_age' => $data['preferred_age'] ?? null,
                        'preferred_weight' => $data['preferred_weight'] ?? null,
                        'preferred_education' => $data['preferred_education'] ?? null,
                        'preferred_religion' => $data['preferred_religion'] ?? null,
                        'preferred_caste' => $data['preferred_caste'] ?? null,
                        'preferred_subcaste' => $data['preferred_subcaste'] ?? null,
                        'preferred_marital_status' => $data['preferred_marital_status'] ?? null,
                        'preferred_annual_income' => $data['preferred_annual_income'] ?? null,
                        'preferred_occupation' => $data['preferred_occupation'] ?? null,
                        'preferred_family_status' => $data['preferred_family_status'] ?? null,
                        'preferred_eating_habits' => $data['preferred_eating_habits'] ?? null,
                    ];
                    break;
                    
                case 'contact':
                    $updateData = [
                        'contact_customer_name' => $data['contact_customer_name'] ?? null,
                        'contact_mobile_no' => $data['contact_mobile_no'] ?? null,
                        'contact_whatsapp_no' => $data['contact_whatsapp_no'] ?? null,
                        'contact_email' => $data['contact_email'] ?? null,
                        'contact_alternate' => $data['contact_alternate'] ?? null,
                        'contact_client' => $data['contact_client'] ?? null,
                    ];
                    break;
                    
                default:
                    return response()->json(['success' => false, 'message' => 'Invalid section'], 400);
            }
            
            // Remove null values to avoid overwriting existing data
            $updateData = array_filter($updateData, function($value) {
                return $value !== null && $value !== '';
            });
            
            // Update or create the service record
            $service = Service::updateOrCreate(
                ['profile_id' => $profileId],
                $updateData
            );
            
            Log::info("Section '{$section}' saved successfully", [
                'profile_id' => $profileId,
                'service_id' => $service->id,
                'updated_fields' => array_keys($updateData)
            ]);
            
            return response()->json([
                'success' => true, 
                'message' => "Section '{$section}' saved successfully!",
                'service_id' => $service->id
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error saving section '{$section}'", [
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
