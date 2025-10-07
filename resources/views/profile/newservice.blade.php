<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>INA Dashboard - New Services</title>
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

    .header-nav li {
      position: relative;
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

    .header-nav .dropdown-arrow {
      font-size: 10px;
      margin-left: 3px;
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

    /* Add Service Button Styling */
    .add-service-btn-beautiful {
      background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
      color: white;
      border: none;
      padding: 15px 30px;
      border-radius: 50px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
      display: flex;
      align-items: center;
      gap: 10px;
      position: relative;
      overflow: hidden;
    }

    .add-service-btn-beautiful:before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
      transition: left 0.5s;
    }

    .add-service-btn-beautiful:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 35px rgba(102, 126, 234, 0.6);
    }

    .add-service-btn-beautiful:hover:before {
      left: 100%;
    }

    .add-service-btn-beautiful:active {
      transform: translateY(-1px);
    }

    /* Modal Overlay */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      backdrop-filter: blur(5px);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 2000;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
    }

    .modal-overlay.active {
      opacity: 1;
      visibility: visible;
    }

    /* Modal Content */
    .modal-content-beautiful {
      background: white;
      padding: 40px;
      border-radius: 20px;
      width: 90%;
      max-width: 700px;
      max-height: 90vh;
      overflow-y: auto;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      transform: translateY(-30px) scale(0.95);
      transition: all 0.3s ease;
      position: relative;
    }

    .modal-overlay.active .modal-content-beautiful {
      transform: translateY(0) scale(1);
    }

    .modal-content-beautiful h2 {
      color: #2c3e50;
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 30px;
      text-align: center;
      position: relative;
      padding-bottom: 15px;
    }

    .modal-content-beautiful h2:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 3px;
      background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
      border-radius: 2px;
    }

    /* Form Styling */
    .modal-form-row {
      display: flex;
      gap: 20px;
      margin-bottom: 25px;
    }

    .modal-form-group {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .modal-form-group label {
      color: #34495e;
      font-weight: 600;
      margin-bottom: 8px;
      font-size: 14px;
      letter-spacing: 0.3px;
    }

    .modal-form-group input,
    .modal-form-group select {
      padding: 15px;
      border: 2px solid #e8ecef;
      border-radius: 12px;
      font-size: 15px;
      transition: all 0.3s ease;
      background: #f8f9fa;
      color: #2c3e50;
    }

    .modal-form-group input:focus,
    .modal-form-group select:focus {
      outline: none;
      border-color: #ac0742;
      background: white;
      box-shadow: 0 0 0 3px rgba(172, 7, 66, 0.1);
      transform: translateY(-1px);
    }

    .modal-form-group select {
      cursor: pointer;
    }

    /* Modal Actions */
    .modal-actions {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-top: 35px;
      padding-top: 25px;
      border-top: 1px solid #e8ecef;
    }

    .btn {
      padding: 15px 30px;
      border: none;
      border-radius: 50px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      min-width: 120px;
      position: relative;
      overflow: hidden;
    }

    .btn-primary {
      background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
      color: white;
      box-shadow: 0 5px 15px rgba(172, 7, 66, 0.4);
    }

    .btn-primary:before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
      transition: left 0.5s;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
    }

    .btn-primary:hover:before {
      left: 100%;
    }

    .btn-secondary {
      background: #95a5a6;
      color: white;
      box-shadow: 0 5px 15px rgba(149, 165, 166, 0.4);
    }

    .btn-secondary:hover {
      background: #7f8c8d;
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(149, 165, 166, 0.6);
    }

    /* Table Container */
    .table-container {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      margin: 0 auto;
      overflow-x: auto;
    }

    .table-title {
      color: #2c3e50;
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 25px;
      text-align: center;
      border-bottom: 2px solid #e0e0e0;
      padding-bottom: 15px;
    }

    /* Table Styling */
    .services-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    .services-table th {
      background: linear-gradient(135deg, #ac0742, #9d1955);
      color: white;
      text-align: left;
      padding: 15px;
      font-weight: 600;
      border-right: 1px solid rgba(255, 255, 255, 0.2);
    }

    .services-table th:last-child {
      border-right: none;
    }

    .services-table td {
      padding: 15px;
      border-bottom: 1px solid #e0e0e0;
      color: #2c3e50;
    }

    .services-table tr:nth-child(even) {
      background-color: rgba(74, 105, 189, 0.05);
    }

    .services-table tr:hover {
      background-color: rgba(74, 105, 189, 0.1);
    }

    /* Serial Number column styling */
    .services-table th:first-child,
    .services-table td:first-child {
      width: 60px;
      text-align: center;
      font-weight: 600;
    }

    .services-table td:first-child {
      color: #ac0742;
      font-size: 14px;
    }

    .action-link {
      color: #ac0742;
      text-decoration: none;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
    }

    .action-link:before {
      content: "○";
      margin-right: 5px;
      font-size: 10px;
      color: #9d1955;
    }

    .action-link:hover {
      color: #9d1955;
      text-decoration: underline;
    }

    .no-access {
      color: #dc3545;
      font-style: italic;
      font-size: 12px;
      font-weight: 500;
    }

    /* Responsive design */
    @media (max-width: 768px) {
      .main-content {
        padding: 20px;
      }
      
      .table-container {
        padding: 20px;
      }
      
      .services-table {
        font-size: 14px;
      }
      
      .services-table th,
      .services-table td {
        padding: 10px;
      }

      /* Adjust Sl No column for mobile */
      .services-table th:first-child,
      .services-table td:first-child {
        width: 40px;
        font-size: 12px;
      }

      .modal-content-beautiful {
        padding: 25px;
        margin: 20px;
      }

      .modal-form-row {
        flex-direction: column;
        gap: 15px;
      }

      .add-service-btn-beautiful {
        padding: 12px 24px;
        font-size: 14px;
      }
    }

    /* Custom Pagination Styles */
    .pagination-wrapper {
      margin-top: 40px;
      padding: 25px 0;
      border-top: 2px solid #f0f0f0;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 20px;
    }

    .pagination-info {
      display: flex;
      align-items: center;
    }

    .pagination-text {
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      color: #495057;
      padding: 10px 16px;
      border-radius: 25px;
      font-size: 14px;
      font-weight: 500;
      border: 1px solid #dee2e6;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .custom-pagination {
      display: flex;
      align-items: center;
    }

    .pagination-container {
      display: flex;
      align-items: center;
      gap: 8px;
      background: white;
      padding: 8px;
      border-radius: 50px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      border: 1px solid #e9ecef;
    }

    .pagination-btn {
      display: flex;
      align-items: center;
      gap: 6px;
      padding: 10px 16px;
      border-radius: 25px;
      text-decoration: none;
      font-weight: 600;
      font-size: 14px;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
      position: relative;
      overflow: hidden;
    }

    .pagination-btn-active {
      background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
      color: white;
      box-shadow: 0 4px 8px rgba(172, 7, 66, 0.3);
    }

    .pagination-btn-active:hover {
      background: linear-gradient(135deg, #9d1955 0%, #ac0742 100%);
      transform: translateY(-1px);
      box-shadow: 0 6px 12px rgba(172, 7, 66, 0.4);
      color: white;
      text-decoration: none;
    }

    .pagination-btn-disabled {
      background: #f8f9fa;
      color: #adb5bd;
      cursor: not-allowed;
    }

    .pagination-icon {
      font-size: 16px;
      font-weight: bold;
    }

    .pagination-numbers {
      display: flex;
      align-items: center;
      gap: 4px;
      margin: 0 8px;
    }

    .pagination-number {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      text-decoration: none;
      font-weight: 600;
      font-size: 14px;
      transition: all 0.3s ease;
      background: #f8f9fa;
      color: #495057;
      border: 1px solid #dee2e6;
      position: relative;
    }

    .pagination-number:hover {
      background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
      color: white;
      transform: scale(1.1);
      box-shadow: 0 4px 8px rgba(172, 7, 66, 0.3);
      text-decoration: none;
    }

    .pagination-number-current {
      background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
      color: white;
      box-shadow: 0 4px 8px rgba(172, 7, 66, 0.4);
      transform: scale(1.05);
    }

    /* Responsive Pagination */
    @media (max-width: 768px) {
      .pagination-wrapper {
        flex-direction: column;
        text-align: center;
        gap: 15px;
      }
      
      .pagination-container {
        padding: 6px;
        gap: 4px;
      }
      
      .pagination-btn {
        padding: 8px 12px;
        font-size: 12px;
      }
      
      .pagination-btn span {
        display: none;
      }
      
      .pagination-number {
        width: 35px;
        height: 35px;
        font-size: 12px;
      }
      
      .pagination-text {
        padding: 8px 12px;
        font-size: 12px;
      }
    }

    /* Pagination Animation Effects */
    .pagination-container {
      animation: fadeInUp 0.5s ease-out;
    }

    .pagination-btn-active::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
      transition: left 0.5s;
    }

    .pagination-btn-active:hover::before {
      left: 100%;
    }

    .pagination-number::after {
      content: '';
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      border-radius: 50%;
      background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
      opacity: 0;
      transform: scale(0.8);
      transition: all 0.3s ease;
      z-index: -1;
    }

    .pagination-number:hover::after {
      opacity: 1;
      transform: scale(1);
    }

    /* Pulse animation for current page */
    .pagination-number-current {
      animation: pulse 2s infinite;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes pulse {
      0% {
        box-shadow: 0 4px 8px rgba(172, 7, 66, 0.4);
      }
      50% {
        box-shadow: 0 4px 8px rgba(172, 7, 66, 0.7), 0 0 0 8px rgba(172, 7, 66, 0.1);
      }
      100% {
        box-shadow: 0 4px 8px rgba(172, 7, 66, 0.4);
      }
    }

    /* Per page dropdown styling */
    #perPageSelect {
      background-color: white;
      border: 1px solid #ddd;
      border-radius: 4px;
      padding: 6px 12px;
      font-size: 14px;
      color: #495057;
      cursor: pointer;
      transition: border-color 0.15s ease-in-out;
    }

    #perPageSelect:focus {
      outline: none;
      border-color: #ac0742;
      box-shadow: 0 0 0 0.2rem rgba(172, 7, 66, 0.25);
    }
  </style>
