<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>INA Dashboard - Services</title>
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

    .card-icon.active {
      background: linear-gradient(135deg, #2196F3, #1976D2);
    }

    .card-icon.new {
      background: linear-gradient(135deg, #FF9800, #F57C00);
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

    /* Services Table Container */
    .services-section {
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
      gap: 15px;
      margin-bottom: 20px;
      padding-bottom: 15px;
      border-bottom: 2px solid #e0e0e0;
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

    /* Service status badges */
    .service-status {
      padding: 6px 12px;
      border-radius: 15px;
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .status-elite {
      background: linear-gradient(135deg, #e8f5e8, #4CAF50);
      color: #1b5e20;
      box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
    }

    .status-assisted {
      background: linear-gradient(135deg, #fff3e0, #ffcc02);
      color: #e65100;
      box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
    }

    /* Profile ID links */
    .profile-link {
      color: #2196F3;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }

    .profile-link:hover {
      color: #1976D2;
      text-decoration: underline;
    }

    /* Action buttons */
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
      margin-bottom: 20px;
      width: fit-content;
    }

    .btn-add:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(76, 175, 80, 0.6);
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
    }
  </style>
</head>
<body>
  <!-- Add New Service Button -->
  <button id="add-new-service-btn" class="btn btn-primary" style="margin: 20px 0;">Add New Service</button>

  <!-- Add New Service Modal -->
  <div id="add-service-modal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:2000; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:10px; padding:30px; max-width:400px; margin:auto; position:relative;">
      <h2 style="margin-bottom:20px;">Add New Service</h2>
      <form id="add-service-form-modal">
        <div class="form-group">
          <label>Profile ID</label>
          <input type="text" name="profile_id" required>
        </div>
        <div class="form-group">
          <label>Name</label>
          <input type="text" name="name" required>
        </div>
        <div class="form-group">
          <label>Gender</label>
          <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
          </select>
        </div>
        <div class="form-group">
          <label>Mobile Number</label>
          <input type="text" name="mobile" required>
        </div>
        <div class="form-group">
          <label>Service Executive</label>
          <input type="text" name="service_executive" required>
        </div>
        <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
          <button type="button" id="close-modal-btn" class="btn btn-secondary">Cancel</button>
          <button type="submit" class="btn btn-primary">Add</button>
        </div>
      </form>
    </div>
  </div>

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
        <li><a href="{{ route('services.page') }}" class="nav-link active">Services <span class="dropdown-arrow">▼</span></a></li>
      </ul>
    </nav>
    
    <button class="logout-btn">Logout</button>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
      @csrf
    </form>
    <button class="logout-btn" id="logout-btn">Logout</button>
  </header>

  <!-- Main Content Area -->
  <main class="main-content">
    <h1 class="page-title">Service Dashboard</h1>

    <!-- Dashboard Cards -->
    <div class="dashboard-cards">
      <div class="dashboard-card">
        <div class="card-icon total">📊</div>
        <div class="card-content">
          <h3>19</h3>
          <p>Total Services</p>
        </div>
      </div>


      <a href="{{ route('active.service') }}" style="text-decoration:none; color:inherit;">
        <div class="dashboard-card">
          <div class="card-icon active">👥</div>
          <div class="card-content">
            <h3>11</h3>
            <p>Active Services</p>
          </div>
        </div>
      </a>

      <a href="{{ route('new.service') }}" style="text-decoration:none; color:inherit;">
        <div class="dashboard-card">
          <div class="card-icon new">⚡</div>
          <div class="card-content">
            <h3>26</h3>
            <p>New Services</p>
          </div>
        </div>
      </a>
    </div>


    <!-- Recent Services Section -->
    <div class="services-section">
      <div class="section-header">
        <div class="section-icon">📋</div>
        <h2 class="section-title">Recent Services</h2>
      </div>

      <table>
        <thead>
          <tr>
            <th>Service ID</th>
            <th>Profile ID</th>
            <th>Service Name</th>
            <th>Created Date</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>24</strong></td>
            <td><a href="#" class="profile-link">37904</a></td>
            <td>Elite</td>
            <td>13-Sep-2025</td>
          </tr>
          <tr>
            <td><strong>23</strong></td>
            <td><a href="#" class="profile-link">51139</a></td>
            <td>Elite</td>
            <td>12-Sep-2025</td>
          </tr>
          <tr>
            <td><strong>22</strong></td>
            <td><a href="#" class="profile-link">41519</a></td>
            <td>Assisted</td>
            <td>09-Aug-2025</td>
          </tr>
          <tr>
            <td><strong>21</strong></td>
            <td><a href="#" class="profile-link">30955</a></td>
            <td>Elite</td>
            <td>18-Jul-2025</td>
          </tr>
          <tr>
            <td><strong>20</strong></td>
            <td><a href="#" class="profile-link">29628</a></td>
            <td>Elite</td>
            <td>17-Jul-2025</td>
          </tr>
          <tr>
            <td><strong>19</strong></td>
            <td><a href="#" class="profile-link">87024</a></td>
            <td>Elite</td>
            <td>16-Jul-2025</td>
          </tr>
          <tr>
            <td><strong>18</strong></td>
            <td><a href="#" class="profile-link">40174</a></td>
            <td>Assisted</td>
            <td>05-Jul-2025</td>
          </tr>
          <tr>
            <td><strong>17</strong></td>
            <td><a href="#" class="profile-link">41785</a></td>
            <td>Assisted</td>
            <td>05-Jul-2025</td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>

  <script>
    // Logout functionality
    document.getElementById('logout-btn').addEventListener('click', function() {
      if(confirm('Are you sure you want to logout?')) {
        document.getElementById('logout-form').submit();
      }
    });
    // Modal logic for Add New Service
    const addNewServiceBtn = document.getElementById('add-new-service-btn');
    const addServiceModal = document.getElementById('add-service-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');
    addNewServiceBtn.addEventListener('click', function() {
      addServiceModal.style.display = 'flex';
    });
    closeModalBtn.addEventListener('click', function() {
      addServiceModal.style.display = 'none';
    });
    // Optional: Close modal on outside click
    addServiceModal.addEventListener('click', function(e) {
      if (e.target === addServiceModal) {
        addServiceModal.style.display = 'none';
      }
    });
    // Handle form submit (demo only)
    document.getElementById('add-service-form-modal').addEventListener('submit', function(e) {
      e.preventDefault();
      alert('Service added!');
      addServiceModal.style.display = 'none';
      this.reset();
    });
    // No SPA navigation: allow browser to follow real links for navigation

    // Logout functionality
    document.querySelector('.logout-btn').addEventListener('click', function() {
      if(confirm('Are you sure you want to logout?')) {
        alert('Logging out...');
        // Add logout logic here
      }
    });

    // Page loading functions
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
      document.querySelector('.main-content').innerHTML = `
        <h1 class="page-title">Profiles</h1>
        <div style="color: white; text-align: center; margin-top: 50px;">
          <h2>Profiles Management</h2>
          <p>Profile management tools would appear here</p>
        </div>
      `;
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
      // Reload the current services page
      location.reload();
    }


    // View button functionality
    document.querySelectorAll('button').forEach(button => {
      if (button.textContent === 'View') {
        button.addEventListener('click', function() {
          const row = this.closest('tr');
          const serviceId = row.querySelector('td:first-child strong').textContent;
          alert('Viewing details for Service ID: ' + serviceId);
        });
      }
    });

    // Profile link functionality
    document.querySelectorAll('.profile-link').forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        const profileId = this.textContent;
        alert('Navigating to Profile ID: ' + profileId);
      });
    });
  </script>

</body>
</html>