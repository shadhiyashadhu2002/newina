<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FreshData;
use Illuminate\Support\Facades\Auth;

class FreshDataController extends Controller
{
    public function edit($id)
    {
    $freshData = FreshData::findOrFail($id);
    return view('profile.edit_fresh_data', compact('freshData'));
    }
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
    public function update(Request $request, $id)
    {
        $freshData = FreshData::findOrFail($id);
        $validated = $request->validate([
            'mobile' => 'required|digits:10',
            'name' => 'required|string|max:255',
            'source' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'gender' => 'nullable|string',
            'registration_date' => 'nullable|date',
            'profile_id' => 'nullable|string',
            'mobile_number_2' => 'nullable|string',
            'whatsapp_number' => 'nullable|string',
            'profile_created' => 'nullable|boolean',
            'photo_uploaded' => 'nullable|boolean',
            'welcome_call' => 'nullable|boolean',
        ]);
        $validated['profile_created'] = $request->has('profile_created');
        $validated['photo_uploaded'] = $request->has('photo_uploaded');
        $validated['welcome_call'] = $request->has('welcome_call');
        $freshData->update($validated);
        return redirect()->route('fresh.data')->with('success', 'Fresh data updated successfully!');
    }
}
