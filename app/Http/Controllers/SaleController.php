<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\User;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

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
        
        // Build base query depending on user role (staff see only their records)
        if (Auth::check() && Auth::user()->user_type === 'staff') {
            $staffId = Auth::id();
            $query = Sale::where('staff_id', $staffId);
        } else {
            $query = Sale::query();
        }

        // Available filter option lists (used by the view)
        $plans = ['Elite','Assisted','Premium','Basic','Standard','Service'];
    // Offices updated per request
    $offices = ['Tirur','Vadakara'];
        // Statuses updated per UI request
        // We'll store display labels but filter/compare using a normalized key
        $statuses = [
            'full_paid' => 'Full Paid',
            'pending' => 'Pending',
            'not_received' => 'Not Received',
            'refund' => 'Refund',
            'partially_paid' => 'Partially Paid'
        ];
        $cashTypes = ['all','paid','partial','unpaid'];

        // Apply filters from query string (month in YYYY-MM format, cash_type, plan, status, staff, office)
        if ($month = $request->query('month')) {
            if (preg_match('/^(\d{4})-(\d{2})$/', $month, $m)) {
                $year = $m[1];
                $mon = $m[2];
                $query->whereYear('date', $year)->whereMonth('date', $mon);
            }
        }

        if ($plan = $request->query('plan')) {
            if ($plan !== '') $query->where('plan', $plan);
        }

        if ($status = $request->query('status')) {
            if ($status !== '') $query->where('status', $status);
        }

        if ($staff = $request->query('staff')) {
            if ($staff !== '') $query->where('staff_id', $staff);
        }

        if ($office = $request->query('office')) {
            if ($office !== '') $query->where('office', $office);
        }

        if ($cashType = $request->query('cash_type')) {
            switch ($cashType) {
                case 'paid':
                    // Fully paid (paid_amount == amount)
                    $query->whereColumn('paid_amount', 'amount');
                    break;
                case 'unpaid':
                    $query->where('paid_amount', 0);
                    break;
                case 'partial':
                    $query->where('paid_amount', '>', 0)->whereColumn('paid_amount', '<', 'amount');
                    break;
                default:
                    // 'all' or unknown -> no filter
                    break;
            }
        }

        // Paginate filtered results and keep query string so filters persist in links
        $sales = $query->orderBy('date', 'desc')->orderBy('id', 'desc')->paginate(20)->withQueryString();

        // Compute aggregates based on the same filtered scope (so cards reflect filters)
        $totalSales = (clone $query)->count();
        $totalSalesAmount = (clone $query)->sum('paid_amount');
        $serviceSales = (clone $query)->where('plan', 'Service')->count();
        $normalSales = $totalSales - $serviceSales;

    // Pass the filter option lists and current request parameters to the view
    return view('addsale', compact('serviceExecutives', 'sales', 'totalSales', 'serviceSales', 'normalSales', 'totalSalesAmount', 'plans', 'offices', 'statuses', 'cashTypes'));
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
            'profile_id' => 'nullable',
            'phone' => 'nullable|string',
            'name' => 'required|string',
            'executive' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'success_fee' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
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

            // Compute paid, discount and success fee per rule:
            // success_fee = amount - paid_amount - discount
            // if discount not provided, it's treated as 0 (so success_fee = amount - paid_amount)
            $amountVal = isset($validated['amount']) ? floatval($validated['amount']) : 0.0;
            $paid = isset($validated['paid_amount']) ? floatval($validated['paid_amount']) : 0.0;
            $discount = isset($validated['discount']) ? floatval($validated['discount']) : 0.0;
            $computedSuccessFee = max(0, $amountVal - $paid - $discount);

            // Build payload and only include columns that actually exist to avoid SQL errors if migration not run
            $payload = [
                'date' => $validated['date'],
                // DB requires profile_id non-nullable; if not provided, store empty string
                'profile_id' => $validated['profile_id'] ?? '',
                'name' => $validated['name'],
                'executive' => $validated['executive'],
                'amount' => $validated['amount'],
                'paid_amount' => $paid,
                'plan' => $validated['plan'],
                'status' => $validated['status'],
                'office' => $validated['office'],
                'notes' => $validated['notes'],
                'created_by' => Auth::id(),
                'staff_id' => $staffUser->id
            ];

            // Add success_fee if column exists
            if (Schema::hasColumn('sales', 'success_fee')) {
                $payload['success_fee'] = $computedSuccessFee;
            }

            // Add discount if column exists
            if (Schema::hasColumn('sales', 'discount')) {
                $payload['discount'] = $discount;
            }

            // Add phone if column exists
            if (Schema::hasColumn('sales', 'phone')) {
                $payload['phone'] = $validated['phone'] ?? null;
            }

            $sale = Sale::create($payload);

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