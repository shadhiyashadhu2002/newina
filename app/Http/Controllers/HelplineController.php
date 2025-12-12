<?php

namespace App\Http\Controllers;

use App\Models\HelplineQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HelplineController extends Controller
{
    public function index(Request $request)
    {
        $query = HelplineQuery::query()->orderBy('date', 'desc');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('mobile_number', 'like', "%{$search}%")
                  ->orWhere('profile_id', 'like', "%{$search}%")
                  ->orWhere('executive_name', 'like', "%{$search}%");
            });
        }

        $queries = $query->paginate(10);

        return view('helpline-tracker', compact('queries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'call_status' => 'nullable|string',
            'nature_of_call' => 'nullable|string',
            'video_source' => 'nullable|string',
            'video_reference' => 'nullable|string',
            'mobile_number' => 'nullable|string',
            'profile_id' => 'nullable|string',
            'executive_name' => 'nullable|string',
            'remarks' => 'nullable|string',
            'purpose' => 'nullable|string',
            'new_lead' => 'nullable|boolean',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['new_lead'] = $request->has('new_lead');

        HelplineQuery::create($validated);

        return redirect()->back()->with('success', 'Query added successfully!');
    }

    public function update(Request $request, $id)
    {
        $query = HelplineQuery::findOrFail($id);

        $validated = $request->validate([
            'date' => 'required|date',
            'call_status' => 'nullable|string',
            'nature_of_call' => 'nullable|string',
            'video_source' => 'nullable|string',
            'video_reference' => 'nullable|string',
            'mobile_number' => 'nullable|string',
            'profile_id' => 'nullable|string',
            'executive_name' => 'nullable|string',
            'remarks' => 'nullable|string',
            'purpose' => 'nullable|string',
            'new_lead' => 'nullable|boolean',
        ]);

        $validated['new_lead'] = $request->has('new_lead');

        $query->update($validated);

        return redirect()->back()->with('success', 'Query updated successfully!');
    }

    public function destroy($id)
    {
        $query = HelplineQuery::findOrFail($id);
        $query->delete();

        return redirect()->back()->with('success', 'Query deleted successfully!');
    }
}
