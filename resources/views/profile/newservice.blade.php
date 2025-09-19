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

    /* Add Service Button Styling */
    .add-service-btn-beautiful {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
      border-color: #667eea;
      background: white;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
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
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
      <h1 class="page-title">New Services</h1>
      <button id="add-new-service-btn" class="add-service-btn-beautiful">
        <span>✨</span> Add New Service
      </button>
    </div>

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
              <label>Full Name</label>
              <input type="text" name="name" placeholder="Enter full name" required>
            </div>
          </div>
          <div class="modal-form-row">
            <div class="modal-form-group">
              <label>Gender</label>
              <select name="gender" required>
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div class="modal-form-group">
              <label>Mobile Number</label>
              <input type="tel" name="mobile" placeholder="Enter mobile number" required>
            </div>
          </div>
          <div class="modal-form-row">
            <div class="modal-form-group">
              <label>Plan Name</label>
              <select name="plan_name" required>
                <option value="">Select Plan</option>
                <option value="assisted">Assisted</option>
                <option value="premium">Premium</option>
                <option value="standard">Standard</option>
                <option value="basic">Basic</option>
              </select>
            </div>
            <div class="modal-form-group">
              <label>Payment Date</label>
              <input type="date" name="payment_date" required>
            </div>
          </div>
          <div class="modal-form-row">
            <div class="modal-form-group">
              <label>Service Executive</label>
              <input type="text" name="service_executive" placeholder="Enter service executive name" required>
            </div>
            <div class="modal-form-group">
              <label>Email Address</label>
              <input type="email" name="email" placeholder="Enter email address">
            </div>
          </div>
          <div class="modal-form-row">
            <div class="modal-form-group">
              <label>Address</label>
              <input type="text" name="address" placeholder="Enter address">
            </div>
          </div>
          <div class="modal-actions">
            <button type="button" id="close-modal-btn" class="btn btn-secondary">Cancel</button>
            <button type="submit" class="btn btn-primary">Add Service</button>
          </div>
        </form>
      </div>
    </div>

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
        <tbody id="services-tbody">
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
      
      // Get form data
      const formData = new FormData(this);
      const profileId = formData.get('profile_id');
      const name = formData.get('name');
      const planName = formData.get('plan_name');
      const paymentDate = formData.get('payment_date');
      
      // Format payment date
      const date = new Date(paymentDate);
      const formattedDate = date.toLocaleDateString('en-GB', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
      });

      // Create new table row
      const newRow = document.createElement('tr');
      newRow.innerHTML = `
        <td>${profileId}</td>
        <td>${name}</td>
        <td>${planName}</td>
        <td>${formattedDate}</td>
        <td><a href="#" class="action-link">Service Details</a></td>
      `;

      // Add animation for new row
      newRow.style.backgroundColor = 'rgba(102, 126, 234, 0.2)';
      newRow.style.animation = 'fadeIn 0.5s ease';

      // Add to beginning of table
      servicesTableBody.insertBefore(newRow, servicesTableBody.firstChild);

      // Reset background color after animation
      setTimeout(() => {
        newRow.style.backgroundColor = '';
        newRow.style.animation = '';
      }, 1000);

      // Show success message
      showNotification('Service added successfully!', 'success');

      // Close modal and reset form
      addServiceModal.classList.remove('active');
      document.body.style.overflow = 'auto';
      this.reset();
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