<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}