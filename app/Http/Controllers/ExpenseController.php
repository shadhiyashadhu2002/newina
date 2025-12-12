<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Check if user is a limited admin (Benazir or Prabhakaran)
        $isLimitedAdmin = $user->is_admin && $user->admin_level === 'limited';
        
        // Get manager name based on user
        $currentManager = null;
        if ($isLimitedAdmin) {
            // Map user to their manager name
            if ($user->first_name === 'Benazir') {
                $currentManager = 'BENAZIR';
            } elseif ($user->first_name === 'Prabhakaran') {
                $currentManager = 'PRABHAKARAN';
            }
        }

        // Build query with filters - INITIALIZE QUERY FIRST
        $query = Expense::with('createdBy');

        // If limited admin, only show their own expenses
        if ($isLimitedAdmin && $currentManager) {
            $query->where('manager', $currentManager);
        }

        // Apply filters only for full admins (Shadhiya, Afnaz)
        if (!$isLimitedAdmin) {
            // Date range filter
            if ($request->filled('date_from')) {
                $query->whereDate('date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('date', '<=', $request->date_to);
            }

            // Manager filter
            if ($request->filled('manager')) {
                $query->where('manager', $request->manager);
            }

            // Notes filter
            if ($request->filled('notes')) {
                $query->where('notes', $request->notes);
            }

            // Search filter
            if ($request->filled('search')) {
                $query->where('description', 'like', '%' . $request->search . '%');
            }
        }

        // Get expenses with pagination
        $expenses = $query->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->appends($request->all());

        // Calculate expense summaries based on filtered results
        $summaryQuery = Expense::query();
        
        // Apply same manager filter for summaries
        if ($isLimitedAdmin && $currentManager) {
            $summaryQuery->where('manager', $currentManager);
        }
        
        $todayExpense = (clone $summaryQuery)->today()->sum('amount');
        $weekExpense = (clone $summaryQuery)->thisWeek()->sum('amount');
        $monthExpense = (clone $summaryQuery)->thisMonth()->sum('amount');
        $totalExpense = (clone $summaryQuery)->sum('amount');

        return view('expense', compact(
            'todayExpense',
            'weekExpense',
            'monthExpense',
            'totalExpense',
            'expenses',
            'isLimitedAdmin'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'notes' => ['nullable', 'in:SALARY,RECHARGE,MOBILE/PC,REPAIR,RENT,ELECTRICITY,WATER,FESTIVAL,TRAVEL,DATA,TEA,EMI,STATIONARY,INCENTIVE,CLEANING,PRINT,REFUND,MARKETING,DIGITAL MARKETING,WI-FI,OTHERS'],
            'amount' => 'required|numeric|min:0',
            'manager' => 'nullable|in:BENAZIR,AFNAS,PRABHAKARAN,RAFEEQUE,OTHERS'
        ]);

        $validated['created_by'] = Auth::id();

        $expense = Expense::create($validated);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Expense added successfully', 'expense' => $expense], 201);
        }

        return redirect()->route('expense.page')->with('success', 'Expense added successfully!');
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'notes' => ['nullable', 'in:SALARY,RECHARGE,MOBILE/PC,REPAIR,RENT,ELECTRICITY,WATER,FESTIVAL,TRAVEL,DATA,TEA,EMI,STATIONARY,INCENTIVE,CLEANING,PRINT,REFUND,MARKETING,DIGITAL MARKETING,OTHERS'],
            'amount' => 'required|numeric|min:0',
            'manager' => 'nullable|in:BENAZIR,AFNAS,PRABHAKARAN,RAFEEQUE,OTHERS'
        ]);

        $expense->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Expense updated successfully', 'expense' => $expense], 200);
        }

        return redirect()->route('expense.page')->with('success', 'Expense updated successfully!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Expense deleted successfully'], 200);
        }

        return redirect()->route('expense.page')->with('success', 'Expense deleted successfully!');
    }
}
