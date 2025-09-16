<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // If user is admin, redirect to admin dashboard
        if ($user->is_admin) {
            return redirect()->route('dashboard'); // This is the admin dashboard route
        }
        
        return view('user.dashboard', compact('user'));
    }

    // Show the edit profile page
    public function editProfile($id)
    {
        $user = User::findOrFail($id);
        return view('profile.profile_edit', compact('user'));
    }

    public function updateProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'phone' => 'nullable|string|max:20',
            'phone2' => 'nullable|string|max:20',
            'whatsapp_phone' => 'nullable|string|max:20',
            'welcome_call_completed' => 'nullable|boolean',
            'comments' => 'nullable|string|max:1000',
        ]);

        $user->first_name = $validatedData['first_name'];
        $user->gender = $validatedData['gender'];
        $user->phone = $validatedData['phone'] ?? null;
        $user->phone2 = $validatedData['phone2'] ?? null;
        $user->whatsapp_number = $validatedData['whatsapp_phone'] ?? null;
        $user->welcome_call_completed = $request->has('welcome_call_completed');
        $user->comments = $validatedData['comments'] ?? null;
        $user->save();

        return redirect()->route('profile.hellow')->with('success', 'Profile updated successfully!');
    }

public function store(Request $request)
{
    // Validation and creation logic
    $validated = $request->validate([
        'code' => 'required|unique:users',
        'first_name' => 'required|string|max:255',
        'gender' => 'required|in:Male,Female,Other',
        'phone' => 'required|string',
        'phone2' => 'nullable|string',
        'whatsapp_phone' => 'nullable|string',
        'comments' => 'nullable|string',
    ]);

    $user = new User();
    $user->code = $validated['code'];
    $user->first_name = $validated['first_name'];
    $user->name = $validated['first_name']; // For compatibility
    $user->gender = $validated['gender'];
    $user->phone = $validated['phone'];
    $user->phone2 = $validated['phone2'] ?? null;
    $user->whatsapp_number = $validated['whatsapp_phone'] ?? null;
    $user->comments = $validated['comments'] ?? null;
    $user->save();

    return redirect()->route('profile.hellow')->with('success', 'Profile created successfully!');
}
}