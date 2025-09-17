<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FreshData;
use Illuminate\Support\Facades\Auth;

class FreshDataController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->is_admin) {
            $freshData = FreshData::with('user')->latest()->get();
        } else {
            $freshData = FreshData::with('user')->where('assigned_to', $user->id)->latest()->get();
        }
        return view('profile.fresh_data', compact('freshData'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mobile' => 'required|digits:10',
            'name' => 'required|string|max:255',
            'source' => 'required|string|max:255',
            'remarks' => 'nullable|string',
        ]);
    $validated['assigned_to'] = Auth::id();
        FreshData::create($validated);
        return redirect()->route('fresh.data')->with('success', 'Fresh data added successfully!');
    }
}
