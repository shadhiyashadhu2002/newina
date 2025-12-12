<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StaffTarget;
use App\Models\User;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StaffTargetController extends Controller
{
    public function index(Request $request)
    {
        $staffUsers = User::where('is_admin', 0)->get();
        $targetsQuery = StaffTarget::with('staff');

        // Apply filters
        if ($request->filled('staff_name')) {
            $targetsQuery->whereHas('staff', function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->staff_name . '%');
            });
        }
        if ($request->filled('month')) {
            $targetsQuery->where('month', 'like', $request->month . '%');
        }
        if ($request->filled('department')) {
            $targetsQuery->where('department', $request->department);
        }

        $staffTargets = $targetsQuery->get();

        $prepared = $staffTargets->map(function ($t) {
            $monthDate = Carbon::parse($t->month);
            $year = $monthDate->year;
            $month = $monthDate->month;

            $staffName = $t->staff->first_name ?? 'N/A';

            // Sum paid_amount for this staff member in this month
            // Check both staff_id AND executive name (case-insensitive)
            $achieved = Sale::where(function($query) use ($t, $staffName) {
                    $query->where('staff_id', $t->staff_id)
                          ->orWhere(DB::raw('UPPER(executive)'), strtoupper($staffName));
                })
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('paid_amount');

            $balance = $t->target_amount - $achieved;
            $percentage = $t->target_amount > 0 ? round(($achieved / $t->target_amount) * 100, 2) : 0;
            $status = $achieved >= $t->target_amount ? 'achieved' : 'in-progress';

            return (object)[
                'id' => $t->id,
                'staff_id' => $t->staff_id,
                'staff_name' => $staffName,
                'staff_first_name' => $staffName,
                'month' => $t->month,
                'department' => $t->department,
                'branch' => $t->branch,
                'target_amount' => $t->target_amount,
                'achieved' => $achieved,
                'balance' => $balance,
                'percentage' => $percentage,
                'status' => $status,
            ];
        });

        // Sort by target_amount descending (highest first)
        $prepared = $prepared->sortByDesc('target_amount')->values();

        $totalStaff = $staffUsers->count();
        $targetsAchieved = $prepared->where('status', 'achieved')->count();
        $inProgress = $prepared->where('status', 'in-progress')->count();
        $zeroSale = $prepared->where('achieved', 0)->count();
        $overallAchievement = $prepared->count() > 0 ? round($prepared->avg('percentage'), 2) : 0;

        return view('stafftarget', compact('staffUsers', 'prepared', 'totalStaff', 'targetsAchieved', 'inProgress', 'overallAchievement', 'zeroSale'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_executive' => 'required|exists:users,id',
            'month' => 'required|date_format:Y-m',
            'department' => 'required|string',
            'branch' => 'required|string',
            'target_amount' => 'required|numeric|min:0',
        ]);

        $staffId = $validated['service_executive'];
        $month = $validated['month'] . '-01';

        $existing = StaffTarget::where('staff_id', $staffId)
            ->where('month', 'like', $validated['month'] . '%')
            ->first();

        if ($existing) {
            return redirect()->route('stafftarget.page')
                ->with('error', 'Target already exists for this staff member in this month.');
        }

        StaffTarget::create([
            'staff_id' => $staffId,
            'month' => $month,
            'branch' => $validated['branch'],
            'department' => $validated['department'],
            'target_amount' => $validated['target_amount'],
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('stafftarget.page')->with('success', 'Target assigned successfully.');
    }

    public function edit($id)
    {
        $target = StaffTarget::with('staff')->findOrFail($id);

        $monthDate = Carbon::parse($target->month);
        $year = $monthDate->year;
        $month = $monthDate->month;

        $staffName = $target->staff->first_name ?? 'N/A';

        $achieved = Sale::where(function($query) use ($target, $staffName) {
                $query->where('staff_id', $target->staff_id)
                      ->orWhere(DB::raw('UPPER(executive)'), strtoupper($staffName));
            })
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('paid_amount');

        $balance = $target->target_amount - $achieved;
        $percentage = $target->target_amount > 0 ? round(($achieved / $target->target_amount) * 100, 2) : 0;

        return response()->json([
            'id' => $target->id,
            'staff_id' => $target->staff_id,
            'staff_name' => $staffName,
            'month' => $monthDate->format('Y-m'),
            'department' => $target->department,
            'branch' => $target->branch,
            'target_amount' => $target->target_amount,
            'achieved' => $achieved,
            'balance' => $balance,
            'percentage' => $percentage,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:users,id',
            'month' => 'required|date_format:Y-m',
            'department' => 'nullable|string',
            'target_amount' => 'required|numeric|min:0',
        ]);

        $target = StaffTarget::findOrFail($id);
        $month = $validated['month'] . '-01';

        $existing = StaffTarget::where('staff_id', $validated['staff_id'])
            ->where('month', 'like', $validated['month'] . '%')
            ->where('id', '!=', $id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Target already exists for this staff member in this month.'
            ], 422);
        }

        $target->update([
            'staff_id' => $validated['staff_id'],
            'month' => $month,
            'department' => $validated['department'],
            'target_amount' => $validated['target_amount'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Target updated successfully.'
        ]);
    }

    public function view($id)
    {
        $target = StaffTarget::with('staff')->findOrFail($id);

        $monthDate = Carbon::parse($target->month);
        $year = $monthDate->year;
        $month = $monthDate->month;

        $staffName = $target->staff->first_name ?? 'N/A';

        $achieved = Sale::where(function($query) use ($target, $staffName) {
                $query->where('staff_id', $target->staff_id)
                      ->orWhere(DB::raw('UPPER(executive)'), strtoupper($staffName));
            })
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('paid_amount');

        $sales = Sale::where(function($query) use ($target, $staffName) {
                $query->where('staff_id', $target->staff_id)
                      ->orWhere(DB::raw('UPPER(executive)'), strtoupper($staffName));
            })
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('created_at', 'desc')
            ->get();

        $balance = $target->target_amount - $achieved;
        $percentage = $target->target_amount > 0 ? round(($achieved / $target->target_amount) * 100, 2) : 0;
        $status = $achieved >= $target->target_amount ? 'Achieved' : 'In Progress';

        return response()->json([
            'id' => $target->id,
            'staff_name' => $staffName,
            'staff_email' => $target->staff->email ?? 'N/A',
            'month' => $monthDate->format('F Y'),
            'department' => $target->department ?? 'N/A',
            'branch' => $target->branch ?? 'N/A',
            'target_amount' => $target->target_amount,
            'achieved' => $achieved,
            'balance' => $balance,
            'percentage' => $percentage,
            'status' => $status,
            'total_sales' => $sales->count(),
            'sales' => $sales->map(function($sale) {
                return [
                    'id' => $sale->id,
                    'amount' => $sale->paid_amount,
                    'customer_name' => $sale->name ?? 'N/A',
                    'date' => Carbon::parse($sale->created_at)->format('d M Y'),
                ];
            }),
            'created_at' => Carbon::parse($target->created_at)->format('d M Y, h:i A'),
        ]);
    }
}
