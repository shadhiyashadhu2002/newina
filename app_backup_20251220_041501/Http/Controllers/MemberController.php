<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // or your profile model

class MemberController extends Controller
{
    public function profiles()
    {
        $profiles = User::paginate(10);
        return view('admin.members.profiles', compact('profiles'));
    }
}