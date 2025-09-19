<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>INA Dashboard - Add Service</title>
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

    /* Main Dashboard Header - Same as profile page */
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
      max-width: 1200px;
      margin: 0 auto;
    }

    .page-title {
      color: #fff;
      font-size: 28px;
      font-weight: 600;
      margin-bottom: 25px;
      text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    /* Form Container */
    .form-container {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      max-width: 800px;
      margin: 0 auto;
    }

    /* Tab Navigation */
    .tab-nav {
      display: flex;
      margin-bottom: 30px;
      border-bottom: 2px solid #e0e0e0;
    }

    .tab-nav button {
      background: none;
      border: none;
      padding: 12px 20px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 500;
      color: #666;
      border-bottom: 3px solid transparent;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .tab-nav button:hover {
      color: #333;
      background: rgba(0,0,0,0.05);
    }

    .tab-nav button.active {
      color: #4CAF50;
      border-bottom-color: #4CAF50;
      background: rgba(76, 175, 80, 0.05);
    }

    /* Form Layout */
    .form-row {
      display: flex;
      gap: 20px;
      margin-bottom: 20px;
    }

    .form-group {
      flex: 1;
    }

    .form-group.half {
      flex: 0.5;
    }

    .form-group label {
      display: block;
      margin-bottom: 6px;
      font-weight: 500;
      color: #333;
      font-size: 14px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 10px 12px;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      font-size: 14px;
      transition: all 0.3s ease;
      background: white;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
      outline: none;
      border-color: #4CAF50;
      box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
    }

    .form-group textarea {
      resize: vertical;
      min-height: 80px;
    }

    /* Section Headers */
    .section-header {
      background: linear-gradient(135deg, #f8fbff, #e8f4fd);
      padding: 12px 20px;
      margin: 25px -30px 20px -30px;
      border-left: 4px solid #4CAF50;
      font-weight: 600;
      color: #2c3e50;
      font-size: 16px;
    }

    /* Action Buttons */
    .form-actions {
      display: flex;
      justify-content: flex-end;
      gap: 15px;
      margin-top: 30px;
      padding-top: 20px;
      border-top: 1px solid #e0e0e0;
    }

    .btn {
      padding: 12px 25px;
      border: none;
      border-radius: 25px;
      font-weight: 600;
      cursor: pointer;
      font-size: 14px;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn-primary {
      background: linear-gradient(135deg, #4CAF50, #45a049);
      color: white;
      box-shadow: 0 4px 15px rgba(76, 175, 80, 0.4);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(76, 175, 80, 0.6);
    }

    .btn-secondary {
      background: linear-gradient(135deg, #6c757d, #5a6268);
      color: white;
      box-shadow: 0 4px 15px rgba(108, 117, 125, 0.4);
    }

    .btn-secondary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(108, 117, 125, 0.6);
    }

    /* Profile ID Section */
    .profile-id-section {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      border-left: 4px solid #007bff;
    }

    .profile-id-section h4 {
      color: #007bff;
      margin-bottom: 10px;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .form-row {
        flex-direction: column;
        gap: 15px;
      }
      
      .form-container {
        margin: 0 15px;
        padding: 20px;
      }
      
      .main-content {
        padding: 15px;
      }
      
      .tab-nav {
        overflow-x: auto;
        white-space: nowrap;
      }
      
      .form-actions {
        flex-direction: column;
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
        <li><a href="#" data-page="dashboard">Home</a></li>
        <li><a href="#" data-page="profiles">Profiles</a></li>
        <li><a href="#" data-page="sales">Sales <span class="dropdown-arrow">▼</span></a></li>
        <li><a href="#" data-page="helpline">HelpLine</a></li>
        <li><a href="#" data-page="fresh-data">Fresh Data <span class="dropdown-arrow">▼</span></a></li>
        <li><a href="#" data-page="abc">abc</a></li>
        <li><a href="#" data-page="services" class="active">Services <span class="dropdown-arrow">▼</span></a></li>
      </ul>
    </nav>
    
    <button class="logout-btn">Logout</button>
  </header>

  <!-- Main Content Area -->
  <main class="main-content">
    <h1 class="page-title">Add Service</h1>

    <div class="form-container">
      <!-- Tab Navigation -->
      <div class="tab-nav">
        <button class="active">
          <span>📋</span>
          Service
        </button>
        <button>
          <span>👤</span>
          Member Info
        </button>
        <button>
          <span>💝</span>
          Partner Preference
        </button>
        <button>
          <span>📞</span>
          Contact Details
        </button>
      </div>

      <form id="addServiceForm">
        <!-- Profile ID Section -->
        <div class="profile-id-section">
          <h4>Profile ID</h4>
          <div class="form-row">
            <div class="form-group">
              <input type="text" placeholder="Enter Profile ID" value="">
            </div>
          </div>
        </div>

        <!-- Service Details Section -->
        <div class="section-header">Service Details</div>
        
        <div class="form-row">
          <div class="form-group">
            <label>Service Name</label>
            <input type="text" placeholder="Enter service name">
          </div>
          <div class="form-group">
            <label>Booking</label>
            <select>
              <option value="">Select booking type</option>
              <option value="online">Online</option>
              <option value="offline">Offline</option>
              <option value="phone">Phone</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Service Details</label>
            <textarea placeholder="Enter detailed service information"></textarea>
          </div>
        </div>

        <!-- Pricing Section -->
        <div class="section-header">Pricing Information</div>
        
        <div class="form-row">
          <div class="form-group">
            <label>Amount Paid</label>
            <input type="number" placeholder="7000.00" step="0.01">
          </div>
          <div class="form-group">
            <label>Service Description Days</label>
            <input type="number" placeholder="0">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Refund Price</label>
            <input type="number" placeholder="0" step="0.01">
          </div>
          <div class="form-group">
            <label>Success Fee</label>
            <input type="number" placeholder="0" step="0.01">
          </div>
        </div>

        <!-- Payment Details Section -->
        <div class="section-header">Payment Details</div>
        
        <div class="form-row">
          <div class="form-group">
            <label>After Payment</label>
            <select>
              <option value="">Select status</option>
              <option value="pending">Pending</option>
              <option value="confirmed">Confirmed</option>
              <option value="processing">Processing</option>
            </select>
          </div>
          <div class="form-group">
            <label>Expiry Date</label>
            <input type="date">
          </div>
        </div>

        <!-- Additional Information -->
        <div class="form-row">
          <div class="form-group">
            <label>Start Date</label>
            <input type="date">
          </div>
          <div class="form-group">
            <label>Net Master</label>
            <input type="text" placeholder="Enter net master details">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Extra Features</label>
            <textarea placeholder="Enter any additional features or notes"></textarea>
          </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
          <button type="button" class="btn btn-secondary">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <span>💾</span>
            Save Service
          </button>
        </div>
      </form>
    </div>
  </main>

  <script>
    // Navigation functionality - same as profile page
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

    // Tab navigation
    document.querySelectorAll('.tab-nav button').forEach(button => {
      button.addEventListener('click', function() {
        document.querySelectorAll('.tab-nav button').forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
        
        // Here you can add logic to show/hide different form sections
        const tabText = this.textContent.trim();
        console.log('Switched to tab:', tabText);
      });
    });

    // Form submission
    document.getElementById('addServiceForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Get form data
      const formData = new FormData(this);
      
      // Show success message
      alert('Service added successfully!');
      
      // Here you would typically send the data to your server
      console.log('Form submitted');
    });

    // Cancel button
    document.querySelector('.btn-secondary').addEventListener('click', function() {
      if(confirm('Are you sure you want to cancel? All unsaved changes will be lost.')) {
        // Redirect back to services page or clear form
        location.href = '#services';
      }
    });
  </script>

</body>
</html>