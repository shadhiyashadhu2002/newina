@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-left">
            <h1 class="dashboard-title">INA</h1>
            <nav class="main-nav">
                <a href="{{ route('dashboard') }}" class="nav-link active">Home</a>
                <a href="{{ route('profile.hellow') }}" class="nav-link">Profiles</a>
                <div class="nav-dropdown">
                    <a href="#" class="nav-link">Sales ▼</a>
                </div>
                <a href="#" class="nav-link">HelpLine</a>
                <div class="nav-dropdown">
                    <a href="{{ route('fresh.data') }}" class="nav-link">Fresh Data</a>
                </div>
                <a href="#" class="nav-link">abc</a>
                <div class="nav-dropdown">
                    <a href="{{ route('services.page') }}" class="nav-link">Services ▼</a>
                </div>
                <div class="nav-dropdown accounts-dropdown">
                    <!-- Make the anchor focusable and prevent default so :focus-within keeps dropdown open when interacting -->
                    <a href="#" class="nav-link" tabindex="0" onclick="event.preventDefault();">Accounts ▼</a>
                    <div class="dropdown-content" style="overflow: visible !important;" aria-hidden="false">
                        <a href="{{ route('addsale.page') }}" class="dropdown-item">Add Sale</a>
                        <a href="{{ route('stafftarget.page') }}" class="dropdown-item">Staff Target Assign</a>
                        <a href="{{ route('staffproductivity.page') }}" class="dropdown-item">Staff Productivity</a>
                        <a href="{{ route('expense.page') }}" class="dropdown-item">Expense Page</a>
                    </div>
                </div>
            </nav>

        </div>
        <div class="header-right">
            @if(isset($profileId) && $profileId)
            <span class="profile-id">Profile ID: {{ $profileId }}</span>
            @endif
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                @csrf
            </form>
            <button class="logout-btn" id="logout-btn">Logout</button>
        </div>
    </div>
    <div class="stats-grid">
        <div class="stat-card green">
            <h3>Follow-up Today</h3>
            <div class="stat-number">0</div>
        </div>

        <div class="stat-card orange">
            <h3>Follow-up Due</h3>
            <div class="stat-number">2</div>
        </div>

        <div class="stat-card blue">
            <h3>New Profiles</h3>
            <div class="stat-number">1</div>
        </div>

        <div class="stat-card teal">
            <h3>Reassigned Profiles</h3>
            <div class="stat-number">0</div>
        </div>

        <div class="stat-card red">
            <h3>Assigned Today</h3>
            <div class="stat-number">0</div>
        </div>

        <div class="stat-card dark-green">
            <h3>Clients Contacted</h3>
            <div class="stat-number">0</div>
        </div>

        <div class="stat-card sales-card">
            <h3>Total Sales</h3>
            <div class="sales-amount">₹ 4,300</div>
            <div class="sales-target">Target: ₹50,000</div>
            <div class="achievement-badge">8.6% Achieved</div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 8.6%"></div>
            </div>
        </div>
    </div>
    <div class="filter-section">
        <div class="filter-header">
            <h2>🔍 Filter Follow-ups</h2>
            <button class="toggle-filters-btn">Toggle Filters</button>
        </div>

        <div class="filter-row">
            <div class="filter-group">
                <label>Status</label>
                <select class="filter-select">
                    <option>Select Status</option>
                    <option>Active</option>
                    <option>Inactive</option>
                    <option>Pending</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Assigned From</label>
                <input type="date" class="filter-input" placeholder="mm/dd/yyyy">
            </div>

            <div class="filter-group">
                <label>Assigned To</label>
                <input type="date" class="filter-input" placeholder="mm/dd/yyyy">
            </div>

            <div class="filter-group">
                <label>Follow-up From</label>
                <input type="date" class="filter-input" placeholder="mm/dd/yyyy">
            </div>

            <div class="filter-group">
                <label>Follow-up To</label>
                <input type="date" class="filter-input" placeholder="mm/dd/yyyy">
            </div>

            <div class="filter-group">
                <label>Mobile/Profile ID</label>
                <input type="text" class="filter-input" placeholder="Search...">
            </div>

            <div class="filter-actions">
                <button class="reset-btn">Reset</button>
                <button class="apply-btn">Apply Filters</button>
            </div>
        </div>
    </div>

    <div class="data-table-section">
        <div style="display: flex; justify-content: flex-end; align-items: center; padding: 20px 16px 10px 16px;">
            @if(is_a($users, 'Illuminate\\Pagination\\LengthAwarePaginator'))
            <div style="display: flex; gap: 8px;">
                @if(!$users->onFirstPage())
                <a href="{{ $users->previousPageUrl() }}" class="apply-btn" style="text-decoration: none;">Previous</a>
                @endif
                @if($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="apply-btn" style="text-decoration: none;">Next</a>
                @endif
            </div>
            @else
            @endif
        </div>

        <div style="display: flex; align-items: center; justify-content: flex-start; padding: 0 16px 10px 16px;">
            <form method="get" style="margin: 0; display: flex; align-items: center;">
                <label for="per_page" style="font-size: 14px; color: #333; margin-right: 8px;">Show</label>
                <select name="per_page" id="per_page" onchange="this.form.submit()" style="padding: 6px 12px; border-radius: 6px; border: 1px solid #e0e0e0; font-size: 14px;">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 entries</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 entries</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 entries</option>
                </select>
            </form>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Profile ID</th>
                    <th>Mobile Number</th>
                    <th>Assigned Date</th>
                    <th>Follow-up Date</th>
                    <th>Status</th>
                    <th>Followup Count</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->code }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>09-09-2025</td>
                    <td>13-09-2025</td>
                    <td><span class="status-badge postpone">Postpone Payment</span></td>
                    <td><span class="count-badge">1/3</span></td>
                    <td>
                        <button class="action-btn update">Update</button>
                        <button class="action-btn history">History</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="display: flex; justify-content: flex-start; align-items: center; padding: 10px 16px 20px 16px;">
            <div style="color: #333; font-size: 14px;">
                Showing {{ ($users->currentPage() - 1) * $users->perPage() + 1 }}
                -
                {{ min($users->currentPage() * $users->perPage(), $users->total()) }}
                of {{ $users->total() }} entries
            </div>
        </div>
    </div>
