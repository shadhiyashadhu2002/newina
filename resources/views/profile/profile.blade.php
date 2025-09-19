<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>INA Dashboard - Profiles</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: #333;
      min-height: 100vh;
    }

    /* Main Dashboard Header */
    .main-header {
      background: linear-gradient(135deg, #4a69bd, #5a4fcf);
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

    /* Top buttons and filters */
    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      flex-wrap: wrap;
      gap: 15px;
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
    }

    .btn-add:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(76, 175, 80, 0.6);
    }

    .filters {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }

    .filters button {
      background: rgba(255, 255, 255, 0.9);
      border: 1px solid rgba(255, 255, 255, 0.3);
      padding: 8px 16px;
      border-radius: 20px;
      cursor: pointer;
      font-size: 13px;
      font-weight: 500;
      transition: all 0.3s ease;
      white-space: nowrap;
      color: #555;
      backdrop-filter: blur(10px);
    }

    .filters button:hover {
      background: rgba(255, 255, 255, 1);
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .filters button.active {
      background: #4CAF50;
      color: white;
      border-color: #4CAF50;
      box-shadow: 0 4px 15px rgba(76, 175, 80, 0.4);
    }

    /* Table container */
    .table-container {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      padding: 25px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.3);
    }

    /* Search and entries */
    .table-controls {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      font-size: 14px;
      color: #666;
    }

    .table-controls select, 
    .table-controls input {
      padding: 8px 12px;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      font-size: 14px;
      transition: all 0.3s ease;
      background: white;
    }

    .table-controls select:focus,
    .table-controls input:focus {
      outline: none;
      border-color: #4CAF50;
      box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
    }

    .table-controls input {
      width: 250px;
    }

    /* Table styling */
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
      background: #fff;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      border: 1px solid #d0e8f2;
    }

    table th, table td {
      padding: 15px 12px;
      text-align: center;
      vertical-align: middle;
      border: 1px solid #d0e8f2;
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

    table th:first-child,
    table td:first-child {
      width: 40px;
    }

    /* Profile ID links */
    a.profile-link {
      color: #2196F3;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }

    a.profile-link:hover {
      color: #1976D2;
      text-decoration: underline;
    }

    /* Status styling */
    .status-postpone {
      background: linear-gradient(135deg, #fff3e0, #ffcc02);
      color: #e65100;
      padding: 6px 12px;
      border-radius: 15px;
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
    }

    .status-interest {
      background: linear-gradient(135deg, #e8f5e8, #4CAF50);
      color: #1b5e20;
      padding: 6px 12px;
      border-radius: 15px;
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
    }

    /* Action buttons */
    .actions {
      display: flex;
      gap: 8px;
      justify-content: center;
    }

    .actions button {
      padding: 8px 12px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 16px;
      transition: all 0.3s ease;
      min-width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .actions .edit {
      background: linear-gradient(135deg, #17a2b8, #138496);
      color: white;
      box-shadow: 0 4px 15px rgba(23, 162, 184, 0.4);
    }

    .actions .edit:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(23, 162, 184, 0.6);
    }

    .actions .delete {
      background: linear-gradient(135deg, #dc3545, #c82333);
      color: white;
      box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
    }

    .actions .delete:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(220, 53, 69, 0.6);
    }

    /* Checkbox styling */
    input[type="checkbox"] {
      width: 18px;
      height: 18px;
      cursor: pointer;
      accent-color: #4CAF50;
    }

    /* Empty cell styling */
    .empty-cell {
      color: #999;
      font-style: italic;
    }

    /* Names styling */
    .name-cell {
      font-weight: 500;
      color: #2c3e50;
    }

    /* Email styling */
    .email-cell {
      color: #666;
      font-size: 13px;
    }
  </style>
</head>
<body>

  <!-- Main Dashboard Header -->
  <header class="main-header">
    <a href="#" class="header-brand">INA</a>
    
    <nav>
      <ul class="header-nav">
    <li><a href="{{ route('dashboard') }}" data-page="dashboard">Home</a></li>
    <li><a href="#" data-page="profiles" class="active">Profiles</a></li>
    <li><a href="#" data-page="sales">Sales <span class="dropdown-arrow">▼</span></a></li>
    <li><a href="#" data-page="helpline">HelpLine</a></li>
    <li><a href="{{ route('fresh.data') }}">Fresh Data <span class="dropdown-arrow">▼</span></a></li>
    <li><a href="#" data-page="abc">abc</a></li>
  <li><a href="{{ route('services.page') }}">Services <span class="dropdown-arrow">▼</span></a></li>
      </ul>
    </nav>
    
    <button class="logout-btn">Logout</button>
  </header>

  <!-- Main Content Area -->
  <main class="main-content">
    <h1 class="page-title">Profiles</h1>

    <div class="top-bar">
      <div class="filters">
        <button>12 More Days Data</button>
        <button>No Welcome Calls</button>
        <button>Post Pone Payment</button>
        <button>Not Assigned</button>
        <button>ZERO Follow-Ups</button>
        <button>Followup Today</button>
        <button>Followup Due</button>
        <button class="active">All</button>
      </div>
      <a href="{{ route('profile.addnew') }}" class="btn-add" style="text-decoration:none;">
        <span>⊕</span>
        Add New Profile
      </a>
    </div>

    <div class="table-container">
      <div class="table-controls">
        <div>
          Show
          <select>
            <option>10</option>
            <option>25</option>
            <option>50</option>
            <option>100</option>
          </select>
          entries
        </div>
        <div>
          Search: <input type="text" placeholder="Search profiles..." />
        </div>
      </div>

      <table>
        <thead>
          <tr>
            <th></th>
            <th>Profile ID</th>
            <th>Name</th>
            <th>Reg Date</th>
            <th>Assign Date</th>
            <th>Followup Date</th>
            <th>Assigned To</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
          <tr>
            <td><input type="checkbox"></td>
            <td><a href="#" class="profile-link">{{ $user->code }}</a></td>
            <td class="name-cell">{{ $user->first_name }}</td>
            <td>{{ $user->created_at ? $user->created_at->format('d-M-Y') : '-' }}</td>
            <td>{{ $user->created_at ? $user->created_at->format('d-M-Y') : '-' }}</td>
            <td>{{ $user->created_at ? $user->created_at->format('d-M-Y') : '-' }}</td>
            <td class="email-cell">{{ $user->assigned_to ?? '-' }}</td>
            <td>{{ $user->status ?? '-' }}</td>
            <td class="actions">
              <a href="{{ route('profile.edit_hi', $user->id) }}" class="edit" style="display:inline-block;text-decoration:none;">✏️</a>
              <button class="delete">🗑️</button>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </main>

  <script>
    // Main header navigation
    document.querySelectorAll('.header-nav a').forEach(link => {
      link.addEventListener('click', function(e) {
        // Only prevent default if href is '#' (no real navigation)
        if (!this.getAttribute('href') || this.getAttribute('href') === '#') {
          e.preventDefault();
          // Remove active class from all nav links
          document.querySelectorAll('.header-nav a').forEach(l => l.classList.remove('active'));
          // Add active class to clicked link
          this.classList.add('active');
          // Get the page name
          const page = this.getAttribute('data-page');
          console.log('Navigating to:', page);
          // Update page title
          const pageTitle = document.querySelector('.page-title');
          pageTitle.textContent = this.textContent.replace('▼', '').trim();
          // You can add logic here to load different content based on the page
          if (page === 'home' || page === 'dashboard') {
            // Load home dashboard with cards
            loadHomePage();
          } else if (page === 'profiles') {
            // Keep current profiles table
            loadProfilesPage();
          } else if (page === 'sales') {
            // Load sales page
            loadSalesPage();
          } else if (page === 'helpline') {
            // Load helpline page
            loadHelplinePage();
          } else if (page === 'fresh-data') {
            // Load fresh data page
            loadFreshDataPage();
          } else if (page === 'abc') {
            // Load abc page
            loadAbcPage();
          } else if (page === 'services') {
            // Load services page
            loadServicesPage();
          }
        }
      });
    });

    // Logout functionality
    document.querySelector('.logout-btn').addEventListener('click', function() {
      if(confirm('Are you sure you want to logout?')) {
        alert('Logging out...');
        // Add logout logic here
      }
    });

    // Placeholder functions for different pages
    function loadHomePage() {
      document.querySelector('.main-content').innerHTML = `
        <h1 class="page-title">Dashboard Home</h1>
        <div style="color: white; text-align: center; margin-top: 50px;">
          <h2>Welcome to INA Dashboard</h2>
          <p>This would show the dashboard cards like Follow-up Today, Follow-up Due, New Profiles, etc.</p>
        </div>
      `;
    }

    function loadProfilesPage() {
      location.reload(); // Reload to show profiles page
    }

    function loadSalesPage() {
      document.querySelector('.main-content').innerHTML = `
        <h1 class="page-title">Sales</h1>
        <div style="color: white; text-align: center; margin-top: 50px;">
          <h2>Sales Management</h2>
          <p>Sales data and management tools would appear here</p>
        </div>
      `;
    }

    function loadHelplinePage() {
      document.querySelector('.main-content').innerHTML = `
        <h1 class="page-title">HelpLine</h1>
        <div style="color: white; text-align: center; margin-top: 50px;">
          <h2>HelpLine Support</h2>
          <p>Customer support and helpline tools would appear here</p>
        </div>
      `;
    }

    function loadFreshDataPage() {
      document.querySelector('.main-content').innerHTML = `
        <h1 class="page-title">Fresh Data</h1>
        <div style="color: white; text-align: center; margin-top: 50px;">
          <h2>Fresh Data Management</h2>
          <p>New data imports and management would appear here</p>
        </div>
      `;
    }

    function loadAbcPage() {
      document.querySelector('.main-content').innerHTML = `
        <h1 class="page-title">ABC</h1>
        <div style="color: white; text-align: center; margin-top: 50px;">
          <h2>ABC Module</h2>
          <p>ABC functionality would appear here</p>
        </div>
      `;
    }

    function loadServicesPage() {
      document.querySelector('.main-content').innerHTML = `
        <h1 class="page-title">Services</h1>
        <div style="color: white; text-align: center; margin-top: 50px;">
          <h2>Services Management</h2>
          <p>Service management tools would appear here</p>
        </div>
      `;
    }

    // Filter buttons functionality
    document.querySelectorAll('.filters button').forEach(button => {
      button.addEventListener('click', function() {
        document.querySelectorAll('.filters button').forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
      });
    });

    // Table functionality
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
      checkbox.addEventListener('change', function() {
        console.log('Checkbox changed:', this.checked);
      });
    });

  // Remove edit button JS handler, now handled by anchor link

    document.querySelectorAll('.delete').forEach(button => {
      button.addEventListener('click', function() {
        const row = this.closest('tr');
        const profileId = row.querySelector('.profile-link').textContent;
        if(confirm('Are you sure you want to delete profile ' + profileId + '?')) {
          row.remove();
        }
      });
    });

    // Search functionality
    document.querySelector('input[type="text"]').addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase();
      const rows = document.querySelectorAll('tbody tr');
      
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });

    // Entries per page functionality
    document.querySelector('select').addEventListener('change', function() {
      console.log('Entries per page changed to:', this.value);
    });
  </script>

</body>
</html>