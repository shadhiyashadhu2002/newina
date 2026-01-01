<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\FreshData;
use App\Models\User;
use App\Models\StaffTarget;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SalesDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get current user
        $currentUser = Auth::user();
        
        // Calculate New Profiles count (only profiles WITHOUT status or with empty status)
        if ($currentUser->is_admin) {
            // Admin sees total assigned profiles without status
            $newProfilesCount = FreshData::whereNotNull('assigned_to')
                ->where(function($query) {
                    $query->whereNull('status')
                          ->orWhere('status', '');
                })
                ->count();
        } else {
            // Staff/Sales sees only profiles assigned to them WITHOUT status
            $newProfilesCount = FreshData::where('assigned_to', $currentUser->id)
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
            $followupTodayCount = FreshData::whereDate('follow_up_date', $today)->count();
        } else {
            // Staff/Sales sees only their follow-ups for today
            $followupTodayCount = FreshData::where('assigned_to', $currentUser->id)
                ->whereDate('follow_up_date', $today)
                ->count();
        }

        // Calculate Follow-up Due count (profiles with follow-up date before today)
        if ($currentUser->is_admin) {
            // Admin sees all overdue follow-ups
            $followupDueCount = FreshData::whereDate('follow_up_date', '<', $today)
                ->whereNotNull('follow_up_date')
                ->count();
        } else {
            // Staff/Sales sees only their overdue follow-ups
            $followupDueCount = FreshData::where('assigned_to', $currentUser->id)
                ->whereDate('follow_up_date', '<', $today)
                ->whereNotNull('follow_up_date')
                ->count();
        }

        // Calculate Assigned Today (profiles assigned today)
        if ($currentUser->is_admin) {
            $assignedToday = FreshData::whereDate('created_at', Carbon::today())->count();
        } else {
            $assignedToday = FreshData::where('assigned_to', $currentUser->id)
                ->whereDate('created_at', Carbon::today())
                ->count();
        }

        // Calculate Clients Contacted (profiles with last_touched_at today)
        if ($currentUser->is_admin) {
            $clientsContacted = FreshData::whereDate('last_touched_at', Carbon::today())->count();
        } else {
            $clientsContacted = FreshData::where('assigned_to', $currentUser->id)
                ->whereDate('last_touched_at', Carbon::today())
                ->count();
        }

        // Calculate Reassigned Profiles (profiles reassigned this month)
        if ($currentUser->is_admin) {
            $reassignedProfiles = FreshData::whereMonth('last_touched_at', Carbon::now()->month)
                ->whereYear('last_touched_at', Carbon::now()->year)
                ->count();
        } else {
            $reassignedProfiles = FreshData::where('assigned_to', $currentUser->id)
                ->whereMonth('last_touched_at', Carbon::now()->month)
                ->whereYear('last_touched_at', Carbon::now()->year)
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
            $target = StaffTarget::where('month', $currentMonth)->sum('target_amount');
        } else {
            // Staff sees only their total target for current month (sum of all their targets)
            $target = StaffTarget::where('staff_id', $currentUser->id)
                ->where('month', $currentMonth)
                ->sum('target_amount');
        }
        
        // If no target found, default to 50000
        if ($target == 0) {
            $target = 50000;
        }
        
        // Calculate achievement percentage
        $achievementPercentage = $target > 0 ? round(($totalSales / $target) * 100, 1) : 0;

        // Prepare stats array
        $stats = [
            'followup_today' => $followupTodayCount,
            'followup_due' => $followupDueCount,
            'new_profiles' => $newProfilesCount,
            'reassigned_profiles' => $reassignedProfiles,
            'assigned_today' => $assignedToday,
            'clients_contacted' => $clientsContacted,
            'total_sales' => number_format($totalSales, 0),
            'target' => number_format($target, 0),
            'achievement_percentage' => $achievementPercentage,
        ];

        return view('sales-management', compact('stats', 'currentUser'));
    }
}
