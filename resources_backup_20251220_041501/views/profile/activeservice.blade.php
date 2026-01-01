<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>INA Dashboard - Active Service Profiles</title>
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
      margin-right: auto;
    }

    .header-nav {
      display: flex;
      list-style: none;
      gap: 25px;
      align-items: center;
      margin: 0;
      padding: 0;
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
      margin-left: 20px;
    }

    .logout-btn:hover {
      background: rgba(255, 255, 255, 0.2);
      transform: translateY(-1px);
    }

    /* Main Content Area */
    .main-content {
      padding: 30px;
      max-width: 1400px;
      margin: 0 auto;
    }

    .page-title {
      color: #fff;
      font-size: 28px;
      font-weight: 600;
      margin-bottom: 25px;
      text-shadow: 0 2px 4px rgba(0,0,0,0.3);
      text-align: center;
    }

    /* Table Container */
    .table-container {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      padding: 0;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      overflow: hidden;
    }

    /* Table Header */
    .table-header {
      background: linear-gradient(135deg, #4CAF50, #45a049);
      color: white;
      padding: 20px 30px;
      font-size: 18px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    /* Table Styles */
    .profiles-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
    }

    .profiles-table th {
      background: #f8f9fa;
      padding: 15px 12px;
      text-align: left;
      font-weight: 600;
      color: #2c3e50;
      border-bottom: 2px solid #e9ecef;
      font-size: 13px;
    }

    .profiles-table td {
      padding: 15px 12px;
      border-bottom: 1px solid #e9ecef;
      vertical-align: middle;
    }

    .profiles-table tbody tr:hover {
      background: rgba(76, 175, 80, 0.05);
    }

    /* Action Buttons */
    .action-btn {
      padding: 6px 12px;
      border: none;
      border-radius: 15px;
      font-size: 12px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      margin: 2px;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      text-decoration: none;
    }

    .btn-shortlist-ina {
      background: #28a745;
      color: white;
    }

    .btn-shortlist-ina:hover {
      background: #218838;
      transform: translateY(-1px);
    }

    .btn-shortlist-others {
      background: #17a2b8;
      color: white;
    }

    .btn-shortlist-others:hover {
      background: #138496;
      transform: translateY(-1px);
    }

    .btn-view-prospects {
      background: #dc3545;
      color: white;
    }

    .btn-view-prospects:hover {
      background: #c82333;
      transform: translateY(-1px);
    }

    .btn-view-details {
      background: #6c757d;
      color: white;
    }

    .btn-view-details:hover {
      background: #5a6268;
      transform: translateY(-1px);
    }

    /* Profile ID styling */
    .profile-id {
      font-weight: 600;
      color: #2c3e50;
    }

    /* Executive name styling */
    .executive-name {
      color: #666;
      font-style: italic;
    }

    /* Date styling */
    .date {
      color: #666;
      font-size: 13px;
    }

    /* Empty state */
    .empty-state {
      text-align: center;
      padding: 40px;
      color: #999;
    }

    /* Responsive */
    @media (max-width: 1200px) {
      .profiles-table {
        font-size: 12px;
      }
      
      .action-btn {
        padding: 4px 8px;
        font-size: 11px;
      }
    }

    @media (max-width: 768px) {
      .main-content {
        padding: 15px;
      }
      
      .header-nav {
        gap: 15px;
      }
      
      .profiles-table th,
      .profiles-table td {
        padding: 8px 6px;
      }
      
      .action-btn {
        padding: 3px 6px;
        font-size: 10px;
        margin: 1px;
      }
    }

    /* Pager buttons */
    .pager-btn {
      display:inline-flex;
      align-items:center;
      justify-content:center;
      padding:8px 12px;
      border-radius:10px;
      border:1px solid rgba(0,0,0,0.06);
      background:#fff;
      color:#2c3e50;
      font-weight:600;
      text-decoration:none;
      box-shadow: 0 4px 12px rgba(0,0,0,0.06);
      transition: all 0.18s ease;
      margin:0 6px;
    }

    .pager-btn.primary {
      background: linear-gradient(135deg,#28a745,#218838);
      color:#fff;
      border:none;
      box-shadow: 0 6px 18px rgba(40,167,69,0.14);
    }

    .pager-btn:hover { transform: translateY(-2px); }
    .pager-btn.disabled { opacity:0.55; cursor:default; transform:none; box-shadow:none; }
    
    /* Cleaner compact pagination styles (user requested) */
    /* Pagination Container */
    .pagination-wrapper {
      margin-top: 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: rgba(255, 255, 255, 0.95);
      padding: 15px 20px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .pagination-info {
      color: #555;
      font-size: 14px;
      font-weight: 500;
    }

    .pagination-controls {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    /* Laravel Pagination Links - Clean compact style */
    .pagination {
      display: flex;
      list-style: none;
      gap: 5px;
      margin: 0;
      padding: 0;
      align-items: center;
    }

    .pagination li {
      display: inline-block;
    }

    .pagination a,
    .pagination span {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 32px;
      height: 32px;
      padding: 0 10px;
      border-radius: 6px;
      border: 1px solid rgba(0, 0, 0, 0.1);
      background: #fff;
      color: #2c3e50;
      font-weight: 600;
      text-decoration: none;
      font-size: 13px;
      transition: all 0.2s ease;
    }

    .pagination a:hover {
      background: linear-gradient(135deg, #28a745, #218838);
      color: #fff;
      border-color: #28a745;
      transform: translateY(-1px);
      box-shadow: 0 2px 8px rgba(40, 167, 69, 0.25);
    }

    /* Active page - using your brand color */
    .pagination .active span,
    .pagination li.active span {
      background: linear-gradient(135deg, #ac0742, #9d1955);
      color: #fff;
      border-color: #ac0742;
      box-shadow: 0 2px 8px rgba(172, 7, 66, 0.25);
    }

    /* Disabled state */
    .pagination .disabled span,
    .pagination li.disabled span {
      opacity: 0.4;
      cursor: not-allowed;
      background: #f8f9fa;
      color: #999;
    }

    /* Remove arrow styling, make Previous/Next text-only */
    .pagination a[rel="prev"],
    .pagination a[rel="next"],
    .pagination span[rel="prev"],
    .pagination span[rel="next"] {
      font-size: 13px;
      padding: 0 12px;
    }

    /* Hide SVG arrows if Laravel adds them */
    .pagination svg {
      display: none !important;
    }

    @media (max-width: 768px) {
      .pagination-wrapper {
        flex-direction: column;
        gap: 12px;
        padding: 12px 15px;
      }
      
      .pagination a,
      .pagination span {
        min-width: 28px;
        height: 28px;
        font-size: 12px;
        padding: 0 8px;
      }
    }
  </style>
</head>
<body>

  <!-- Main Dashboard Header -->
  <header class="main-header">
    <a href="#" class="header-brand">INA</a>
    
    <nav>
      <ul class="header-nav">
        <li><a href="{{ route('dashboard') }}" class="active">Home</a></li>
        <li><a href="{{ route('profile.hellow') }}">Profiles</a></li>
        <li><a href="#">Sales <span class="dropdown-arrow">▼</span></a></li>
        <li><a href="#">HelpLine</a></li>
        <li><a href="{{ route('fresh.data') }}">Fresh Data <span class="dropdown-arrow">▼</span></a></li>
        <li><a href="#">abc</a></li>
        <li><a href="{{ route('services.page') }}">Services <span class="dropdown-arrow">▼</span></a></li>
      </ul>
    </nav>
    
    <button class="logout-btn" onclick="logout()">Logout</button>
  </header>

  <!-- Main Content Area -->
  <main class="main-content">
    <h1 class="page-title">Active Service Profiles</h1>

    <div class="table-container">
      <!-- Table Header -->
      <div class="table-header">
        <span>📋</span>
        List of Active Profiles
      </div>

      <!-- Search / Filters -->
      <div style="padding:16px; display:flex; gap:12px; align-items:center; flex-wrap:wrap; background:#fff; border-bottom:1px solid #eee;">
        <form method="GET" action="{{ route('active.service') }}" style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
          <input type="text" name="search" placeholder="Search by Profile ID or Name" value="{{ request('search') }}" style="padding:8px 10px; border-radius:6px; border:1px solid #ccc; min-width:260px;" />
          <select name="per_page" style="padding:8px 10px; border-radius:6px; border:1px solid #ccc;">
            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
          </select>
          <button type="submit" class="action-btn" style="background:#007bff;color:#fff;padding:8px 12px;border-radius:8px;">Search</button>
          @if(request()->has('search') || request()->has('per_page'))
            <a href="{{ route('active.service') }}" class="action-btn" style="background:#6c757d;color:#fff;padding:8px 12px;border-radius:8px;">Reset</a>
          @endif
        </form>
      </div>

      <!-- Profiles Table -->
      <table class="profiles-table">
        <thead>
          <tr>
            <th>Profile ID</th>
            <th>Name</th>
            <th>Start Date</th>
            <th>Expiry Date</th>
            <th>Executive Name</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="servicesTableBody">
          @if(isset($services) && $services->count() > 0)
            @foreach($services as $s)
              <tr>
                <td><span class="profile-id">{{ $s->profile_id }}</span></td>
                <td>{{ $s->name ?? $s->member_name ?? '-' }}</td>
                <td><span class="date">{{ $s->start_date ? \Carbon\Carbon::parse($s->start_date)->format('d-M-Y') : '-' }}</span></td>
                <td><span class="date">{{ $s->expiry_date ? \Carbon\Carbon::parse($s->expiry_date)->format('d-M-Y') : '-' }}</span></td>
                <td><span class="executive-name">{{ $s->service_executive }}</span></td>
                <td>
                  <a href="{{ route('shortlist.ina', $s->profile_id) }}" class="action-btn btn-shortlist-ina">📋 Shortlist from INA</a>
                  <a href="{{ route('shortlist.others', $s->profile_id) }}" class="action-btn btn-shortlist-others">📋 Shortlist from Others</a>
                  <a href="{{ route('view.prospects', $s->profile_id ?? $s->id) }}" class="action-btn btn-view-prospects">👀 View Prospects</a>
                  <a href="{{ route('client.details', $s->profile_id) }}" class="action-btn btn-view-details">👤 View Client Details</a>
                </td>
              </tr>
            @endforeach
          @else
            <tr>
              <td colspan="6" class="empty-state">No active services found.</td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>

    @if(isset($services) && $services->total() > 0)
      <div class="pagination-wrapper">
        <div class="pagination-info">
          Showing {{ $services->firstItem() ?? 0 }}-{{ $services->lastItem() ?? 0 }} of {{ $services->total() }}
        </div>
        
        <div class="pagination-controls">
          {{ $services->appends(request()->query())->links() }}
        </div>
      </div>
    @endif
  </main>

  <script>
    function logout() {
      if(confirm('Are you sure you want to logout?')) {
        const form = document.createElement('form');
        form.method = 'POST';
  form.action = "{{ route('logout') }}";
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);
        
        document.body.appendChild(form);
        form.submit();
      }
    }

    // Navigation functionality
    document.querySelectorAll('.header-nav a').forEach(link => {
      link.addEventListener('click', function(e) {
        if (!this.getAttribute('href') || this.getAttribute('href') === '#') {
          e.preventDefault();
        }
      });
    });

    // Action button functionality
    document.querySelectorAll('.action-btn').forEach(btn => {
      btn.addEventListener('click', function(e) {
        const action = this.textContent.trim();
        const profileRow = this.closest('tr');
        const profileId = profileRow.querySelector('.profile-id').textContent;
        console.log(`Action: ${action} for Profile ID: ${profileId}`);
      });
    });
  </script>

</body>
</html>