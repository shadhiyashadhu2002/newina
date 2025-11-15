@extends('layouts.app')

@section('content')
<style>
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

    .stat-icon.percentage {
        background: linear-gradient(135deg, #ac0742, #9d1955);
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
</style>

<div class="main-content">
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
        <div class="stat-card">
            <div class="stat-icon total">📊</div>
            <div class="stat-content">
                <h3>15</h3>
                <p>Total Staff</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon achieved">✅</div>
            <div class="stat-content">
                <h3>8</h3>
                <p>Targets Achieved</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon pending">⏳</div>
            <div class="stat-content">
                <h3>5</h3>
                <p>In Progress</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon percentage">📈</div>
            <div class="stat-content">
                <h3>73%</h3>
                <p>Overall Achievement</p>
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
            <div class="filter-group">
                <input type="month" id="monthFilter" class="filter-select" value="2025-10" />
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Staff Name</th>
                    <th>Month</th>
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


<script>
// Month filter functionality

document.getElementById('monthFilter').addEventListener('change', function() {
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

function editTarget(id) {
    alert('Edit target for ID: ' + id + '\n\nThis would open a form to edit the target details.');
    // Add your edit functionality here
}

function viewDetails(id) {
    alert('View details for target ID: ' + id + '\n\nThis would show detailed breakdown of achievements.');
    // Add your view details functionality here
}
</script>

@endsection