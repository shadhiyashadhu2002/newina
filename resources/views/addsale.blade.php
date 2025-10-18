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
        color: #333;
        min-height: 100vh;
    }

    /* Main Dashboard Header */
    .main-header {
        background: linear-gradient(135deg, #ac0742, #9d1955);
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.2);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .header-brand {
        color: white;
        font-size: 24px;
        font-weight: 700;
        text-decoration: none;
    }

    .header-nav {
        display: flex;
        list-style: none;
        gap: 25px;
        align-items: center;
    }

    .header-nav a {
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        font-weight: 500;
        font-size: 15px;
        padding: 10px 16px;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .header-nav a:hover {
        color: white;
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-1px);
    }

    .header-nav a.active {
        color: white;
        background: rgba(255, 255, 255, 0.2);
        font-weight: 600;
    }

    .logout-btn {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .logout-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-1px);
    }

    /* Main Content Area */
    .main-content {
        padding: 30px;
    }

    .page-title {
        color: #fff;
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 25px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    /* Dashboard Cards */
    .dashboard-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .dashboard-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.25);
    }

    .card-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: bold;
        color: white;
    }

    .card-icon.total {
        background: linear-gradient(135deg, #4CAF50, #45a049);
    }

    .card-icon.service {
        background: linear-gradient(135deg, #ac0742, #9d1955);
    }

    .card-icon.normal {
        background: linear-gradient(135deg, #2196F3, #1976D2);
    }

    .card-content h3 {
        font-size: 32px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .card-content p {
        color: #666;
        font-weight: 500;
        font-size: 16px;
    }

    /* Sales Section */
    .sales-section {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e0e0e0;
    }

    .section-header-left {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .section-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #4CAF50, #45a049);
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

    .btn-add {
        background: linear-gradient(135deg, #4CAF50, #45a049);
        color: white;
        padding: 12px 20px;
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

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.6);
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
        text-align: center;
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

    /* Status badges */
    .status-badge {
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.new {
        background: linear-gradient(135deg, #ff9800, #f57c00);
        color: white;
    }

    .status-badge.active {
        background: linear-gradient(135deg, #4caf50, #388e3c);
        color: white;
    }

    .status-badge.completed {
        background: linear-gradient(135deg, #2196f3, #1976d2);
        color: white;
    }

    .status-badge.pending {
        background: linear-gradient(135deg, #ffcc02, #e6b800);
        color: #663c00;
    }

    .status-badge.cancelled {
        background: linear-gradient(135deg, #f44336, #d32f2f);
        color: white;
    }

    .profile-link {
        color: #ac0742;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .profile-link:hover {
        color: #9d1955;
        text-decoration: underline;
    }

    /* Modal styles for Add New Sale popup */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.7);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 2000;
        padding: 20px;
        backdrop-filter: blur(5px);
    }

    .modal-overlay.active { 
        display: flex; 
    }

    .modal-box {
        background: #fff;
        width: 100%;
        max-width: 1000px;
        border-radius: 15px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.4);
        overflow: auto;
        max-height: 90vh;
        position: relative;
        animation: modalSlideIn 0.3s ease-out;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-close-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        background: #fff;
        border: 2px solid #e0e0e0;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        font-size: 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        z-index: 10;
        color: #666;
    }

    .modal-close-btn:hover {
        background: #f44336;
        color: white;
        border-color: #f44336;
        transform: rotate(90deg);
    }

    .modal-content {
        padding: 30px;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .main-header {
            flex-direction: column;
            gap: 15px;
            padding: 20px;
        }

        .header-nav {
            flex-wrap: wrap;
            justify-content: center;
        }

        .dashboard-cards {
            grid-template-columns: 1fr;
        }

        .main-content {
            padding: 20px;
        }

        table {
            font-size: 12px;
        }

        table th, table td {
            padding: 10px 8px;
        }

        .section-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }

        .modal-box {
            max-width: 95%;
            max-height: 95vh;
        }

        .modal-content {
            padding: 20px;
        }
    }
</style>

<!-- Main Dashboard Header -->
<header class="main-header">
    <a href="#" class="header-brand">INA</a>
    
    <nav>
        <ul class="header-nav">
            <li><a href="{{ route('dashboard') }}" class="nav-link">Home</a></li>
            <li><a href="{{ route('profile.hellow') }}" class="nav-link">Profiles</a></li>
            <li><a href="#" class="nav-link">Sales <span class="dropdown-arrow">▼</span></a></li>
            <li><a href="#" class="nav-link">HelpLine</a></li>
            <li><a href="{{ route('fresh.data') }}" class="nav-link">Fresh Data <span class="dropdown-arrow">▼</span></a></li>
            <li><a href="#" class="nav-link">abc</a></li>
            <li><a href="{{ route('services.page') }}" class="nav-link">Services <span class="dropdown-arrow">▼</span></a></li>
            <li><a href="{{ route('addsale.page') }}" class="nav-link active">Accounts <span class="dropdown-arrow">▼</span></a></li>
        </ul>
    </nav>
    
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
    </form>
    <button class="logout-btn" id="logout-btn">Logout</button>
</header>

<!-- Main Content Area -->
<main class="main-content">
    <h1 class="page-title">Sales Dashboard</h1>

    <!-- Dashboard Cards -->
    <div class="dashboard-cards">
        <div class="dashboard-card">
            <div class="card-icon total">💰</div>
            <div class="card-content">
                <h3>{{ $totalSales ?? 0 }}</h3>
                <p>Total Sales</p>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-icon service">🎯</div>
            <div class="card-content">
                <h3>{{ $serviceSales ?? 0 }}</h3>
                <p>Service Sale</p>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-icon normal">📈</div>
            <div class="card-content">
                <h3>{{ $normalSales ?? 0 }}</h3>
                <p>Normal Sale</p>
            </div>
        </div>
    </div>

    <!-- Sales Section -->
    <div class="sales-section">
        <div class="section-header">
            <div class="section-header-left">
                <div class="section-icon">📋</div>
                <h2 class="section-title">Recent Sales</h2>
            </div>
            <a href="#" id="open-add-sale" class="btn-add">+ Add New Sale</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>ID</th>
                    <th>Customer Name</th>
                    <th>Plan</th>
                    <th>Amount</th>
                    <th>Paid Amount</th>
                    <th>Discount</th>
                    <th>Service Executive</th>
                    <th>Status</th>
                    <th>Office</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales ?? [] as $sale)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($sale->date)->format('d-M-Y') }}</td>
                    <td><a href="#" class="profile-link">{{ $sale->profile_id }}</a></td>
                    <td>{{ $sale->name }}</td>
                    <td><strong>{{ $sale->plan }}</strong></td>
                    <td>₹{{ number_format($sale->amount, 2) }}</td>
                    <td>₹{{ number_format($sale->paid_amount ?? 0, 2) }}</td>
                    <td>₹{{ number_format($sale->discount ?? 0, 2) }}</td>
                    <td><span style="color: #ac0742; font-weight: 600;">{{ $sale->executive }}</span></td>
                    <td>
                        <span class="status-badge {{ strtolower($sale->status) }}">
                            {{ ucfirst($sale->status) }}
                        </span>
                    </td>
                    <td>{{ $sale->office }}</td>
                    <td>{{ Str::limit($sale->notes ?? '-', 30) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" style="text-align: center; padding: 20px; color: #666;">
                        No sales records found. Click "Add New Sale" to create your first sale.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Add Sale Modal (Hidden by default) -->
    <div id="add-sale-modal" class="modal-overlay">
        <div class="modal-box">
            <button type="button" class="modal-close-btn" id="close-modal">✕</button>
            <div class="modal-content">
                <div style="margin-bottom: 25px;">
                    <div style="display: flex; align-items: center; gap: 15px; padding-bottom: 15px; border-bottom: 3px solid #ac0742;">
                        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #ac0742, #9d1955); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                            ➕
                        </div>
                        <h2 style="font-size: 24px; font-weight: 700; color: #2c3e50; margin: 0;">Add New Sale</h2>
                    </div>
                </div>

                @if(session('success'))
                <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #28a745;">
                    ✓ {{ session('success') }}
                </div>
                @endif

                @if($errors->any())
                <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #dc3545;">
                    <strong>⚠ Please fix the following errors:</strong>
                    <ul style="margin: 10px 0 0 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('sale.store') }}" method="POST" id="sale-form" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
                    @csrf

                    <div style="display: flex; flex-direction: column;">
                        <label style="font-weight: 600; color: #2c3e50; margin-bottom: 8px; font-size: 14px;">Date<span style="color: #ac0742;">*</span></label>
                        <input type="date" name="date" class="modal-date" value="{{ old('date', date('Y-m-d')) }}" required
                               style="padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 15px;">
                    </div>

                    <div style="display: flex; flex-direction: column;">
                        <label style="font-weight: 600; color: #2c3e50; margin-bottom: 8px; font-size: 14px;">ID<span style="color: #ac0742;">*</span></label>
                        <input type="text" name="profile_id" class="modal-profile-id" value="{{ old('profile_id') }}" placeholder="Enter ID" required
                               style="padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 15px;">
                    </div>

                    <div style="display: flex; flex-direction: column;">
                        <label style="font-weight: 600; color: #2c3e50; margin-bottom: 8px; font-size: 14px;">Customer Name<span style="color: #ac0742;">*</span></label>
                        <input type="text" name="name" class="modal-name" value="{{ old('name') }}" placeholder="Enter Customer Name" required
                               style="padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 15px;">
                    </div>

                    <div style="display: flex; flex-direction: column;">
                        <label style="font-weight: 600; color: #2c3e50; margin-bottom: 8px; font-size: 14px;">Plan<span style="color: #ac0742;">*</span></label>
                        <select name="plan" id="modal-plan-select" required
                                style="padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 15px;">
                            <option value="">Select Plan</option>
                            <option value="Elite">Elite</option>
                            <option value="Assisted">Assisted</option>
                            <option value="Premium">Premium</option>
                            <option value="Basic">Basic</option>
                            <option value="Standard">Standard</option>
                            <option value="Service">Service</option>
                        </select>
                    </div>

                    <div style="display: flex; flex-direction: column;">
                        <label style="font-weight: 600; color: #2c3e50; margin-bottom: 8px; font-size: 14px;">Amount<span style="color: #ac0742;">*</span></label>
                        <input type="number" name="amount" id="modal-amount" value="{{ old('amount') }}" placeholder="0.00" step="0.01" required
                               style="padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 15px;">
                    </div>

                    <div style="display: flex; flex-direction: column;">
                        <label style="font-weight: 600; color: #2c3e50; margin-bottom: 8px; font-size: 14px;">Paid Amount</label>
                        <input type="number" name="paid_amount" id="modal-paid-amount" value="{{ old('paid_amount') }}" placeholder="0.00" step="0.01"
                               style="padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 15px;">
                    </div>

                    <div style="display: flex; flex-direction: column;">
                        <label style="font-weight: 600; color: #2c3e50; margin-bottom: 8px; font-size: 14px;">Discount</label>
                        <input type="number" name="discount" id="modal-discount" value="{{ old('discount') }}" placeholder="0.00" step="0.01"
                               style="padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 15px;">
                    </div>

                    <div style="display: flex; flex-direction: column;">
                        <label style="font-weight: 600; color: #2c3e50; margin-bottom: 8px; font-size: 14px;">Service Executive<span style="color: #ac0742;">*</span></label>
                        <select name="executive" required
                                style="padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 15px;">
                            <option value="">Select Service Executive</option>
                            @forelse($serviceExecutives ?? [] as $executive)
                                <option value="{{ $executive->first_name }}">{{ $executive->first_name }}</option>
                            @empty
                                <option value="">No executives available</option>
                            @endforelse
                        </select>
                    </div>

                    <div style="display: flex; flex-direction: column;">
                        <label style="font-weight: 600; color: #2c3e50; margin-bottom: 8px; font-size: 14px;">Status<span style="color: #ac0742;">*</span></label>
                        <select name="status" required
                                style="padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 15px;">
                            <option value="">Select Status</option>
                            <option value="new">New</option>
                            <option value="active" selected>Active</option>
                            <option value="completed">Completed</option>
                            <option value="pending">Pending</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <div style="display: flex; flex-direction: column;">
                        <label style="font-weight: 600; color: #2c3e50; margin-bottom: 8px; font-size: 14px;">Office<span style="color: #ac0742;">*</span></label>
                        <select name="office" required
                                style="padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 15px;">
                            <option value="">Select Office</option>
                            <option value="Head Office">Head Office</option>
                            <option value="Branch 1">Branch 1</option>
                            <option value="Branch 2">Branch 2</option>
                            <option value="Regional Office">Regional Office</option>
                        </select>
                    </div>

                    <div style="display: flex; flex-direction: column; grid-column: 1 / -1;">
                        <label style="font-weight: 600; color: #2c3e50; margin-bottom: 8px; font-size: 14px;">Note</label>
                        <textarea name="notes" placeholder="Enter any additional notes..." rows="3"
                                  style="padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 15px; resize: vertical;">{{ old('notes') }}</textarea>
                    </div>

                    <div style="grid-column: 1 / -1; display: flex; gap: 15px; justify-content: flex-end; padding-top: 20px; border-top: 2px solid #e0e0e0;">
                        <button type="button" id="cancel-modal" style="padding: 12px 30px; border: none; border-radius: 25px; background: #e0e0e0; color: #333; font-weight: 600; cursor: pointer; transition: all 0.3s ease;">
                            Cancel
                        </button>
                        <button type="submit" style="padding: 12px 30px; border: none; border-radius: 25px; background: linear-gradient(135deg, #ac0742, #9d1955); color: white; font-weight: 600; cursor: pointer; box-shadow: 0 4px 15px rgba(172, 7, 66, 0.4); transition: all 0.3s ease;">
                            💾 Submit Sale
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    // Plan to amount mapping
    const planPrices = {
        'Elite': 18000,
        'Assisted': 12000,
        'Premium': 9000,
        'Basic': 6000,
        'Standard': 4000,
        'Service': 0
    };

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('add-sale-modal');
        const openBtn = document.getElementById('open-add-sale');
        const closeBtn = document.getElementById('close-modal');
        const cancelBtn = document.getElementById('cancel-modal');
        const planSelect = document.getElementById('modal-plan-select');
        const amountInput = document.getElementById('modal-amount');
        const paidInput = document.getElementById('modal-paid-amount');
        const discountInput = document.getElementById('modal-discount');

        function debugLog() {
            // Safe console logging if present
            if (window.console && console.log) console.log.apply(console, arguments);
        }

        if (!modal) {
            debugLog('Add Sale modal element not found');
            return;
        }

        // Open modal
        function openModal() {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent background scroll
        }

        if (openBtn) {
            openBtn.addEventListener('click', function(e) {
                e.preventDefault();
                debugLog('open-add-sale clicked');
                openModal();
            });
        } else {
            debugLog('open-add-sale button not found; attaching fallback to .btn-add elements');
            // Fallback: attach to any .btn-add
            document.querySelectorAll('.btn-add').forEach(function(el) {
                el.addEventListener('click', function(e) { e.preventDefault(); debugLog('fallback btn-add clicked'); openModal(); });
            });
        }

        // Close modal function
        function closeModal() {
            modal.classList.remove('active');
            document.body.style.overflow = ''; // Restore scroll
        }

        // Close handlers
        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', function(e) { if (e.target === modal) closeModal(); });
        document.addEventListener('keydown', function(e) { if (e.key === 'Escape' && modal.classList.contains('active')) closeModal(); });

        // Plan selection logic
        if (planSelect && amountInput) {
            planSelect.addEventListener('change', function() {
                const price = planPrices[this.value] || '';
                amountInput.value = price;
                updateDiscount();
            });
        }

        // Discount calculation
        function updateDiscount() {
            if (amountInput && paidInput && discountInput) {
                const total = parseFloat(amountInput.value) || 0;
                const paid = parseFloat(paidInput.value) || 0;
                discountInput.value = Math.max(0, total - paid);
            }
        }

        if (paidInput) paidInput.addEventListener('input', updateDiscount);
        if (amountInput) amountInput.addEventListener('input', updateDiscount);

        // Logout functionality
        const logoutBtn = document.getElementById('logout-btn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if(confirm('Are you sure you want to logout?')) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    });

    // If there are validation errors, show the modal on page load (blade rendered server-side)
    <?php if(!empty($errors) && $errors->any()): ?>
        (function(){
            var m = document.getElementById('add-sale-modal');
            if (m) {
                m.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        })();
    <?php endif; ?>
</script>

@endsection