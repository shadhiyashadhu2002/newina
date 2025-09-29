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
}
