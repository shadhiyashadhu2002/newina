@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="dashboard-header">
    <div class="header-left">
        <h1 class="dashboard-title">INA</h1>
        <nav class="main-nav">
            <a href="{{ route('dashboard') }}" class="nav-link">Home</a>
            <a href="{{ route('profile.hellow') }}" class="nav-link">Profiles</a>
            <div class="nav-dropdown">
                <a href="{{ route('sales.management') }}" class="nav-link">Sales ▼</a>
            </div>
            <a href="{{ route('helpline.index') }}" class="nav-link">HelpLine</a>
            <a href="{{ route('fresh.data') }}" class="nav-link">Fresh Data</a>
            <a href="#" class="nav-link">abc</a>
            <div class="nav-dropdown">
                <a href="{{ route('services.page') }}" class="nav-link">Services ▼</a>
            </div>
            <div class="nav-dropdown business-dropdown">
                <a href="#" class="nav-link" onclick="event.preventDefault();">Business ▼</a>
                <div class="dropdown-content">
                    <a href="{{ route('addsale.page') }}" class="dropdown-item">Add Sale</a>
                    <a href="{{ route('stafftarget.page') }}" class="dropdown-item active">Staff Target Assign</a>
                    <a href="{{ route('staffproductivity.page') }}" class="dropdown-item">Staff Productivity</a>
                </div>
            </div>
            <div class="nav-dropdown accounts-dropdown">
                <a href="#" class="nav-link" onclick="event.preventDefault();">Accounts ▼</a>
                <div class="dropdown-content">
                    <a href="{{ route('expense.page') }}" class="dropdown-item">Expense Page</a>
                </div>
            </div>
        </nav>
    </div>
    <div class="header-right">
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
            @csrf
        </form>
        <button class="logout-btn" id="logout-btn">Logout</button>
    </div>
</div>

