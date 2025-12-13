<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Log;

class EmployeeSheetController extends Controller
{
    public function index()
    {
        // Show only latest 5 employees on initial page load
        $employees = Employee::orderBy('created_at', 'desc')->take(5)->get();
        return view('employee-sheet', compact('employees'));
    }

    /**
     * Server-side search endpoint for employee filters.
     * Accepts query params: code, name, contact, aadhar, department
     */
    public function search(Request $request)
    {
        $query = Employee::query();

        if ($request->filled('code')) {
            $query->where('emp_code', 'LIKE', '%' . $request->input('code') . '%');
        }
        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->input('name') . '%');
        }
        if ($request->filled('contact')) {
            $query->where('contact_person', 'LIKE', '%' . $request->input('contact') . '%');
        }
        if ($request->filled('aadhar')) {
            $query->where('aadhar_card_no', 'LIKE', '%' . $request->input('aadhar') . '%');
        }
        if ($request->filled('department')) {
            $query->where('department', 'LIKE', '%' . $request->input('department') . '%');
        }

        $employees = $query->orderBy('created_at', 'desc')->get()->map(function ($e) {
            return [
                'id' => $e->id,
                'emp_code' => $e->emp_code,
                'name' => $e->name,
                'emergency_mobile' => $e->emergency_mobile,
                'email' => $e->email,
                'contact_person' => $e->contact_person,
                'aadhar_card_no' => $e->aadhar_card_no,
                'address' => $e->address,
                'date_of_joining' => $e->date_of_joining ? $e->date_of_joining->format('d-m-Y') : null,
                'designation' => $e->designation,
                'department' => $e->department,
                'company' => $e->company,
                'salary' => $e->salary,
            ];
        });

        return response()->json(['success' => true, 'employees' => $employees]);
    }

    public function store(Request $request)
    {
        try {
            Log::info('Employee store payload', $request->all());

            $validated = $request->validate([
            'emp_code' => 'required|string|unique:employees,emp_code',
            'name' => 'required|string',
            'emergency_mobile' => 'nullable|string',
            'email' => 'nullable|email',
            'contact_person' => 'nullable|string',
            'aadhar_card_no' => 'nullable|string',
            'address' => 'nullable|string',
            'date_of_joining' => 'nullable|date',
            'designation' => 'nullable|string',
            'department' => 'nullable|string',
            'company' => 'nullable|string',
            'salary' => 'nullable|numeric',
        ]);
            $employee = Employee::create($validated);

            Log::info('Employee created', ['id' => $employee->id]);

            return response()->json(['success' => true, 'message' => 'Employee added successfully.', 'id' => $employee->id]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            Log::warning('Employee store validation failed', ['errors' => $ve->errors()]);
            return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $ve->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Employee store exception: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'emergency_mobile' => 'nullable|string',
            'email' => 'nullable|email',
            'contact_person' => 'nullable|string',
            'aadhar_card_no' => 'nullable|string',
            'address' => 'nullable|string',
            'date_of_joining' => 'nullable|date',
            'designation' => 'nullable|string',
            'department' => 'nullable|string',
            'company' => 'nullable|string',
            'salary' => 'nullable|numeric',
        ]);

        $employee->update($validated);

        return response()->json(['success' => true, 'message' => 'Employee updated successfully.']);
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return response()->json(['success' => true, 'message' => 'Employee deleted successfully.']);
    }
}
