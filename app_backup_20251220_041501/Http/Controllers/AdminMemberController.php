<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\User;

class AdminMemberController extends Controller
{
    public function show($encryptedId)
    {
        try {
            // Decrypt the encrypted ID
            $id = Crypt::decrypt($encryptedId);
            
            // Find the user by ID
            $member = User::findOrFail($id);
            
            // Return the view with member data
            return view('admin.members.show', compact('member'));
            
        } catch (DecryptException $e) {
            // Handle decryption failure
            return redirect()->back()->with('error', 'Invalid profile link.');
        } catch (\Exception $e) {
            // Handle other exceptions (like user not found)
            return redirect()->back()->with('error', 'Profile not found.');
        }
    }
}