</head>
<body>

  <!-- Main Dashboard Header -->
  <header class="main-header">
    <a href="#" class="header-brand">INA</a>
    <nav>
      <ul class="header-nav">
        <li><a href="{{ route('dashboard') }}">Home</a></li>
        <li><a href="{{ route('profile.hellow') }}">Profiles</a></li>
        <li><a href="#">Sales <span class="dropdown-arrow">▼</span></a></li>
        <li><a href="#">HelpLine</a></li>
        <li><a href="{{ route('fresh.data') }}">Fresh Data <span class="dropdown-arrow">▼</span></a></li>
        <li><a href="#">abc</a></li>
        <li><a href="{{ route('services.page') }}" class="active">Services <span class="dropdown-arrow">▼</span></a></li>
      </ul>
    </nav>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
      @csrf
    </form>
    <button class="logout-btn" id="logout-btn">Logout</button>
  </header>

  <!-- Main Content Area -->
  <main class="main-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
      <h1 class="page-title">New Services</h1>
      @if(Auth::check() && Auth::user()->is_admin)
        <button id="add-new-service-btn" class="add-service-btn-beautiful">
          <span>✨</span> Add New Service
        </button>
      @endif
    </div>

    @if(Auth::check() && Auth::user()->is_admin)
    <!-- Add New Service Modal -->
    <div id="add-service-modal" class="modal-overlay">
      <div class="modal-content-beautiful">
        <h2>Add New Service</h2>
        <form id="add-service-form-modal">
          <div class="modal-form-row">
            <div class="modal-form-group">
              <label>Profile ID</label>
              <input type="text" name="profile_id" placeholder="Enter Profile ID" required>
            </div>
            <div class="modal-form-group">
              <label>Member Name</label>
              <input type="text" name="member_name" placeholder="Enter member name" required>
            </div>
          </div>
          <div class="modal-form-row">
            <div class="modal-form-group">
              <label>Gender</label>
              <select name="member_gender" required>
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div class="modal-form-group">
              <label>Mobile Number</label>
              <input type="tel" name="contact_mobile_no" placeholder="Enter mobile number" required>
            </div>
            <div class="modal-form-group">
              <label>Alternative Contact Number</label>
              <input type="tel" name="contact_alternate" placeholder="Enter alternative contact number">
            </div>
          </div>
          <div class="modal-form-row">
            <div class="modal-form-group">
              <label>Service Executive</label>
              <select name="service_executive" required>
                <option value="">Select Service Executive</option>
                @if(isset($staffUsers))
                  @foreach($staffUsers as $staff)
                    <option value="{{ $staff->first_name }}" 
                            {{ (Auth::user()->first_name == $staff->first_name) ? 'selected' : '' }}>
                      {{ $staff->first_name }}
                    </option>
                  @endforeach
                @else
                  <option value="{{ Auth::user()->first_name ?? 'admin' }}" selected>
                    {{ Auth::user()->first_name ?? 'admin' }}
                  </option>
                @endif
              </select>
            </div>
          </div>
          <div class="modal-actions">
            <button type="button" id="close-modal-btn" class="btn btn-secondary">Cancel</button>
            <button type="submit" class="btn btn-primary">Add Service</button>
          </div>
        </form>
      </div>
    </div>
    @endif

    <div class="table-container">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 class="table-title">List of New Services
          @if(isset($services) && method_exists($services, 'total'))
          <span style="font-size: 14px; font-weight: normal; color: #666;">
            ({{ $services->firstItem() ?? 0 }} - {{ $services->lastItem() ?? 0 }} of {{ $services->total() }} services)
          </span>
          @endif
        </h2>
        
        <!-- Entries per page dropdown -->
        <div style="display: flex; align-items: center; gap: 10px;">
          <label for="perPageSelect" style="font-size: 14px; color: #666;">Show:</label>
          <select id="perPageSelect" onchange="changePerPage()" style="padding: 5px 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
            <option value="10" {{ (isset($perPage) && $perPage == 10) ? 'selected' : '' }}>10 entries</option>
            <option value="50" {{ (isset($perPage) && $perPage == 50) ? 'selected' : '' }}>50 entries</option>
            <option value="100" {{ (isset($perPage) && $perPage == 100) ? 'selected' : '' }}>100 entries</option>
          </select>
        </div>
      </div>
      
      <table class="services-table">
        <thead>
          <tr>
            <th>Sl No</th>
            <th>Profile ID</th>
            <th>Name</th>
            <th>Plan Name</th>
            <th>Payment Date</th>
            <th>Executive Name</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="services-tbody">
        @if(isset($services) && count($services))
            @foreach($services as $index => $service)
            <tr>
                <td>{{ ($services->currentPage() - 1) * $services->perPage() + $index + 1 }}</td>
                <td>{{ $service->profile_id }}</td>
                <td>{{ $service->name }}</td>
                <td>{{ $service->plan_name }}</td>
                <td>{{ $service->payment_date ? \Carbon\Carbon::parse($service->payment_date)->format('d-M-Y') : '' }}</td>
                <td>{{ $service->service_executive }}</td>
                <td><a href="{{ route('service.details', ['id' => $service->profile_id, 'name' => $service->name]) }}" class="action-link">Service Details</a></td>
            </tr>
            @endforeach
        @else
            <tr><td colspan="7" style="text-align:center;">No services found.</td></tr>
        @endif
        </tbody>
      </table>
      
      <!-- Custom Designed Pagination -->
      @if(isset($services) && method_exists($services, 'links'))
      <div class="pagination-wrapper">
        <!-- Pagination info -->
        <div class="pagination-info">
          <span class="pagination-text">Showing {{ $services->firstItem() ?? 0 }} to {{ $services->lastItem() ?? 0 }} of {{ $services->total() }} results</span>
        </div>
        
        <!-- Custom Pagination Navigation -->
        <div class="custom-pagination">
          @if ($services->hasPages())
            <div class="pagination-container">
              {{-- Previous Page Link --}}
              @if ($services->onFirstPage())
                <span class="pagination-btn pagination-btn-disabled">
                  <i class="pagination-icon">‹</i>
                  <span>Previous</span>
                </span>
              @else
                <a href="{{ $services->previousPageUrl() }}" class="pagination-btn pagination-btn-active">
                  <i class="pagination-icon">‹</i>
                  <span>Previous</span>
                </a>
              @endif

              {{-- Pagination Elements --}}
              <div class="pagination-numbers">
                @foreach ($services->getUrlRange(1, $services->lastPage()) as $page => $url)
                  @if ($page == $services->currentPage())
                    <span class="pagination-number pagination-number-current">{{ $page }}</span>
                  @else
                    <a href="{{ $url }}" class="pagination-number">{{ $page }}</a>
                  @endif
                @endforeach
              </div>

              {{-- Next Page Link --}}
              @if ($services->hasMorePages())
                <a href="{{ $services->nextPageUrl() }}" class="pagination-btn pagination-btn-active">
                  <span>Next</span>
                  <i class="pagination-icon">›</i>
                </a>
              @else
                <span class="pagination-btn pagination-btn-disabled">
                  <span>Next</span>
                  <i class="pagination-icon">›</i>
                </span>
              @endif
            </div>
          @endif
        </div>
      </div>
      @endif
    </div>
  </main>

  <script>
    // Function to change per page count - global function
    function changePerPage() {
      const select = document.getElementById('perPageSelect');
      const perPage = select.value;
      const url = new URL(window.location.href);
      url.searchParams.set('per_page', perPage);
      url.searchParams.delete('page'); // Reset to page 1 when changing per page
      window.location.href = url.toString();
    }

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

    // Modal logic for Add New Service
    const addNewServiceBtn = document.getElementById('add-new-service-btn');
    const addServiceModal = document.getElementById('add-service-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const serviceForm = document.getElementById('add-service-form-modal');
    const servicesTableBody = document.getElementById('services-tbody');

    // Open modal
    addNewServiceBtn.addEventListener('click', function() {
      addServiceModal.classList.add('active');
      document.body.style.overflow = 'hidden'; // Prevent background scrolling
    });

    // Close modal
    closeModalBtn.addEventListener('click', function() {
      addServiceModal.classList.remove('active');
      document.body.style.overflow = 'auto';
    });

    // Close modal on outside click
    addServiceModal.addEventListener('click', function(e) {
      if (e.target === addServiceModal) {
        addServiceModal.classList.remove('active');
        document.body.style.overflow = 'auto';
      }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && addServiceModal.classList.contains('active')) {
        addServiceModal.classList.remove('active');
        document.body.style.overflow = 'auto';
      }
    });

    // Handle form submission
    serviceForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      
      // Add debug logging
      console.log('Form data being sent:');
      for (let [key, value] of formData.entries()) {
        console.log(key, value);
      }
      
      fetch("{{ route('new.service.store') }}", {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
      })
      .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
          return response.text().then(text => {
            console.log('Error response body:', text);
            throw new Error(`HTTP error! status: ${response.status}, body: ${text.substring(0, 200)}...`);
          });
        }
        
        return response.text().then(text => {
          console.log('Response body:', text);
          try {
            return JSON.parse(text);
          } catch (e) {
            console.error('Failed to parse JSON:', e);
            throw new Error('Response is not valid JSON: ' + text.substring(0, 100));
          }
        });
      })
      .then(data => {
        if (data.success) {
          showNotification('Service added successfully!', 'success');
          // Close modal and reset form
          addServiceModal.classList.remove('active');
          document.body.style.overflow = 'auto';
          serviceForm.reset();
          // Reload the page to refresh the service list
          setTimeout(() => window.location.reload(), 1000);
        } else {
          showNotification(data.message || 'Failed to add service', 'error');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to add service: ' + error.message, 'error');
      });
    });

    // Notification function
    function showNotification(message, type = 'info') {
      const notification = document.createElement('div');
      notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        background: ${type === 'success' ? '#2ecc71' : '#3498db'};
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 3000;
        transform: translateX(300px);
        transition: transform 0.3s ease;
        font-weight: 500;
      `;
      notification.textContent = message;
      document.body.appendChild(notification);

      // Animate in
      setTimeout(() => {
        notification.style.transform = 'translateX(0)';
      }, 100);

      // Remove after 3 seconds
      setTimeout(() => {
        notification.style.transform = 'translateX(300px)';
        setTimeout(() => {
          document.body.removeChild(notification);
        }, 300);
      }, 3000);
    }

    // Main header navigation
    document.querySelectorAll('.header-nav a').forEach(link => {
      link.addEventListener('click', function(e) {
        if (!this.getAttribute('href') || this.getAttribute('href') === '#') {
          e.preventDefault();
          document.querySelectorAll('.header-nav a').forEach(l => l.classList.remove('active'));
          this.classList.add('active');
          const page = this.getAttribute('data-page');
          console.log('Navigating to:', page);
        }
      });
    });

    // Logout functionality
    document.querySelector('.logout-btn').addEventListener('click', function() {
      if(confirm('Are you sure you want to logout?')) {
        showNotification('Logging out...', 'info');
        setTimeout(() => {
          // Redirect to login page
          console.log('Redirecting to login...');
        }, 1500);
      }
    });

    // Add CSS animation keyframes
    const style = document.createElement('style');
    style.textContent = `
      @keyframes fadeIn {
        from {
          opacity: 0;
          transform: translateY(-10px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
    `;
    document.head.appendChild(style);
  </script>

</body>
</html>