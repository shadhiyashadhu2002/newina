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

    /* Main Dashboard Header - Same as original code */
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

    .btn-shortlist-varuna {
      background: #ffc107;
      color: #212529;
    }

    .btn-shortlist-varuna:hover {
      background: #e0a800;
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
      
      .table-container {
        margin: 0 10px;
      }
      
      .profiles-table {
        font-size: 11px;
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
      
      .header-nav {
        flex-wrap: wrap;
        gap: 15px;
      }
    }
  </style>
</head>
<body>

  <!-- Main Dashboard Header - Same as original -->
  <header class="main-header">
    <a href="#" class="header-brand">INA</a>
    
    <nav>
      <ul class="header-nav">
        <li><a href="#" data-page="dashboard" class="active">Home</a></li>
        <li><a href="#" data-page="profiles">Profiles</a></li>
        <li><a href="#" data-page="sales">Sales <span class="dropdown-arrow">▼</span></a></li>
        <li><a href="#" data-page="helpline">HelpLine</a></li>
        <li><a href="#" data-page="fresh-data">Fresh Data <span class="dropdown-arrow">▼</span></a></li>
        <li><a href="#" data-page="abc">abc</a></li>
        <li><a href="#" data-page="services">Services <span class="dropdown-arrow">▼</span></a></li>
      </ul>
    </nav>
    
    <button class="logout-btn">Logout</button>
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
        <tbody>
          <tr>
            <td><span class="profile-id">38584</span></td>
            <td><span class="date">27-Jan-2025</span></td>
            <td><span class="date">03-Nov-2025</span></td>
            <td><span class="executive-name">greeshmarajegh1989</span></td>
            <td>
              <a href="{{ route('shortlist.ina', 38584) }}" class="action-btn btn-shortlist-ina">📋 Shortlist from INA</a>
              <a href="{{ route('shortlist.others', 38584) }}" class="action-btn btn-shortlist-others">📋 Shortlist from Others</a>
              <a href="{{ route('view.prospects', 38584) }}" class="action-btn btn-view-prospects">👀 View Prospects</a>
              <a href="{{ route('client.details', 38584) }}" class="action-btn btn-view-details">👤 View Client Details</a>
            </td>
          </tr>
          <tr>
            <td><span class="profile-id">44260</span></td>
            <td><span class="date">17-Jun-2025</span></td>
            <td><span class="date">15-Oct-2025</span></td>
            <td><span class="executive-name">greeshmarajegh1989</span></td>
            <td>
              <a href="{{ route('shortlist.ina', 44260) }}" class="action-btn btn-shortlist-ina">📋 Shortlist from INA</a>
              <a href="{{ route('shortlist.others', 44260) }}" class="action-btn btn-shortlist-others">📋 Shortlist from Others</a>
              <a href="{{ route('view.prospects', 44260) }}" class="action-btn btn-view-prospects">👀 View Prospects</a>
              <a href="{{ route('client.details', 44260) }}" class="action-btn btn-view-details">👤 View Client Details</a>
            </td>
          </tr>
          <tr>
            <td><span class="profile-id">29541</span></td>
            <td><span class="date">15-May-2025</span></td>
            <td><span class="date">11-Nov-2025</span></td>
            <td><span class="executive-name">greeshmarajegh1989</span></td>
            <td>
              <a href="{{ route('shortlist.ina', 29541) }}" class="action-btn btn-shortlist-ina">📋 Shortlist from INA</a>
              <a href="{{ route('shortlist.others', 29541) }}" class="action-btn btn-shortlist-others">📋 Shortlist from Others</a>
              <a href="{{ route('view.prospects', 29541) }}" class="action-btn btn-view-prospects">👀 View Prospects</a>
              <a href="{{ route('client.details', 29541) }}" class="action-btn btn-view-details">👤 View Client Details</a>
            </td>
          </tr>
          <tr>
            <td><span class="profile-id">41787</span></td>
            <td><span class="date">05-Jul-2025</span></td>
            <td><span class="date">03-Oct-2025</span></td>
            <td><span class="executive-name">shijina680</span></td>
            <td>
              <a href="{{ route('shortlist.ina', 41787) }}" class="action-btn btn-shortlist-ina">📋 Shortlist from INA</a>
              <a href="{{ route('shortlist.others', 41787) }}" class="action-btn btn-shortlist-others">📋 Shortlist from Others</a>
              <a href="{{ route('view.prospects', 41787) }}" class="action-btn btn-view-prospects">👀 View Prospects</a>
              <a href="{{ route('client.details', 41787) }}" class="action-btn btn-view-details">👤 View Client Details</a>
            </td>
          </tr>
          <tr>
            <td><span class="profile-id">87025</span></td>
            <td><span class="date">16-Jul-2025</span></td>
            <td><span class="date">12-Jan-2026</span></td>
            <td><span class="executive-name">greeshmarajegh1989</span></td>
            <td>
              <a href="{{ route('shortlist.ina', 87025) }}" class="action-btn btn-shortlist-ina">📋 Shortlist from INA</a>
              <a href="{{ route('shortlist.others', 87025) }}" class="action-btn btn-shortlist-others">📋 Shortlist from Others</a>
              <a href="{{ route('view.prospects', 87025) }}" class="action-btn btn-view-prospects">👀 View Prospects</a>
              <a href="{{ route('client.details', 87025) }}" class="action-btn btn-view-details">👤 View Client Details</a>
            </td>
          </tr>
          <tr>
            <td><span class="profile-id">30955</span></td>
            <td><span class="date">15-May-2025</span></td>
            <td><span class="date">11-Nov-2025</span></td>
            <td><span class="executive-name">greeshmarajegh1989</span></td>
            <td>
              <a href="{{ route('shortlist.ina', 30955) }}" class="action-btn btn-shortlist-ina">📋 Shortlist from INA</a>
              <a href="{{ route('shortlist.others', 30955) }}" class="action-btn btn-shortlist-others">📋 Shortlist from Others</a>
              <a href="{{ route('view.prospects', 30955) }}" class="action-btn btn-view-prospects">👀 View Prospects</a>
              <a href="{{ route('client.details', 30955) }}" class="action-btn btn-view-details">👤 View Client Details</a>
            </td>
          </tr>
          <tr>
            <td><span class="profile-id">41519</span></td>
            <td><span class="date">30-Jul-2025</span></td>
            <td><span class="date">26-Oct-2025</span></td>
            <td><span class="executive-name">shijina680</span></td>
            <td>
              <a href="{{ route('shortlist.ina', 41519) }}" class="action-btn btn-shortlist-ina">📋 Shortlist from INA</a>
              <a href="{{ route('shortlist.others', 41519) }}" class="action-btn btn-shortlist-others">📋 Shortlist from Others</a>
              <a href="{{ route('view.prospects', 41519) }}" class="action-btn btn-view-prospects">👀 View Prospects</a>
              <a href="{{ route('client.details', 41519) }}" class="action-btn btn-view-details">👤 View Client Details</a>
            </td>
          </tr>
          <tr>
            <td><span class="profile-id">51139</span></td>
            <td><span class="date">12-Sep-2025</span></td>
            <td><span class="date">11-Mar-2026</span></td>
            <td><span class="executive-name">greeshmarajegh1989</span></td>
            <td>
              <a href="{{ route('shortlist.ina', 51139) }}" class="action-btn btn-shortlist-ina">📋 Shortlist from INA</a>
              <a href="{{ route('shortlist.others', 51139) }}" class="action-btn btn-shortlist-others">📋 Shortlist from Others</a>
              <a href="{{ route('view.prospects', 51139) }}" class="action-btn btn-view-prospects">👀 View Prospects</a>
              <a href="{{ route('client.details', 51139) }}" class="action-btn btn-view-details">👤 View Client Details</a>
            </td>
          </tr>
          <tr>
            <td><span class="profile-id">17004</span></td>
            <td><span class="date">23-Aug-2025</span></td>
            <td><span class="date">21-Nov-2025</span></td>
            <td><span class="executive-name">greeshmarajegh1989</span></td>
            <td>
              <a href="{{ route('shortlist.ina', 17004) }}" class="action-btn btn-shortlist-ina">📋 Shortlist from INA</a>
              <a href="{{ route('shortlist.others', 17004) }}" class="action-btn btn-shortlist-others">📋 Shortlist from Others</a>
              <a href="{{ route('view.prospects', 17004) }}" class="action-btn btn-view-prospects">👀 View Prospects</a>
              <a href="{{ route('client.details', 17004) }}" class="action-btn btn-view-details">👤 View Client Details</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>

  <script>
    // Navigation functionality - same as original
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
        // Create a form and submit it to the logout route
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('logout') }}';
        
        // Add CSRF token from meta tag
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);
        
        // Add the form to the document and submit it
        document.body.appendChild(form);
        form.submit();
      }
    });

    // Action button functionality - removed preventDefault to allow normal navigation
    document.querySelectorAll('.action-btn').forEach(btn => {
      btn.addEventListener('click', function(e) {
        // Allow normal navigation by not preventing default
        const action = this.textContent.trim();
        const profileRow = this.closest('tr');
        const profileId = profileRow.querySelector('.profile-id').textContent;
        
        console.log(`Navigating to: ${action} for Profile ID: ${profileId}`);
        // Let the browser handle the navigation normally
      });
    });

    // Table row click functionality
    document.querySelectorAll('.profiles-table tbody tr').forEach(row => {
      row.addEventListener('click', function(e) {
        // Only trigger if not clicking on action buttons
        if (!e.target.classList.contains('action-btn')) {
          const profileId = this.querySelector('.profile-id').textContent;
          console.log(`Row clicked for Profile ID: ${profileId}`);
        }
      });
    });
  </script>

</body>
</html>