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
    // Calculate expense summaries
    $todayExpense = Expense::today()->sum('amount');
    $weekExpense = Expense::thisWeek()->sum('amount');
    $monthExpense = Expense::thisMonth()->sum('amount');
    $totalExpense = Expense::sum('amount');

    // Build query with filters - INITIALIZE QUERY FIRST
    $query = Expense::with('createdBy');

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

    // Get expenses with pagination - NOW USE $query VARIABLE
    $expenses = $query->orderBy('date', 'desc')
        ->orderBy('id', 'desc')
        ->paginate(10)
        ->appends($request->all()); // Keep filters in pagination

    return view('expense', compact(
        'todayExpense',
        'weekExpense', 
        'monthExpense',
        'totalExpense',
        'expenses'
    ));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'notes' => ['nullable', 'in:salary,recharge,mobile/pc,repair,rent,electricity,water,festival,travel,data,tea,EMI,stationary,incentive,cleaning,print,refund,markrting,digital marketing,others'],
            'amount' => 'required|numeric|min:0',
            'manager' => 'nullable|in:benazir,afnas,prabhakaran,rafeeque,others'
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
            'notes' => ['nullable', 'in:salary,recharge,mobile/pc,repair,rent,electricity,water,festival,travel,data,tea,EMI,stationary,incentive,cleaning,print,refund,markrting,digital marketing,others'],
            'amount' => 'required|numeric|min:0',
            'manager' => 'nullable|in:benazir,afnas,prabhakaran,rafeeque,others'
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

    public function show(Expense $expense)
    {
        return response()->json($expense);
    }
}
