<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminController extends Controller
{
	public function showLoginForm()
	{
		return view('admin.login');
	}

	public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['is_admin'] = 1;
        
        if (Auth::attempt($credentials)) {
            // Regenerate session to prevent fixation attacks
            $request->session()->regenerate();
            
            // Redirect to dashboard
            return redirect()->route('dashboard');
        }
        
        return back()->withErrors(['email' => 'Invalid credentials or not an admin.']);
    }
}
