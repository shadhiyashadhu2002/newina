<?php
namespace App\Http\Controllers;
use App\Imports\SalesImport;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\User;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel as ExcelFacade;

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

        $saleStatuses = [
            'PAID' => 'PAID',
            'PENDING' => 'PENDING',
            'NOT RECIEVED' => 'NOT RECIEVED',
            'REFUND' => 'REFUND',
            'PARTIALLY PAID' => 'PARTIALLY PAID',
            'FULL PAID' => 'FULL PAID',
            'RENEW' => 'RENEW',
            'ADVANCE PAYMENT' => 'ADVANCE PAYMENT'
        ];

        $cashTypeOptions = [
            'GPAY CANARA' => 'GPAY CANARA',
            'ACCOUNT TRANSFER' => 'ACCOUNT TRANSFER',
            'OFFICE PAYMENT' => 'OFFICE PAYMENT',
            'GPAY B' => 'GPAY B',
            'GPAY P' => 'GPAY P',
            'GPAY R' => 'GPAY R',
            'GPAY A' => 'GPAY A',
            'GPAY AXIS' => 'GPAY AXIS',
            'GPAY S' => 'GPAY S'
        ];

        $cashTypes = array_keys($cashTypeOptions);

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

        if ($request->filled('cash_type')) {
            $salesQuery->where('cash_type', $request->cash_type);
        }

        if ($request->filled('plan')) {
            $salesQuery->where('plan', $request->plan);
        }

        if ($request->filled('status_filter')) {
            $salesQuery->where('sale_status', $request->status_filter);
        }

        if ($request->filled('staff')) {
            $salesQuery->where('executive', $request->staff);
        }

        if ($request->filled('office')) {
            $salesQuery->where('office', $request->office);
        }

        if ($request->filled('cash_type_filter')) {
            $salesQuery->where('cash_type', $request->cash_type_filter);
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
            'saleStatuses',
            'cashTypeOptions',
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
                'status' => 'nullable|string|in:new,active,completed,pending,cancelled',
                'sale_status' => 'nullable|string|in:PAID,PENDING,NOT RECIEVED,REFUND,PARTIALLY PAID,FULL PAID,RENEW,ADVANCE PAYMENT',
                'office' => 'required|string|max:255',
                'cash_type' => 'nullable|string|in:GPAY CANARA,ACCOUNT TRANSFER,OFFICE PAYMENT,GPAY B,GPAY P,GPAY R,GPAY A,GPAY AXIS,GPAY S',
                'notes' => 'nullable|string|max:1000',
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
                'status' => $validatedData['status'] ?? 'new',
                'sale_status' => $validatedData['sale_status'] ?? null,
                'office' => $validatedData['office'],
                'cash_type' => $validatedData['cash_type'] ?? null,
                'notes' => $validatedData['notes'] ?? null,
                'staff_id' => $staffUser->id,
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

    // Get sale data for editing (AJAX)
    public function show($id)
    {
        try {
            $sale = Sale::findOrFail($id);
            
            return response()->json([
                'id' => $sale->id,
                'date' => $sale->date->format('Y-m-d'),
                'profile_id' => $sale->profile_id,
                'phone' => $sale->phone,
                'name' => $sale->name,
                'plan' => $sale->plan,
                'amount' => $sale->amount,
                'paid_amount' => $sale->paid_amount,
                'discount' => $sale->discount,
                'success_fee' => $sale->success_fee,
                'executive' => $sale->executive,
                'status' => $sale->status,
                'office' => $sale->office,
                'sale_status' => $sale->sale_status,
                'cash_type' => $sale->cash_type,
                'notes' => $sale->notes
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching sale', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Sale not found'], 404);
        }
    }

    // Update sale (AJAX)
    public function update(Request $request, $id)
    {
        try {
            $sale = Sale::findOrFail($id);

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
                'status' => 'nullable|string|in:new,active,completed,pending,cancelled',
                'sale_status' => 'nullable|string|in:PAID,PENDING,NOT RECIEVED,REFUND,PARTIALLY PAID,FULL PAID,RENEW,ADVANCE PAYMENT',
                'office' => 'required|string|max:255',
                'cash_type' => 'nullable|string|in:GPAY CANARA,ACCOUNT TRANSFER,OFFICE PAYMENT,GPAY B,GPAY P,GPAY R,GPAY A,GPAY AXIS,GPAY S',
                'notes' => 'nullable|string|max:1000',
            ]);

            // Find staff user by executive name
            $staffUser = User::where('user_type', 'staff')
                ->where('first_name', $validatedData['executive'])
                ->first();
            
            if (!$staffUser) {
                return response()->json(['error' => 'Selected executive not found'], 422);
            }

            // Compute success_fee server-side
            $amount = floatval($validatedData['amount'] ?? 0);
            $paid = floatval($validatedData['paid_amount'] ?? 0);
            $discount = floatval($validatedData['discount'] ?? 0);
            $success_fee = max(0, $amount - $paid - $discount);

            $sale->update([
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
                'status' => $validatedData['status'] ?? 'new',
                'sale_status' => $validatedData['sale_status'] ?? null,
                'office' => $validatedData['office'],
                'cash_type' => $validatedData['cash_type'] ?? null,
                'notes' => $validatedData['notes'] ?? null,
                'staff_id' => $staffUser->id,
            ]);

            Log::info('Sale updated successfully', ['sale_id' => $sale->id]);

            return response()->json(['message' => 'Sale updated successfully', 'sale' => $sale]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error on update', ['errors' => $e->errors()]);
            return response()->json(['error' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error updating sale', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Error updating sale: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Import sales from Excel file
     */
    public function import(Request $request)
    {
        try {
            // Validate the file
            $request->validate([
                'excel_file' => 'required|file|mimes:xlsx,xls|max:10240', // Max 10MB
            ]);

            // Import the Excel file
            $import = new \App\Imports\SalesImport();
            ExcelFacade::import($import, $request->file('excel_file'));

            // Count imported records (approximate - we'd need to track this properly)
            $importedCount = \App\Models\Sale::where('created_by', Auth::id())
                ->where('created_at', '>=', now()->subMinutes(1))
                ->count();

            return response()->json([
                'success' => true,
                'message' => 'Sales imported successfully',
                'imported' => $importedCount
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Excel import error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error importing sales: ' . $e->getMessage()
            ], 500);
        }
    }
    public function importSales(Request $request)
    {
        try {
            $request->validate([
                'excel_file' => 'required|mimes:xlsx,xls'
            ]);
            
            $file = $request->file('excel_file');
            $import = new \App\Imports\SalesImport();
            
            // Actually import the file
            ExcelFacade::import($import, $file);
            return response()->json([
                'success' => true,
                'message' => 'Sales imported successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Import error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 422);
        }
    }

    public function downloadTemplate()
    {
        $headers = [
            ['Date', 'ID', 'Customer Name', 'Phone', 'Paid Amount', 'Cash Type', 'Plan', 'Sale Status', 'Service Executive', 'Office'],
            ['1-Dec-2025', 'IM205122545', 'HASHIR', '9876543210', '6000', 'OFFICE PAYMENT', 'SERVICE', 'PAID', 'RAMEESHA', 'TIRUR'],
            ['2-Dec-2025', 'IM205122546', 'JOHN DOE', '9876543211', '8000', 'GPAY CANARA', 'Basic', 'PENDING', 'STAFF NAME', 'VATAKARA']
        ];

        $filename = 'sales_import_template_' . date('Y-m-d') . '.xlsx';

        return ExcelFacade::download(
            new class($headers) implements \Maatwebsite\Excel\Concerns\FromArray {
                private $data;
                public function __construct($data) { $this->data = $data; }
                public function array(): array { return $this->data; }
            },
            $filename
        );
    }
}
