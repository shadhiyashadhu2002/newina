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

      <!-- Profiles Table -->
      <table class="profiles-table">
        <thead>
          <tr>
            <th>Profile ID</th>
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
                <td><span class="date">{{ $s->start_date ? \Carbon\Carbon::parse($s->start_date)->format('d-M-Y') : '-' }}</span></td>
                <td><span class="date">{{ $s->expiry_date ? \Carbon\Carbon::parse($s->expiry_date)->format('d-M-Y') : '-' }}</span></td>
                <td><span class="executive-name">{{ $s->service_executive }}</span></td>
                <td>
                  <a href="{{ route('shortlist.ina', $s->profile_id) }}" class="action-btn btn-shortlist-ina">📋 Shortlist from INA</a>
                  <a href="{{ route('shortlist.others', $s->profile_id) }}" class="action-btn btn-shortlist-others">📋 Shortlist from Others</a>
                  <a href="{{ route('view.prospects', $s->profile_id) }}" class="action-btn btn-view-prospects">👀 View Prospects</a>
                  <a href="{{ route('client.details', $s->profile_id) }}" class="action-btn btn-view-details">👤 View Client Details</a>
                </td>
              </tr>
            @endforeach
          @else
            <tr>
              <td colspan="5" class="empty-state">No active services found.</td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>

    @if(isset($services))
      <div style="margin-top: 20px;">
        {{ $services->links() }}
      </div>
    @endif
  </main>

  <script>
    function logout() {
      if(confirm('Are you sure you want to logout?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('logout') }}';
        
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