<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Status;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index()
    {
        // Fetch all statuses and departments to display in the settings page
        $statuses = Status::all();
        $departments = Department::all();
        
        return view('settings.index', compact('statuses', 'departments'));
    }

    public function addStaff(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'team' => 'required|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'first_name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff',
            'team' => $request->team,
            'user_type' => 'staff',
        ]);

        return redirect()->back()->with('success', 'Staff member added successfully!');
    }

    public function addStatus(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Status::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Status added successfully!');
    }

    public function addDepartment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Department::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Department added successfully!');
    }
}
