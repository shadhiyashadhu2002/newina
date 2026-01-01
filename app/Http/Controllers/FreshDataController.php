<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FreshData;
use Illuminate\Support\Facades\Auth;
use App\Imports\FreshDataImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

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
        $source = request()->query('source');

        // Ensure these variables exist so compact() won't throw if one mode is not used
        $databaseUsers = collect();
        $freshData = collect();

        // If requested source is 'database', show users table instead
        if ($source === 'database') {
            // Show paginated users from users table - select first_name/last_name and phone/gender
            $databaseUsers = \App\Models\User::select('id', 'first_name', 'last_name', 'name', 'email', 'phone', 'gender')
                ->orderBy('first_name')
                ->paginate(25)
                ->withQueryString();
        } else {
            if ($user->is_admin) {
                // Admin sees UNASSIGNED profiles (to assign them)
                $freshData = FreshData::with('user')
                    ->whereNull('assigned_to')
                    ->latest()
                    ->get();
            } else {
                // Service/Sales executives see profiles ASSIGNED TO THEM (their work)
                $freshData = FreshData::with('user')
                    ->where('assigned_to', $user->id)
                    ->latest()
                    ->get();
            }
        }

        // Fetch service executives (staff) with names
        $serviceExecutives = \App\Models\User::where('user_type', 'staff')
            ->whereNotNull('name')
            ->orderBy('name')
            ->get(['id', 'name']);
        
        // Fetch sales executives
        $salesExecutives = \App\Models\User::whereIn('name', [
            'SAFA', 'RIMA', 'ASNA', 'MIZHI', 'THARA', 'ISHA', 
            'AKSHARA', 'MIYA', 'DIVYA', 'FIDHA', 'JANNA', 'ZARA', 
            'NEHA', 'FARHA', 'RIYA'
        ])
            ->orderBy('name')
            ->get(['id', 'name']);
        
        return view('profile.fresh_data', compact('serviceExecutives', 'salesExecutives', 'source', 'freshData', 'databaseUsers'));
    }

    public function store(Request $request)
    {
        // Log the incoming request for debugging
        \Log::info('FreshData::store called', [
            'user_id' => Auth::id(),
            'input' => $request->all()
        ]);

        $validated = $request->validate([
            'mobile' => 'required|digits:10',
            'name' => 'required|string|max:255',
            'source' => 'required|string|max:255',
            'remarks' => 'nullable|string',
            'welcome_call' => 'nullable|boolean',
        ]);

        // Provide both 'name' and 'customer_name' fields
        $createData = [
            'name' => $validated['name'],
            'customer_name' => $validated['name'],
            'mobile' => $validated['mobile'],
            'source' => $validated['source'],
            'remarks' => $validated['remarks'] ?? null,
            'welcome_call' => $request->has('welcome_call'),
        ];

        // If the current user is not an admin, assign the new record to them
        if (Auth::check() && !Auth::user()->is_admin) {
            $createData['assigned_to'] = Auth::id();
        }

        try {
            $fresh = FreshData::create($createData);
            \Log::info('FreshData created', [ 'id' => $fresh->id ?? null, 'assigned_to' => $fresh->assigned_to ?? null ]);
            return redirect()->route('fresh.data.index')->with('success', 'Fresh data added successfully!');
        } catch (\Exception $e) {
            \Log::error('FreshData::store exception', [
                'message' => $e->getMessage(),
                'input' => $createData,
                'user_id' => Auth::id()
            ]);

            return back()->withInput()->with('error', 'Failed to add fresh data: ' . $e->getMessage());
        }
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

    public function view($id)
    {
        $freshData = FreshData::with('user')->findOrFail($id);
        return view('profile.fresh_data_view', compact('freshData'));
    }

    public function import(Request $request)
    {
        try {
            Log::info("=== IMPORT STARTED ===");
            Log::info("Request Data", [
                "source" => $request->input("source"),
                "has_file" => $request->hasFile("excel_file"),
                "file_original_name" => $request->file('excel_file') ? $request->file('excel_file')->getClientOriginalName() : 'none',
                "file_mime" => $request->file('excel_file') ? $request->file('excel_file')->getMimeType() : 'none',
                "file_extension" => $request->file('excel_file') ? $request->file('excel_file')->getClientOriginalExtension() : 'none',
                "file_size" => $request->file('excel_file') ? $request->file('excel_file')->getSize() : 0,
            ]);

            // More permissive validation - allow text/plain and text/csv
            $validated = $request->validate([
                'excel_file' => 'required|file|mimes:xlsx,xls,csv,txt|mimetypes:text/plain,text/csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet|max:10240',
                'source' => 'required|string|in:Facebook,Instagram,Google Ads,Justdial,Website,Referral,Other',
            ]);

            Log::info("✓ Validation passed");

            $file = $request->file('excel_file');
            $source = $request->input('source');

            Log::info("Processing import", [
                "filename" => $file->getClientOriginalName(),
                "source" => $source,
                "size" => $file->getSize()
            ]);

            // Create import instance WITH source
            $import = new FreshDataImport($source);

            // Import the file
            Excel::import($import, $file);

            $imported = $import->getImportedCount();
            $skipped = $import->getSkippedCount();

            $message = "Import completed! Successfully imported: {$imported} records from {$source}. ";
            if ($skipped > 0) {
                $message .= "Skipped: {$skipped} records.";
            }

            Log::info('Excel Import Completed', [
                'user_id' => Auth::id(),
                'imported' => $imported,
                'skipped' => $skipped,
                'source' => $source,
            ]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'imported' => $imported,
                'skipped' => $skipped,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("✗ VALIDATION FAILED", ["errors" => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . json_encode($e->errors()),
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error("✗ IMPORT EXCEPTION", [
                "message" => $e->getMessage(),
                "line" => $e->getLine(),
                "file" => $e->getFile()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export all users as a CSV file (Database view)
     */
    public function exportUsers()
    {
        $filename = 'users_export_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $columns = ['ID', 'Name', 'Email', 'Phone', 'User Type'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            \App\Models\User::orderBy('name')->chunk(200, function ($users) use ($file) {
                foreach ($users as $u) {
                    fputcsv($file, [
                        $u->id,
                        $u->name,
                        $u->email,
                        $u->phone,
                        $u->user_type,
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function downloadTemplate()
    {
        $filename = 'fresh_data_template.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $columns = ['Mobile'];
        $sampleData = [
            ['9876543210'],
            ['9876543211'],
            ['9876543212'],
        ];

        $callback = function() use ($columns, $sampleData) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($sampleData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Assign fresh data to a sales/service executive
     */
    public function assign(Request $request, $id)
    {
        try {
            $request->validate([
                'assigned_to' => 'required|integer|exists:users,id'
            ]);

            $freshData = FreshData::findOrFail($id);
            $freshData->assigned_to = $request->assigned_to;
            $freshData->save();

            return response()->json([
                'success' => true,
                'message' => 'Successfully assigned to ' . $request->assigned_to
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk assign fresh data to a sales/service executive
     */
    public function bulkAssign(Request $request)
    {
        try {
            $request->validate([
                'record_ids' => 'required|array',
                'record_ids.*' => 'integer',
                'assigned_to' => 'required|integer|exists:users,id'
            ]);

            $recordIds = $request->record_ids;
            $assignedTo = $request->assigned_to;

            // Update all selected records
            $updated = FreshData::whereIn('id', $recordIds)
                ->update(['assigned_to' => $assignedTo]);

            return response()->json([
                'success' => true,
                'message' => "Successfully assigned {$updated} record(s) to {$assignedTo}"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign: ' . $e->getMessage()
            ], 500);
        }
    }

    // View assigned profiles for service/sales executives
   public function myAssignedProfiles()
    {
        $user = Auth::user();
        $today = now()->format('Y-m-d');

        if ($user->is_admin) {
            // Admin sees profiles that are either:
            // 1. Not updated yet (no follow_up_date) OR
            // 2. Follow-up date is today
            $freshData = FreshData::with('user')
                ->whereNotNull('assigned_to')
                ->where(function($query) use ($today) {
                    $query->whereNull('follow_up_date')
                          ->orWhereDate('follow_up_date', $today);
                })
                ->latest()
                ->get();

            // Calculate follow-up today count for admin
            $followupTodayCount = FreshData::whereDate('follow_up_date', $today)->count();
        } else {
            // Service/Sales executives see only their profiles that are either:
            // 1. Not updated yet (no follow_up_date) OR
            // 2. Follow-up date is today
            $freshData = FreshData::where('assigned_to', $user->id)
                ->where(function($query) use ($today) {
                    $query->whereNull('follow_up_date')
                          ->orWhereDate('follow_up_date', $today);
                })
                ->latest()
                ->get();

            // Calculate follow-up today count for staff
            $followupTodayCount = FreshData::where('assigned_to', $user->id)
                ->whereDate('follow_up_date', $today)
                ->count();
        }

        // Separate into new profiles and follow-up today
        $newProfiles = $freshData->whereNull('follow_up_date');
        $followupToday = $freshData->whereNotNull('follow_up_date');

        // Calculate stats
        $stats = [
            'total' => $freshData->count(),
            'pending' => $freshData->where('status', '!=', 'Completed')->count(),
            'completed' => $freshData->where('status', 'Completed')->count(),
            'followup_today' => $followupTodayCount,
            'new_profiles' => $newProfiles->count()
        ];

        // DEBUG: Check what data we're passing
        \Log::info('MyAssignedProfiles data:', [
            'count' => $freshData->count(),
            'stats' => $stats,
            'today' => $today,
            'new_profiles_count' => $newProfiles->count(),
            'followup_today_count' => $followupToday->count(),
        ]);

        return view('profile.my_assigned_profiles', compact('freshData', 'stats', 'newProfiles', 'followupToday'));
    }

    /**
     * Get history for a profile
     */
    public function getHistory($id)
    {
        try {
            $profile = FreshData::findOrFail($id);
            
            // Check if user has access to this profile
            if (!Auth::user()->is_admin && $profile->assigned_to != Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            // Get all history records for this profile from profile_history table
            $history = \DB::table('profile_history')
                ->where('profile_id', $id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($record) {
                    return [
                        'updated_at' => date('d M Y, h:i a', strtotime($record->created_at)),
                        'executive' => $record->executive_name ?? 'N/A',
                        'customer_name' => $record->customer_name ?? '-',
                        'status' => $record->status ?? '-',
                        'assigned_date' => $record->assigned_date ? date('d M Y', strtotime($record->assigned_date)) : 'N/A',
                        'follow_up_date' => $record->follow_up_date ? date('d M Y', strtotime($record->follow_up_date)) : '-',
                        'imid' => $record->imid ?? 'N/A',
                        'remarks' => $record->remarks ?? '-',
                        'action' => $record->action_type ?? 'update'
                    ];
                });

            return response()->json([
                'success' => true,
                'history' => $history
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch history: ' . $e->getMessage()
            ], 500);
        }
    }
    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'profile_id' => 'required|integer|exists:fresh_data,id',
            'status' => 'required|string',
            'follow_up_date' => 'nullable|date',
            'customer_name' => 'nullable|string|max:255',
            'imid' => 'nullable|string|max:100',
            'secondary_phone' => 'nullable|string|max:20',
            'is_new_lead' => 'nullable|in:yes,no',
            'remarks' => 'nullable|string'
        ]);
        
        $profile = FreshData::findOrFail($validated['profile_id']);
        
        if (!Auth::user()->is_admin && $profile->assigned_to != Auth::id()) {
            return back()->with('error', 'Unauthorized access.');
        }
        
        $updateData = ['status' => $validated['status']];
        if (isset($validated['follow_up_date'])) $updateData['follow_up_date'] = $validated['follow_up_date'];
        if (isset($validated['customer_name'])) $updateData['customer_name'] = $validated['customer_name'];
        if (isset($validated['imid'])) $updateData['imid'] = $validated['imid'];
        if (isset($validated['secondary_phone'])) $updateData['secondary_phone'] = $validated['secondary_phone'];
        if (isset($validated['is_new_lead'])) $updateData['is_new_lead'] = $validated['is_new_lead'];
        if (isset($validated['remarks'])) $updateData['remarks'] = $validated['remarks'];
        
        $profile->update($updateData);
        
        // Save to profile_history table
        \DB::table('profile_history')->insert([
            'profile_id' => $profile->id,
            'updated_by' => Auth::id(),
            'executive_name' => Auth::user()->name,
            'customer_name' => $validated['customer_name'] ?? $profile->customer_name,
            'status' => $validated['status'],
            'follow_up_date' => $validated['follow_up_date'] ?? null,
            'remarks' => $validated['remarks'] ?? null,
            'imid' => $validated['imid'] ?? $profile->imid,
            'assigned_date' => $profile->created_at,
            'action_type' => 'update',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        if (request()->ajax() || request()->wantsJson()) {
            $profile->refresh();
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully!',
                'profile' => $profile
            ]);
        }
        
        return redirect()->back()->with('success', 'Status updated successfully!');

    }
}
