<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\StaffTarget;
use App\Models\Sale;
use Carbon\Carbon;

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

        // Calculate New Profiles count (only profiles WITHOUT status or with empty status)
        if ($currentUser->is_admin) {
            // Admin sees total assigned profiles without status
            $newProfilesCount = \App\Models\FreshData::whereNotNull('assigned_to')
                ->where(function($query) {
                    $query->whereNull('status')
                          ->orWhere('status', '');
                })
                ->count();
        } else {
            // Staff/Sales sees only profiles assigned to them WITHOUT status
            $newProfilesCount = \App\Models\FreshData::where('assigned_to', $currentUser->id)
                ->where(function($query) {
                    $query->whereNull('status')
                          ->orWhere('status', '');
                })
                ->count();
        }

        // Calculate Follow-up Today count (profiles with today's follow-up date)
        $today = Carbon::today()->format('Y-m-d');
        if ($currentUser->is_admin) {
            // Admin sees all follow-ups for today
            $followupTodayCount = \App\Models\FreshData::whereDate('follow_up_date', $today)->count();
        } else {
            // Staff/Sales sees only their follow-ups for today
            $followupTodayCount = \App\Models\FreshData::where('assigned_to', $currentUser->id)
                ->whereDate('follow_up_date', $today)
                ->count();
        }

        // Calculate Follow-up Due count (profiles with follow-up date before today that weren't touched)
        if ($currentUser->is_admin) {
            // Admin sees all overdue follow-ups
            $followupDueCount = \App\Models\FreshData::whereDate('follow_up_date', '<', $today)
                ->whereNotNull('follow_up_date')
                ->count();
        } else {
            // Staff/Sales sees only their overdue follow-ups
            $followupDueCount = \App\Models\FreshData::where('assigned_to', $currentUser->id)
                ->whereDate('follow_up_date', '<', $today)
                ->whereNotNull('follow_up_date')
                ->count();
        }

        // Calculate Total Sales for current month
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');
        
        if ($currentUser->is_admin) {
            $totalSales = Sale::whereBetween('date', [$startOfMonth, $endOfMonth])->sum('paid_amount');
        } else {
            $totalSales = Sale::where('staff_id', $currentUser->id)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->sum('paid_amount');
        }

        // Get Target Amount from staff_targets table
        // Month is stored as string 'YYYY-MM', not a date field
        $currentMonth = Carbon::now()->format('Y-m');
        
        if ($currentUser->is_admin) {
            // Admin sees total of all staff targets for current month
            $targetAmount = StaffTarget::where('month', $currentMonth)->sum('target_amount');
        } else {
            // Staff sees only their total target for current month (sum of all their targets)
            $targetAmount = StaffTarget::where('staff_id', $currentUser->id)
                ->where('month', $currentMonth)
                ->sum('target_amount');
        }

        // If no target found, default to 50000
        if ($targetAmount == 0) {
            $targetAmount = 50000;
        }

        // Calculate achievement percentage
        $achievementPercentage = $targetAmount > 0 ? round(($totalSales / $targetAmount) * 100, 1) : 0;

        // You might also want to get some statistics for the dashboard
        $stats = [
            'total_users' => User::count(),
            'new_profiles' => $newProfilesCount,
            'followup_today' => $followupTodayCount,
            'followup_due' => $followupDueCount,
            'target_amount' => $targetAmount,
            'total_sales' => $totalSales,
            'achievement_percentage' => $achievementPercentage,
            // Add other stats as needed
        ];

        return view('dashboard', compact('users', 'stats', 'currentUser'));
    }

    public function followupDue(Request $request)
    {
        $currentUser = Auth::user();
        $today = Carbon::today()->format('Y-m-d');

        // Get all executives for the filter dropdown
        $executives = \App\Models\User::where(function($query) {
            $query->where('is_admin', 1)
                  ->orWhere('user_type', 'staff');
        })
        ->orderBy('first_name')
        ->get(['id', 'first_name', 'last_name']);

        // Get follow-up due profiles
        $query = \App\Models\FreshData::whereDate('follow_up_date', '<', $today)
            ->whereNotNull('follow_up_date')
            ->with('user');

        // Filter based on user role
        if (!$currentUser->is_admin) {
            $query->where('assigned_to', $currentUser->id);
        }

        // Apply filters
        // Executive filter
        if ($request->filled('executive_id')) {
            if ($request->executive_id === 'unassigned') {
                $query->whereDoesntHave('user');
            } else {
                $query->where('assigned_to', $request->executive_id);
            }
        }

        // Assigned date filter
        if ($request->filled('assigned_date')) {
            $query->whereDate('created_at', $request->assigned_date);
        }

        // Follow-up date filter
        if ($request->filled('followup_date')) {
            $query->whereDate('follow_up_date', $request->followup_date);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Get paginated results
        $perPage = $request->input('per_page', 20);
        $profiles = $query->orderBy('follow_up_date', 'asc')->paginate($perPage);

        return view('profile.followup_due', compact('profiles', 'currentUser', 'executives'));
    }
}