<style>
    /* Navigation Styles */
    .dashboard-header {
        background: linear-gradient(135deg, #c41e56 0%, #e91e63 100%);
        padding: 0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 60px;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 2rem;
        padding-left: 2rem;
    }

    .dashboard-title {
        color: white;
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0;
    }

    .main-nav {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .nav-link {
        color: white;
        text-decoration: none;
        padding: 0.6rem 1rem;
        border-radius: 6px;
        transition: all 0.3s ease;
        font-weight: 500;
        white-space: nowrap;
    }

    .nav-link:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .nav-dropdown {
        position: relative;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background: white;
        min-width: 200px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-radius: 8px;
        margin-top: 0.5rem;
        z-index: 1001;
    }

    .nav-dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown-item {
        color: #333;
        text-decoration: none;
        padding: 0.8rem 1.2rem;
        display: block;
        transition: all 0.2s ease;
    }

    .dropdown-item:hover {
        background: #f0f0f0;
    }

    .dropdown-item.active {
        background: #c41e56;
        color: white;
    }

    .dropdown-item:first-child {
        border-radius: 8px 8px 0 0;
    }

    .dropdown-item:last-child {
        border-radius: 0 0 8px 8px;
    }

    .header-right {
        padding-right: 2rem;
    }

    .logout-btn {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 2px solid white;
        padding: 0.6rem 1.5rem;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .logout-btn:hover {
        background: white;
        color: #c41e56;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
        min-height: 100vh;
    }

    .main-content {
        padding: 30px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .page-title {
        color: #fff;
        font-size: 28px;
        font-weight: 600;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-icon {
        width: 45px;
        height: 45px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .btn-add-target {
        background: linear-gradient(135deg, #4CAF50, #45a049);
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 25px;
        font-weight: 600;
        cursor: pointer;
        font-size: 14px;
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.4);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-add-target:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.6);
    }

    /* Stats Cards */
    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.25);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: white;
    }

    .stat-icon.total {
        background: linear-gradient(135deg, #2196F3, #1976D2);
    }

    .stat-icon.achieved {
        background: linear-gradient(135deg, #4CAF50, #45a049);
    }

    .stat-icon.pending {
        background: linear-gradient(135deg, #FF9800, #F57C00);
    }

    .stat-icon.minimal-sale {
        background: linear-gradient(135deg, #9C27B0, #7B1FA2);
    }

    .stat-icon.percentage {
        background: linear-gradient(135deg, #ac0742, #9d1955);
    }
    .stat-icon.zero-sales {
        background: linear-gradient(135deg, #f44336, #d32f2f);
    }

    .stat-content h3 {
        font-size: 26px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 3px;
    }

    .stat-content p {
        color: #666;
        font-weight: 500;
        font-size: 14px;
    }

    /* Table Container */
    .table-section {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e0e0e0;
    }

    .section-title-wrapper {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .section-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #ac0742, #9d1955);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 600;
        color: #2c3e50;
    }

    .filter-group {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .filter-select {
        padding: 8px 12px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        color: #333;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-select:focus {
        outline: none;
        border-color: #ac0742;
        box-shadow: 0 0 0 3px rgba(172, 7, 66, 0.1);
    }

    /* Table styling */
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border: 1px solid #d0e8f2;
        border-radius: 8px;
        overflow: hidden;
    }

    table th, table td {
        padding: 15px 12px;
        text-align: left;
        vertical-align: middle;
        border-bottom: 1px solid #e0e0e0;
    }

    table th {
        background: linear-gradient(135deg, #f8fbff, #e8f4fd);
        font-weight: 600;
        color: #2c3e50;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #d0e8f2;
    }

    table tbody tr {
        transition: all 0.3s ease;
    }

    table tbody tr:nth-child(even) {
        background: #f8fbff;
    }

    table tbody tr:hover {
        background: #e0f2fe;
        transform: scale(1.01);
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .staff-name {
        font-weight: 600;
        color: #2c3e50;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .staff-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: linear-gradient(135deg, #ac0742, #9d1955);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
    }

    .target-amount {
        font-weight: 600;
        color: #ac0742;
        font-size: 15px;
    }

    .achievement-bar {
        width: 100%;
        height: 8px;
        background: #e0e0e0;
        border-radius: 10px;
        overflow: hidden;
        position: relative;
    }

    .achievement-fill {
        height: 100%;
        background: linear-gradient(135deg, #4CAF50, #45a049);
        border-radius: 10px;
        transition: width 0.5s ease;
    }

    .achievement-text {
        font-size: 12px;
        color: #666;
        margin-top: 5px;
        font-weight: 500;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
    }

    .status-badge.achieved {
        background: linear-gradient(135deg, #d4edda, #4CAF50);
        color: #1b5e20;
        box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
    }

    .status-badge.in-progress {
        background: linear-gradient(135deg, #fff3e0, #FF9800);
        color: #e65100;
        box-shadow: 0 2px 8px rgba(255, 152, 0, 0.3);
    }

    .status-badge.not-started {
        background: linear-gradient(135deg, #f5f5f5, #9e9e9e);
        color: #424242;
        box-shadow: 0 2px 8px rgba(158, 158, 158, 0.3);
    }

    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-edit {
        background: linear-gradient(135deg, #2196F3, #1976D2);
        color: white;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(33, 150, 243, 0.4);
    }

    .btn-delete {
        background: linear-gradient(135deg, #f44336, #d32f2f);
        color: white;
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(244, 67, 54, 0.4);
    }

    .btn-view {
        background: linear-gradient(135deg, #9C27B0, #7B1FA2);
        color: white;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(156, 39, 176, 0.4);
    }

    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border-left: 4px solid #28a745;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .main-content {
            padding: 20px;
        }

        .page-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }

        .stats-cards {
            grid-template-columns: 1fr;
        }

        table {
            font-size: 12px;
        }

        table th, table td {
            padding: 10px 8px;
        }

        .action-buttons {
            flex-direction: column;
        }
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 10000;
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background: white;
        border-radius: 12px;
        padding: 0;
        max-width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    }

    .modal-header {
        background: linear-gradient(135deg, #ac0742, #9d1955);
        color: white;
        padding: 20px;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 1.5rem;
    }

    .close-modal {
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        font-size: 28px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .close-modal:hover {
        background: rgba(255,255,255,0.3);
        transform: rotate(90deg);
    }

    .form-group {
        margin-bottom: 20px;
        padding: 0 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #ac0742;
    }

    .modal-actions {
        padding: 20px;
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        border-top: 1px solid #e0e0e0;
    }

    .btn-submit {
        background: linear-gradient(135deg, #ac0742, #9d1955);
        color: white;
        padding: 10px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(172, 7, 66, 0.4);
    }

    .btn-cancel {
        background: #f0f0f0;
        color: #666;
        padding: 10px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: #e0e0e0;
    }

</style>




<div class="main-content">
<!-- Page Version: 1765515527 -->
<input type="hidden" id="page_version" value="1765515527">

    <div class="page-header">
        <h1 class="page-title">
            <span class="page-icon">🎯</span>
            Staff Target Assignment
        </h1>
        <button class="btn-add-target" onclick="openAddTargetModal()">
            ➕ Assign New Target
        </button>
    </div>

    <!-- Add Target Modal (hidden by default) -->
    <div id="addTargetModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:#fff; border-radius:12px; padding:32px; min-width:350px; max-width:95vw; box-shadow:0 8px 32px rgba(0,0,0,0.25);">
            <h2 style="margin-bottom:18px; color:#ac0742;">Assign New Target</h2>
            <form method="POST" action="{{ route('stafftarget.assign') }}">
                @csrf
                <div style="margin-bottom:16px;">
                    <label style="font-weight:600; color:#2c3e50;">Service Executive<span style="color:#ac0742;">*</span></label>
                    <select name="service_executive" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #e0e0e0;">
                        <option value="">Select Service Executive</option>
                        @if(isset($staffUsers) && $staffUsers->count() > 0)
                            @foreach($staffUsers as $staff)
                                <option value="{{ $staff->id }}" {{ (Auth::id() == $staff->id) ? 'selected' : '' }}>{{ $staff->first_name }}</option>
                            @endforeach
                        @else
                            <option value="{{ Auth::id() ?? 0 }}" selected>{{ Auth::user()->first_name ?? 'admin' }}</option>
                        @endif
                    </select>
                </div>
                <div style="margin-bottom:16px;">
                    <label style="font-weight:600; color:#2c3e50;">Month<span style="color:#ac0742;">*</span></label>
                    <input type="month" name="month" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #e0e0e0;">
                </div>
                <div style="margin-bottom:16px;">
                    <label style="font-weight:600; color:#2c3e50;">Branch<span style="color:#ac0742;">*</span></label>
                    <select name="branch" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #e0e0e0;">
                        <option value="">Select Branch</option>
                        <option value="Tirur">Tirur</option>
                        <option value="Vadakara">Vadakara</option>
                    </select>
                </div>
                <div style="margin-bottom:16px;">
                    <label style="font-weight:600; color:#2c3e50;">Department<span style="color:#ac0742;">*</span></label>
                    <select name="department" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #e0e0e0;">
                        <option value="">Select Department</option>
                        <option value="Sales 1">Sales 1</option>
                        <option value="Sales 2">Sales 2</option>
                        <option value="Service Muslims">Service Muslims</option>
                        <option value="Service Hindu">Service Hindu</option>
                        <option value="Service Christian">Service Christian</option>
                        <option value="Backend/Helpline">Backend/Helpline</option>
                        <option value="Retail">Retail</option>
                        <option value="Elite">Elite</option>
                    </select>
                </div>
                <div style="margin-bottom:16px;">
                    <label style="font-weight:600; color:#2c3e50;">Target Amount<span style="color:#ac0742;">*</span></label>
                    <input type="number" name="target_amount" min="0" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #e0e0e0;">
                </div>
                <div style="display:flex; gap:12px; justify-content:flex-end;">
                    <button type="button" onclick="closeAddTargetModal()" style="padding:10px 22px; border-radius:8px; border:none; background:#e0e0e0; color:#333; font-weight:600;">Cancel</button>
                    <button type="submit" style="padding:10px 22px; border-radius:8px; border:none; background:linear-gradient(135deg,#ac0742,#9d1955); color:#fff; font-weight:600;">Assign</button>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        <span>✓</span> {{ session('success') }}
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="stats-cards">
        <div class="stat-card" onclick="showStatsModal('total')" style="cursor: pointer;">
            <div class="stat-icon total">📊</div>
            <div class="stat-content">
                <h3>{{ $totalStaff }}</h3>
                <p>Total Staff</p>
            </div>
        </div>

        <div class="stat-card" onclick="showStatsModal('achieved')" style="cursor: pointer;">
            <div class="stat-icon achieved">✅</div>
            <div class="stat-content">
                <h3>{{ $targetsAchieved }}</h3>
                <p>Targets Achieved</p>
            </div>
        </div>

        <div class="stat-card" onclick="showStatsModal('inProgress')" style="cursor: pointer;">
            <div class="stat-icon pending">⏳</div>
            <div class="stat-content">
                <h3>{{ $inProgress }}</h3>
                <p>In Progress</p>
            </div>
        </div>

        <div class="stat-card" onclick="showStatsModal('minimalSale')" style="cursor: pointer;">
            <div class="stat-icon minimal-sale">📉</div>
            <div class="stat-content">
                <h3>{{ $minimalSale }}</h3>
                <p>Minimal Sale</p>
            </div>
        </div>

        <div class="stat-card" onclick="showStatsModal('zeroSales')" style="cursor: pointer;">
            <div class="stat-icon zero-sales">❌</div>
            <div class="stat-content">
                <h3>{{ $zeroSale }}</h3>
                <p>Zero Sales</p>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="table-section">
        <div class="section-header">
            <div class="section-title-wrapper">
                <div class="section-icon">📋</div>
                <h2 class="section-title">Staff Targets Overview</h2>
                </div>
        <form method="GET" action="{{ route('stafftarget.page') }}" style="margin-bottom: 20px;">
            <div style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; font-size: 14px; font-weight: 500; color: #555; margin-bottom: 5px;">Staff Name</label>
                    <input type="text" name="staff_name" value="{{ request('staff_name') }}" placeholder="Search by name" style="width: 100%; padding: 10px 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; font-size: 14px; font-weight: 500; color: #555; margin-bottom: 5px;">Month</label>
                    <input type="month" name="month" value="{{ request('month') }}" style="width: 100%; padding: 10px 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px;">
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; font-size: 14px; font-weight: 500; color: #555; margin-bottom: 5px;">Department</label>
                    <select name="department" style="width: 100%; padding: 10px 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; background: white;">
                        <option value="">All Departments</option>
                        <option value="Sales 1" {{ request('department') == 'Sales 1' ? 'selected' : '' }}>Sales 1</option>
                        <option value="Sales 2" {{ request('department') == 'Sales 2' ? 'selected' : '' }}>Sales 2</option>
                        <option value="Service Muslims" {{ request('department') == 'Service Muslims' ? 'selected' : '' }}>Service Muslims</option>
                        <option value="Service Hindu" {{ request('department') == 'Service Hindu' ? 'selected' : '' }}>Service Hindu</option>
                        <option value="Service Christian" {{ request('department') == 'Service Christian' ? 'selected' : '' }}>Service Christian</option>
                        <option value="Backend/Helpline" {{ request('department') == 'Backend/Helpline' ? 'selected' : '' }}>Backend/Helpline</option>
                        <option value="Retail" {{ request('department') == 'Retail' ? 'selected' : '' }}>Retail</option>
                        <option value="Elite" {{ request('department') == 'Elite' ? 'selected' : '' }}>Elite</option>
                    </select>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" style="padding: 10px 24px; background: linear-gradient(135deg, #ac0742, #9d1955); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px;">Filter</button>
                    <a href="{{ route('stafftarget.page') }}" style="padding: 10px 24px; background: #f0f0f0; color: #666; border: none; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-block; font-size: 14px;">Reset</a>
                </div>
            </div>
        </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Staff Name</th>
                    <th>Month</th>
                    <th>Department</th>
                    <th>Target Amount</th>
                    <th>Achieved</th>
                    <th>Balance</th>
                    <th>Achievement %</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($prepared) && $prepared->count() > 0)
                    @foreach($prepared as $index => $row)
                        <tr>
                            <td>
                                <div class="staff-name">
                                    <div class="staff-avatar">{{ strtoupper(substr($row->staff_first_name ?? 'U',0,1)) }}{{ strtoupper(substr($row->staff_first_name ?? 'U', -1)) }}</div>
                                    <span>{{ $row->staff_first_name ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($row->month . '-01')->format('F Y') }}</td>
                            <td>{{ $row->department ?? "N/A" }}</td>
                            <td><span class="target-amount">₹{{ number_format($row->target_amount, 2) }}</span></td>
                            <td>₹{{ number_format($row->achieved, 2) }}</td>
                            <td><span class="target-amount">₹{{ number_format(($row->target_amount - $row->achieved), 2) }}</span></td>
                            <td>
                                <div class="achievement-bar">
                                    <div class="achievement-fill" style="width: {{ max(0, min(100, $row->percentage)) }}%;"></div>
                                </div>
                                <div class="achievement-text">{{ $row->percentage }}%</div>
                            </td>
                            <td><span class="status-badge {{ $row->status == 'achieved' ? 'achieved' : 'in-progress' }}">{{ $row->status == 'achieved' ? 'Achieved' : 'In Progress' }}</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-edit" onclick="editTarget({{ $row->id }})">✏️ Edit</button>
                                    <button class="btn-action btn-view" onclick="viewDetails({{ $row->id }})">👁️ View</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" style="text-align:center; padding:30px; color:#666;">No targets assigned yet.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>


<!-- Statistics Details Modal -->
<div id="statsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; border-radius: 15px; width: 90%; max-width: 800px; max-height: 80vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,0.3);">
        <div style="background: linear-gradient(135deg, #ac0742, #9d1955); color: white; padding: 20px; border-radius: 15px 15px 0 0; display: flex; justify-content: space-between; align-items: center;">
            <h2 id="modalTitle" style="margin: 0; font-size: 24px;">Staff Details</h2>
            <button onclick="closeStatsModal()" style="background: rgba(255,255,255,0.2); border: none; color: white; font-size: 24px; width: 35px; height: 35px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center;">&times;</button>
        </div>
        <div id="modalContent" style="padding: 25px;">
            <!-- Content will be populated by JavaScript -->
        </div>
    </div>
</div>


<!-- Edit Target Modal -->
<div id="editTargetModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h2>Edit Staff Target</h2>
            <button class="close-modal" onclick="closeEditModal()">&times;</button>
        </div>
        <form id="editTargetForm">
            <input type="hidden" id="edit_target_id" name="target_id">
            
            <div class="form-group">
                <label>Staff Member</label>
                <p id="edit_staff_name_display" style="padding: 10px; background: #f5f5f5; border-radius: 6px; margin: 5px 0;"></p>
                <input type="hidden" id="edit_staff_id" name="staff_id">
            </div>

            <div class="form-group">
                <label for="edit_month">Month *</label>
                <input type="month" id="edit_month" name="month" required>
            </div>

            <div class="form-group">
                <label for="edit_department">Department</label>
                <input type="text" id="edit_department" name="department" placeholder="Enter department">
            </div>

            <div class="form-group">
                <label for="edit_target_amount">Target Amount *</label>
                <input type="number" id="edit_target_amount" name="target_amount" step="0.01" min="0" required placeholder="Enter target amount">
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancel</button>
                <button type="button" class="btn-submit" onclick="submitEditForm()">Update Target</button>
            </div>
        </form>
    </div>
</div>

<!-- View Target Details Modal -->
<div id="viewTargetModal" class="modal">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h2>Target Details</h2>
            <button class="close-modal" onclick="closeViewModal()">&times;</button>
        </div>
        
        <div style="padding: 20px;">
            <!-- Staff Information -->
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 12px; margin-bottom: 20px;">
                <h3 style="margin: 0 0 10px 0; font-size: 1.5rem;" id="view_staff_name">-</h3>
                <p style="margin: 0; opacity: 0.9;" id="view_staff_email">-</p>
            </div>

            <!-- Target Overview Cards -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 25px;">
                <div style="background: #f8f9fa; padding: 15px; border-radius: 10px; border-left: 4px solid #667eea;">
                    <div style="color: #666; font-size: 0.85rem; margin-bottom: 5px;">Month</div>
                    <div style="font-size: 1.1rem; font-weight: 600;" id="view_month">-</div>
                </div>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 10px; border-left: 4px solid #f093fb;">
                    <div style="color: #666; font-size: 0.85rem; margin-bottom: 5px;">Department</div>
                    <div style="font-size: 1.1rem; font-weight: 600;" id="view_department">-</div>
                </div>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 10px; border-left: 4px solid #4facfe;">
                    <div style="color: #666; font-size: 0.85rem; margin-bottom: 5px;">Branch</div>
                    <div style="font-size: 1.1rem; font-weight: 600;" id="view_branch">-</div>
                </div>
            </div>

            <!-- Financial Summary -->
            <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; border-radius: 12px; margin-bottom: 20px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
                    <div>
                        <div style="opacity: 0.9; font-size: 0.9rem; margin-bottom: 5px;">Target Amount</div>
                        <div style="font-size: 1.3rem; font-weight: 700;" id="view_target_amount">₹0.00</div>
                    </div>
                    <div>
                        <div style="opacity: 0.9; font-size: 0.9rem; margin-bottom: 5px;">Achieved</div>
                        <div style="font-size: 1.3rem; font-weight: 700;" id="view_achieved">₹0.00</div>
                    </div>
                    <div>
                        <div style="opacity: 0.9; font-size: 0.9rem; margin-bottom: 5px;">Balance</div>
                        <div style="font-size: 1.3rem; font-weight: 700;" id="view_balance">₹0.00</div>
                    </div>
                </div>
            </div>

            <!-- Progress Section -->
            <div style="margin-bottom: 25px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <span style="font-weight: 600; color: #333;">Achievement Progress</span>
                    <span style="font-weight: 700; font-size: 1.2rem; color: #667eea;" id="view_percentage">0%</span>
                </div>
                <div style="background: #e0e0e0; height: 30px; border-radius: 15px; overflow: hidden; position: relative;">
                    <div class="achievement-bar" style="height: 100%; background: linear-gradient(90deg, #667eea 0%, #764ba2 100%); transition: width 0.3s ease; width: 0%;"></div>
                </div>
                <div style="margin-top: 10px; text-align: center;">
                    <span id="view_status" class="status-badge in-progress">In Progress</span>
                </div>
            </div>

            <!-- Sales Information -->
            <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h3 style="margin: 0; color: #333;">Sales Breakdown</h3>
                    <span style="background: #667eea; color: white; padding: 5px 15px; border-radius: 20px; font-weight: 600;" id="view_total_sales">0</span>
                </div>
                
                <div id="no_sales_message" style="text-align: center; padding: 20px; color: #666;">
                    No sales recorded for this period
                </div>
                
                <div style="overflow-x: auto;">
                    <table id="sales_table" style="width: 100%; display: none; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #667eea; color: white;">
                                <th style="padding: 12px; text-align: left; border-radius: 8px 0 0 0;">#</th>
                                <th style="padding: 12px; text-align: left;">Customer Name</th>
                                <th style="padding: 12px; text-align: right;">Amount</th>
                                <th style="padding: 12px; text-align: left; border-radius: 0 8px 0 0;">Date</th>
                            </tr>
                        </thead>
                        <tbody id="sales_table_body">
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Additional Info -->
            <div style="text-align: center; color: #666; font-size: 0.85rem; padding-top: 15px; border-top: 1px solid #e0e0e0;">
                <p style="margin: 0;">Created on: <span id="view_created_at">-</span></p>
            </div>
        </div>
        
        <div class="modal-actions">
            <button type="button" class="btn-cancel" onclick="closeViewModal()">Close</button>
        </div>
    </div>
</div>



<script>
// Month filter functionality

const monthFilter = document.getElementById('monthFilter');
if (monthFilter) {
    monthFilter.addEventListener('change', function() {
        const selectedMonth = this.value;
        const rows = document.querySelectorAll('table tbody tr');
        rows.forEach(row => {
            const monthCell = row.cells[1].textContent;
            if (!selectedMonth || monthCell.includes(getMonthName(selectedMonth))) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
}
 
function getMonthName(monthValue) {
    const months = {
        '2025-01': 'January 2025',
        '2025-02': 'February 2025',
        '2025-03': 'March 2025',
        '2025-04': 'April 2025',
        '2025-05': 'May 2025',
        '2025-06': 'June 2025',
        '2025-07': 'July 2025',
        '2025-08': 'August 2025',
        '2025-09': 'September 2025',
        '2025-10': 'October 2025',
        '2025-11': 'November 2025',
        '2025-12': 'December 2025'
    };
    return months[monthValue] || '';
}

function openAddTargetModal() {
    document.getElementById('addTargetModal').style.display = 'flex';
}
function closeAddTargetModal() {
    document.getElementById('addTargetModal').style.display = 'none';
}

window.editTarget = function(id) {
    fetch(`/staff-target/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            console.log("Data received:", data);
            document.getElementById('edit_target_id').value = data.id;
            document.getElementById('edit_staff_id').value = data.staff_id;
            document.getElementById('edit_month').value = data.month;
            document.getElementById('edit_department').value = data.department;
            document.getElementById('edit_target_amount').value = data.target_amount;
            document.getElementById('edit_staff_name_display').textContent = data.staff_name;
            const modal = document.getElementById('editTargetModal');
            console.log("Modal element:", modal);
            console.log("Setting display to flex");
            modal.style.display = 'flex';
            console.log("Display is now:", modal.style.display);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load target details');
        });
}

window.viewDetails = function(id) {
    fetch(`/staff-target/${id}/view`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('view_staff_name').textContent = data.staff_name;
            document.getElementById('view_staff_email').textContent = data.staff_email;
            document.getElementById('view_month').textContent = data.month;
            document.getElementById('view_department').textContent = data.department;
            document.getElementById('view_branch').textContent = data.branch;
            document.getElementById('view_target_amount').textContent = '₹' + parseFloat(data.target_amount).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('view_achieved').textContent = '₹' + parseFloat(data.achieved).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('view_balance').textContent = '₹' + parseFloat(data.balance).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('view_percentage').textContent = data.percentage + '%';
            document.getElementById('view_status').textContent = data.status;
            document.getElementById('view_total_sales').textContent = data.total_sales;
            document.getElementById('view_created_at').textContent = data.created_at;
            
            // Update progress bar
            const progressBar = document.querySelector('#viewTargetModal .achievement-bar');
            progressBar.style.width = data.percentage + '%';
            
            // Update status badge
            const statusBadge = document.getElementById('view_status');
            statusBadge.className = 'status-badge ' + (data.status === 'Achieved' ? 'achieved' : 'in-progress');
            
            // Populate sales table
            const salesTableBody = document.getElementById('sales_table_body');
            if (data.sales.length > 0) {
                let salesHtml = '';
                data.sales.forEach((sale, index) => {
                    salesHtml += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${sale.customer_name}</td>
                            <td>₹${parseFloat(sale.amount).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                            <td>${sale.date}</td>
                        </tr>
                    `;
                });
                salesTableBody.innerHTML = salesHtml;
                document.getElementById('no_sales_message').style.display = 'none';
                document.getElementById('sales_table').style.display = 'table';
            } else {
                document.getElementById('sales_table').style.display = 'none';
                document.getElementById('no_sales_message').style.display = 'block';
            }
            
            document.getElementById('viewTargetModal').style.display = 'flex';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load target details');
        });
}
// Statistics Modal Functions
function showStatsModal(type) {
    const modal = document.getElementById('statsModal');
    const title = document.getElementById('modalTitle');
    const content = document.getElementById('modalContent');
    
    // Get the prepared data from the table
    const tableRows = document.querySelectorAll('tbody tr');
    let filteredData = [];
    
    tableRows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length > 0) {
            const staffName = cells[0]?.textContent.trim();
            const monthText = cells[1]?.textContent.trim();
            const department = cells[2]?.textContent.trim();
            const targetAmount = cells[3]?.textContent.trim();
            const achievedAmount = cells[4]?.textContent.trim();
            const balanceAmount = cells[5]?.textContent.trim();
            const percentageText = cells[6]?.textContent?.trim() || "0%";
            const status = cells[7]?.textContent?.trim() || "In Progress";
            const percentage = parseFloat((percentageText || "0%").replace("%", "") || 0);
            const achieved = parseFloat(achievedAmount?.replace(/[^0-9.-]/g, "") || 0);
            
            let include = false;
            if (type === 'total') {
                include = true;
            } else if (type === 'achieved' && percentage >= 75) {
                include = true;
            } else if (type === 'inProgress' && percentage >= 25 && percentage < 75) {
                include = true;
            } else if (type === 'minimalSale' && percentage > 0 && percentage < 25 && achieved > 0) {
                include = true;
            } else if (type === 'zeroSales' && achieved === 0 && percentage === 0) {
                include = true;
            }
            
            if (include) {
                filteredData.push({
                    name: staffName,
                    month: monthText,
                    department: department,
                    target: targetAmount,
                    achieved: achievedAmount,
                    balance: balanceAmount,
                    percentage: percentageText,
                    status: status
                });
            }
        }
    });
    
    // Set modal title
    const titles = {
        'total': '📊 Total Staff Members',
        'achieved': '✅ Targets Achieved (75%+)',
        'inProgress': '⏳ In Progress (25% - 75%)',
        'minimalSale': '📉 Minimal Sale (1% - 24%)',
        'zeroSales': '❌ Zero Sales Staff'
    };
    title.textContent = titles[type] || 'Staff Details';
    
    // Generate content
    if (filteredData.length === 0) {
        content.innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">No staff members found in this category.</p>';
    } else {
        let html = '<div style="overflow-x: auto;"><table style="width: 100%; border-collapse: collapse;">';
        html += '<thead><tr style="background: #f5f5f5;">';
        html += '<th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Staff Name</th>';
        html += '<th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Month</th>';
        html += '<th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Department</th>';
        html += '<th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Target</th>';
        html += '<th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Achieved</th>';
        html += '<th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Percentage</th>';
        html += '<th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Status</th>';
        html += '</tr></thead><tbody>';
        
        filteredData.forEach((item, index) => {
            const bgColor = index % 2 === 0 ? '#fff' : '#f9f9f9';
            html += `<tr style="background: ${bgColor};">`;
            html += `<td style="padding: 12px; border-bottom: 1px solid #eee;">${item.name}</td>`;
            html += `<td style="padding: 12px; border-bottom: 1px solid #eee;">${item.month}</td>`;
            html += `<td style="padding: 12px; border-bottom: 1px solid #eee;">${item.department}</td>`;
            html += `<td style="padding: 12px; border-bottom: 1px solid #eee;">${item.target}</td>`;
            html += `<td style="padding: 12px; border-bottom: 1px solid #eee;">${item.achieved}</td>`;
            html += `<td style="padding: 12px; border-bottom: 1px solid #eee;">${item.percentage}</td>`;
            html += `<td style="padding: 12px; border-bottom: 1px solid #eee;">${item.status}</td>`;
            html += '</tr>';
        });
        html += '</tbody></table></div>';
        html += `<div style="margin-top: 20px; padding: 15px; background: #f0f0f0; border-radius: 8px; text-align: center;">`;
        html += `<strong>Total: ${filteredData.length} staff member(s)</strong>`;
        html += '</div>';
        content.innerHTML = html;
    }
    
    modal.style.display = 'flex';
}

window.closeEditModal = function() {
    document.getElementById('editTargetModal').style.display = 'none';
}

window.closeViewModal = function() {
    document.getElementById('viewTargetModal').style.display = 'none';
}

window.submitEditForm = function() {
    const form = document.getElementById('editTargetForm');
    const formData = new FormData(form);
    const id = document.getElementById('edit_target_id').value;
    
    // Convert FormData to JSON
    const data = {};
    formData.forEach((value, key) => {
        data[key] = value;
    });
    fetch(`/staff-target/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closeEditModal();
            location.reload();
        } else {
            alert(data.message || 'Failed to update target');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update target');
    });
}

function closeStatsModal() {
    document.getElementById('statsModal').style.display = 'none';
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('statsModal');
    if (event.target === modal) {
        closeStatsModal();
    }
});
</script>

@endsection
