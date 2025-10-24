<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\User;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    // Show the add sale form
    public function create(Request $request)
    {
        // Get all unique service executives from the users table where user_type is 'staff'
        $serviceExecutives = User::where('user_type', 'staff')
            ->whereNotNull('first_name')
            ->where('first_name', '!=', '')
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'name']);
        // Use the project's canonical statuses and cash types
        $statuses = [
            'new' => 'New',
            'active' => 'Active',
            'completed' => 'Completed',
            'pending' => 'Pending',
            'cancelled' => 'Cancelled'
        ];

        $cashTypes = ['all', 'paid', 'partial', 'unpaid'];

        $plans = ['Elite','Assisted','Premium','Basic','Standard','Service'];
        $offices = ['Tirur','Vadakara'];

        // Build the query for filtering sales
        $salesQuery = Sale::query();

        // If the logged-in user is a staff member, restrict to their sales
        if (Auth::check() && Auth::user()->user_type === 'staff') {
            $staffId = Auth::id();
            $salesQuery->where('staff_id', $staffId);
        }

        // Apply filters based on request parameters
        if ($request->filled('month')) {
            $salesQuery->whereYear('date', substr($request->month, 0, 4))
                      ->whereMonth('date', substr($request->month, 5, 2));
        }

        if ($request->filled('cash_type') && $request->cash_type !== 'all') {
            // For cash_type filter, we'll map the filter values to database logic
            switch ($request->cash_type) {
                case 'paid':
                    $salesQuery->whereColumn('paid_amount', '>=', 'amount');
                    break;
                case 'partial':
                    $salesQuery->where('paid_amount', '>', 0)
                              ->whereColumn('paid_amount', '<', 'amount');
                    break;
                case 'unpaid':
                    $salesQuery->where('paid_amount', '<=', 0);
                    break;
            }
        }

        if ($request->filled('plan')) {
            $salesQuery->where('plan', $request->plan);
        }

        if ($request->filled('status')) {
            $salesQuery->where('status', $request->status);
        }

        if ($request->filled('staff')) {
            $salesQuery->where('staff_id', $request->staff);
        }

        if ($request->filled('office')) {
            $salesQuery->where('office', $request->office);
        }

        // Get filtered sales with pagination
        $sales = $salesQuery->orderBy('date', 'desc')
                           ->orderBy('id', 'desc')
                           ->paginate(20)
                           ->appends($request->query());

        // Calculate aggregates based on the current user's permissions
        if (Auth::check() && Auth::user()->user_type === 'staff') {
            $staffId = Auth::id();
            $totalSales = Sale::where('staff_id', $staffId)->count();
            $totalSalesAmount = Sale::where('staff_id', $staffId)->sum('paid_amount');
            $serviceSales = Sale::where('staff_id', $staffId)->where('plan', 'Service')->count();
            $normalSales = $totalSales - $serviceSales;
        } else {
            // Aggregate counts for dashboard cards (global)
            $totalSales = Sale::count();
            $totalSalesAmount = Sale::sum('paid_amount');
            $serviceSales = Sale::where('plan', 'Service')->count();
            $normalSales = $totalSales - $serviceSales;
        }

        return view('addsale', compact(
            'serviceExecutives',
            'sales',
            'totalSales',
            'serviceSales',
            'normalSales',
            'totalSalesAmount',
            'statuses',
            'cashTypes',
            'plans',
            'offices'
        ));
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
        Log::info('SaleController@store called', ['method' => $request->method(), 'inputs' => array_keys($request->all())]);
        
        try {
            // Single unified validation that matches the add sale form
            $validatedData = $request->validate([
                'date' => 'required|date',
                'profile_id' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'name' => 'required|string|max:255',
                'executive' => 'required|string|max:255',
                'plan' => 'required|string|in:Elite,Assisted,Premium,Basic,Standard,Service',
                'amount' => 'required|numeric|min:0',
                'paid_amount' => 'nullable|numeric|min:0',
                'discount' => 'nullable|numeric|min:0',
                'success_fee' => 'nullable|numeric|min:0',
                'status' => 'required|string|in:new,active,completed,pending,cancelled',
                'office' => 'required|string|max:255',
                'notes' => 'nullable|string|max:1000',
                'cash_type' => 'nullable|string',
            ]);

            // Find staff user by executive name
            $staffUser = User::where('user_type', 'staff')
                ->where('first_name', $validatedData['executive'])
                ->first();
            
            if (!$staffUser) {
                Log::error('Staff user not found', ['executive' => $validatedData['executive']]);
                return back()
                    ->withErrors(['executive' => 'Selected executive not found in staff users.'])
                    ->withInput();
            }

            // Compute success_fee server-side to ensure correctness
            $amount = floatval($validatedData['amount'] ?? 0);
            $paid = floatval($validatedData['paid_amount'] ?? 0);
            $discount = floatval($validatedData['discount'] ?? 0);
            $success_fee = max(0, $amount - $paid - $discount);

            $sale = Sale::create([
                'date' => $validatedData['date'],
                'profile_id' => $validatedData['profile_id'] ?? null,
                'phone' => $validatedData['phone'] ?? null,
                'name' => $validatedData['name'],
                'executive' => $validatedData['executive'],
                'plan' => $validatedData['plan'],
                'amount' => $amount,
                'paid_amount' => $paid,
                'discount' => $discount,
                'success_fee' => $success_fee,
                'status' => $validatedData['status'],
                'office' => $validatedData['office'],
                'notes' => $validatedData['notes'] ?? null,
                'staff_id' => $staffUser->id,
                'cash_type' => $validatedData['cash_type'] ?? null,
                'created_by' => Auth::id(),
            ]);

            Log::info('Sale created successfully', ['sale_id' => $sale->id]);

            return redirect()->back()->with('success', 'Sale added successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error', ['errors' => $e->errors()]);
            return back()
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Error saving sale', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withErrors(['error' => 'Error saving sale: ' . $e->getMessage()])
                ->withInput();
        }
    }
}