<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get the number of items per page from request, default to 10
        $perPage = $request->get('per_page', 10);
        
        // Get users with pagination
        $users = User::paginate($perPage);
        
        // Get current user
        $currentUser = Auth::user();
        
        // You might also want to get some statistics for the dashboard
        $stats = [
            'total_users' => User::count(),
            'new_profiles' => User::whereDate('created_at', today())->count(),
            // Add other stats as needed
        ];
        
        return view('dashboard', compact('users', 'stats', 'currentUser'));
    }
}