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

        // Get expenses with pagination
        $expenses = Expense::with('createdBy')
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10);

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
