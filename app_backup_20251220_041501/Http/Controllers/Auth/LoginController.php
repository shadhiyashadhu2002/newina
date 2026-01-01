<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        
        $credentials = $request->only('email', 'password');
        
        // Check if user exists first
        $user = \App\Models\User::where('email', $credentials['email'])->first();
        if (!$user) {
            return back()->withErrors(['email' => 'No account found with this email address'])->withInput();
        }
        
        // Check password
        if (!\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['password' => 'The password is incorrect'])->withInput();
        }
        
        // Attempt authentication
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Regenerate session for security
            $request->session()->regenerate();
            
            // Redirect based on user type
            if ($user->is_admin || $user->user_type === 'staff') {
                return redirect()->intended('/dashboard'); // Admin and Staff dashboard
            } else {
                return redirect()->intended('/user/dashboard'); // Regular user dashboard
            }
        }
        
        return back()->withErrors(['email' => 'Authentication failed. Please try again.'])->withInput();
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Registration logic here
        // ...
        return redirect()->route('login');
    }
}