</div>
<script>
    // Logout functionality
    document.addEventListener('DOMContentLoaded', function() {
        var logoutBtn = document.getElementById('logout-btn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if(confirm('Are you sure you want to logout?')) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    });
</script>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
        min-height: 100vh;
    }

    .dashboard-container {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        min-height: 100vh;
        padding: 0;
    }


    .dashboard-header {
        background: rgba(255, 255, 255, 0.2);
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        backdrop-filter: blur(15px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        z-index: 100000;
        position: relative;
    }

    .dashboard-title {
        color: white;
        font-size: 24px;
        font-weight: bold;
        margin-right: 30px;
    }

    .header-left {
        display: flex;
        align-items: center;
    }

    .main-nav {
        display: flex;
        gap: 20px;
        position: relative;
        z-index: 100001;
    }

    .nav-link {
        color: white;
        text-decoration: none;
        padding: 8px 15px;
        border-radius: 5px;
        transition: background 0.3s;
    }

    .nav-link:hover,
    .nav-link.active {
        background: rgba(255, 255, 255, 0.2);
    }

    .nav-dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background: white;
        min-width: 240px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
        z-index: 999999 !important;
        top: 100%;
        left: 0;
        border-radius: 10px;
        overflow: visible !important;
        margin-top: 8px;
        border: 1px solid rgba(0, 0, 0, 0.1);
        padding: 0;
    }

    .accounts-dropdown {
        position: relative;
        display: inline-block;
    }

    .accounts-dropdown .dropdown-content {
        display: none !important;
    }

    /* Keep dropdown visible on hover OR when its anchor or content receives focus (keyboard/tab or click) */
    .accounts-dropdown:hover .dropdown-content,
    .accounts-dropdown:focus-within .dropdown-content {
        display: block !important;
    }

    .dropdown-item {
        color: #333;
        padding: 15px 20px;
        text-decoration: none;
        display: block;
        transition: all 0.3s ease;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        font-weight: 500;
        font-size: 14px;
        position: relative;
        z-index: 99999;
        margin: 0;
    }

    .dropdown-item:first-child {
        border-top: none;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .dropdown-item:last-child {
        border-bottom: none;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    .dropdown-item:hover {
        background: #f5f5f5;
        color: #ac0742;
        padding-left: 24px;
    }

    .header-right {
        color: white;
    }

    .user-greeting {
        margin-right: 15px;
    }

    .logout-btn {
        color: white;
        text-decoration: none;
        padding: 8px 15px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 5px;
        transition: background 0.3s;
        border: none;
        cursor: pointer;
    }

    .logout-btn:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        padding: 30px;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.9);
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .stat-card h3 {
        font-size: 16px;
        margin-bottom: 15px;
        color: #333;
    }

    .stat-number {
        font-size: 36px;
        font-weight: bold;
        color: white;
    }

    .green {
        background: linear-gradient(135deg, #4CAF50, #45a049);
    }

    .orange {
        background: linear-gradient(135deg, #FF9800, #f57c00);
    }

    .blue {
        background: linear-gradient(135deg, #ac0742, #9d1955);
    }

    .teal {
        background: linear-gradient(135deg, #009688, #00796b);
    }

    .red {
        background: linear-gradient(135deg, #F44336, #d32f2f);
    }

    .dark-green {
        background: linear-gradient(135deg, #388E3C, #2e7d32);
    }

    .sales-card {
        background: linear-gradient(135deg, #4CAF50, #45a049);
        grid-column: span 1;
    }

    .sales-amount {
        font-size: 36px;
        font-weight: bold;
        color: white;
        margin-bottom: 10px;
    }

    .sales-target {
        color: rgba(255, 255, 255, 0.8);
        font-size: 14px;
        margin-bottom: 8px;
    }

    .achievement-badge {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        display: inline-block;
        margin-bottom: 10px;
    }

    .progress-bar {
        background: rgba(255, 255, 255, 0.2);
        height: 8px;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill {
        background: white;
        height: 100%;
        transition: width 0.3s;
    }

    .filter-section {
        background: rgba(255, 255, 255, 0.9);
        margin: 0 30px;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .filter-header h2 {
        color: #333;
        font-size: 20px;
    }

    .toggle-filters-btn {
        background: #ac0742;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .toggle-filters-btn:hover {
        background: #9d1955;
    }

    .filter-row {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 15px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        margin-bottom: 5px;
        color: #333;
        font-weight: 500;
    }

    .filter-select,
    .filter-input {
        padding: 10px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .filter-select:focus,
    .filter-input:focus {
        outline: none;
        border-color: #ac0742;
    }

    .filter-actions {
        display: flex;
        gap: 10px;
    }

    .reset-btn,
    .apply-btn {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: background 0.3s;
    }

    .reset-btn {
        background: #f0f0f0;
        color: #333;
    }

    .reset-btn:hover {
        background: #e0e0e0;
    }

    .apply-btn {
        background: #4CAF50;
        color: white;
        text-decoration: none !important;
        box-shadow: none;
    }

    .apply-btn:hover,
    .apply-btn:focus,
    .apply-btn:active {
        background: #45a049;
        text-decoration: none !important;
        box-shadow: none;
        outline: none;
    }


    .data-table-section {
        background: rgba(255, 255, 255, 0.9);
        margin: 0 30px 30px;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        background: #f8f9fa;
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #e0e0e0;
    }

    .data-table td {
        padding: 15px;
        border-bottom: 1px solid #e0e0e0;
    }

    .data-table tr:hover {
        background: rgba(33, 150, 243, 0.05);
    }

    .status-badge {
        background: #FF9800;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-badge.postpone {
        background: #FF9800;
    }

    .count-badge {
        background: #E91E63;
        color: white;
        padding: 4px 8px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 500;
    }

    .action-btn {
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        margin-right: 5px;
        transition: background 0.3s;
    }

    .action-btn.update {
        background: #4CAF50;
        color: white;
    }

    .action-btn.update:hover {
        background: #45a049;
    }

    .action-btn.history {
        background: #ac0742;
        color: white;
    }

    .action-btn.history:hover {
        background: #9d1955;
    }


    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .filter-row {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            padding: 20px;
        }

        .filter-row {
            grid-template-columns: 1fr;
        }

        .dashboard-header {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }

        .main-nav {
            flex-wrap: wrap;
            justify-content: center;
        }

        .data-table-section {
            margin: 0 20px 20px;
        }

        .data-table {
            font-size: 14px;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .data-table-section {
            overflow-x: auto;
        }
    }
</style>
@endsection