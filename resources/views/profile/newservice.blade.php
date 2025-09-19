<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>INA Dashboard - New Services</title>
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
      background: linear-gradient(135deg, #4a69bd, #5a4fcf);
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

    .action-link {
      color: #4a69bd;
      text-decoration: none;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
    }

    .action-link:before {
      content: "○";
      margin-right: 8px;
      font-weight: bold;
    }

    .action-link:hover {
      color: #5a4fcf;
      text-decoration: underline;
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
    
    <button class="logout-btn">Logout</button>
  </header>

  <!-- Main Content Area -->
  <main class="main-content">
    <h1 class="page-title">New Services</h1>

    <div class="table-container">
      <h2 class="table-title">List of New Services</h2>
      
      <table class="services-table">
        <thead>
          <tr>
            <th>Profile ID</th>
            <th>Name</th>
            <th>Plan Name</th>
            <th>Payment Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>39987</td>
            <td>Tatau</td>
            <td>Assisted</td>
            <td>05-Mar-2025</td>
            <td><a href="{{ route('service.details', ['id' => 39987, 'name' => 'Tatau']) }}" class="action-link">Service Details</a></td>
          </tr>
          <tr>
            <td>25663</td>
            <td>THANWEIN</td>
            <td>Assisted</td>
            <td>03-Mar-2025</td>
            <td><a href="{{ route('service.details', ['id' => 25663, 'name' => 'THANWEIN']) }}" class="action-link">Service Details</a></td>
          </tr>
          <tr>
            <td>204543</td>
            <td>Amwer</td>
            <td>Assisted</td>
            <td>15-Feb-2025</td>
            <td><a href="{{ route('service.details', ['id' => 204543, 'name' => 'Amwer']) }}" class="action-link">Service Details</a></td>
          </tr>
          <tr>
            <td>28275</td>
            <td>Shabaob ali</td>
            <td>Assisted</td>
            <td>09-Jan-2025</td>
            <td><a href="{{ route('service.details', ['id' => 28275, 'name' => 'Shabaob ali']) }}" class="action-link">Service Details</a></td>
          </tr>
          <tr>
            <td>29365</td>
            <td>FECRU MAHAMMED</td>
            <td>Assisted</td>
            <td>03-Jan-2025</td>
            <td><a href="{{ route('service.details', ['id' => 29365, 'name' => 'FECRU MAHAMMED']) }}" class="action-link">Service Details</a></td>
          </tr>
          <tr>
            <td>271548</td>
            <td>Al Idhadi</td>
            <td>Assisted</td>
            <td>03-Jan-2025</td>
            <td><a href="#" class="action-link">Service Details</a></td>
          </tr>
          <tr>
            <td>28113</td>
            <td>Isuka business</td>
            <td>Assisted</td>
            <td>30-Dec-2024</td>
            <td><a href="#" class="action-link">Service Details</a></td>
          </tr>
          <tr>
            <td>18440</td>
            <td>Marmon jiu</td>
            <td>Assisted</td>
            <td>24-Dec-2024</td>
            <td><a href="#" class="action-link">Service Details</a></td>
          </tr>
          <tr>
            <td>204032</td>
            <td>Irmstad</td>
            <td>Assisted</td>
            <td>06-Dec-2024</td>
            <td><a href="#" class="action-link">Service Details</a></td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>

  <script>
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
        alert('Logging out...');
      }
    });

    // Action link functionality
  </script>

</body>
</html>