<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\User;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    // Show the add sale form
    public function create()
    {
        // Get all unique service executives from the users table where user_type is 'staff'
        $serviceExecutives = User::where('user_type', 'staff')
            ->whereNotNull('first_name')
            ->where('first_name', '!=', '')
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'name']);
        
        return view('addsale', compact('serviceExecutives'));
    }

    // Get profile information for auto-fetch
    public function getProfileInfo($profileId)
    {
        try {
            // Try to find by code first, then by ID
            $user = User::where('code', $profileId)
                       ->orWhere('id', $profileId)
                       ->first();
            
            if ($user) {
                return response()->json([
                    'success' => true,
                    'name' => $user->name ?? $user->first_name,
                    'first_name' => $user->first_name,
                    'service_executive' => $user->service_executive ?? Auth::user()->name,
                    'executive' => $user->executive ?? Auth::user()->name
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Profile not found'
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching profile: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Store new sale
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'profile_id' => 'required',
            'name' => 'required|string',
            'executive' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'success_fee' => 'nullable|numeric|min:0',
            'plan' => 'required|string',
            'status' => 'required|string',
            'office' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        try {
            // Find staff user by executive name
            $staffUser = User::where('user_type', 'staff')
                ->where('first_name', $validated['executive'])
                ->first();
            if (!$staffUser) {
                return back()->withErrors(['error' => 'Selected executive not found in staff users.'])->withInput();
            }

            $sale = Sale::create([
                'date' => $validated['date'],
                'profile_id' => $validated['profile_id'],
                'name' => $validated['name'],
                'executive' => $validated['executive'],
                'amount' => $validated['amount'],
                'success_fee' => $validated['success_fee'] ?? 0,
                'plan' => $validated['plan'],
                'status' => $validated['status'],
                'office' => $validated['office'],
                'notes' => $validated['notes'],
                'created_by' => Auth::id(),
                'staff_id' => $staffUser->id
            ]);

            // If plan is 'Service', add to session array for badge and prefill (support multiple)
            if (strtolower($validated['plan']) === 'service') {
                $pending = session('show_service_badge', []);
                // Only add if not already present
                $exists = false;
                foreach ($pending as $item) {
                    if ($item['profile_id'] == $validated['profile_id']) {
                        $exists = true;
                        break;
                    }
                }
                if (!$exists) {
                    $pending[] = [
                        'profile_id' => $validated['profile_id'],
                        'member_name' => $validated['name']
                    ];
                }
                session(['show_service_badge' => $pending]);
            }
            // Always return to addsale page after sale
            return redirect()->route('addsale.page')
                           ->with('success', 'Sale added successfully!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error saving sale: ' . $e->getMessage()])
                        ->withInput();
        }
    }
}