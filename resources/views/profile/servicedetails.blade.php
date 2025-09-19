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
        <button id="tab-service" class="active" type="button"><span>📋</span>Service</button>
        <button id="tab-member" type="button"><span>👤</span>Member Info</button>
        <button id="tab-partner" type="button"><span>💝</span>Partner Preference</button>
        <button id="tab-contact" type="button"><span>📞</span>Contact Details</button>
      </div>

      <form id="addServiceForm">
  <div id="tab-content-service">
          <!-- Profile ID Section -->
          <div class="profile-id-section">
            <h4>Profile ID</h4>
            <div class="form-row">
              <div class="form-group">
                <input type="text" placeholder="Enter Profile ID" value="">
              </div>
            </div>
          </div>
          <!-- Service Details -->
          <div class="form-row">
            <div class="form-group">
              <label>Service Name</label>
              <input type="text" placeholder="Enter service name">
            </div>
            <div class="form-group">
              <label>Amount Paid</label>
              <input type="number" placeholder="7000.00" step="0.01">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Service Details</label>
              <textarea placeholder="Enter detailed service information"></textarea>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Service Duration (Days)</label>
              <input type="number" placeholder="0">
            </div>
            <div class="form-group">
              <label>Success Fee</label>
              <input type="number" placeholder="0" step="0.01">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Refund Price</label>
              <input type="number" placeholder="0" step="0.01">
            </div>
            <div class="form-group">
              <label>After Payment</label>
              <select>
                <option value="">Select status</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="processing">Processing</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Start Date</label>
              <input type="date">
            </div>
            <div class="form-group">
              <label>Expiry Date</label>
              <input type="date">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>RM Name</label>
              <select>
                <option value="">Select Executive</option>
                <option value="exec1">Executive 1</option>
                <option value="exec2">Executive 2</option>
                <option value="exec3">Executive 3</option>
              </select>
            </div>
          </div>
          <!-- Form Actions -->
          <div class="form-actions">
            <button type="button" class="btn btn-secondary" disabled>Previous</button>
            <button type="button" class="btn btn-primary" id="next-to-member">Next</button>
          </div>
        </div>
        <div id="tab-content-member" style="display:none;">
          <div class="section-header">Member Info</div>
          <div class="form-row">
            <div class="form-group">
              <label>Name</label>
              <input type="text" value="Musthafia" readonly>
            </div>
            <div class="form-group">
              <label>Age</label>
              <input type="text" value="30" readonly>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Education</label>
              <input type="text" value="B.TECH" readonly>
            </div>
            <div class="form-group">
              <label>Occupation</label>
              <input type="text" value="BUSINESS" readonly>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Annual Income</label>
              <input type="text" value="200/2020" readonly>
            </div>
            <div class="form-group">
              <label>Marital Status</label>
              <input type="text" value="News Married" readonly>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Family Status</label>
              <input type="text" value="Weekly" readonly>
            </div>
            <div class="form-group">
              <label>Father Details</label>
              <input type="text" value="Change activity" readonly>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Mother Details</label>
              <input type="text" value="sin" readonly>
            </div>
            <div class="form-group">
              <label>Sibling Details</label>
              <input type="text" value="Dr. notch, phara" readonly>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Caste</label>
              <input type="text" value="" readonly>
            </div>
            <div class="form-group">
              <label>SubCaste</label>
              <input type="text" value="min" readonly>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Eating Habits</label>
              <input type="text" value="All" readonly>
            </div>
            <div class="form-group">
              <label>Country Living In</label>
              <input type="text" value="India" readonly>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Residing State</label>
              <input type="text" value="Kerala" readonly>
            </div>
          </div>
          <div class="form-actions">
            <button type="button" class="btn btn-secondary" id="back-to-service">Previous</button>
            <button type="button" class="btn btn-primary" id="next-to-partner">Next</button>
          </div>
        </div>

        <!-- Partner Preference Tab Content -->
        <div id="tab-content-partner" style="display:none;">
          <div class="section-header">Partner Preference</div>
          <div class="form-row">
            <div class="form-group">
              <label>Preferred Age</label>
              <input type="text" placeholder="Preferred Age">
            </div>
            <div class="form-group">
              <label>Preferred Height</label>
              <input type="text" placeholder="Preferred Height">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Preferred Weight</label>
              <input type="text" placeholder="Preferred Weight">
            </div>
            <div class="form-group">
              <label>Preferred Education</label>
              <input type="text" placeholder="Preferred Education">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Preferred Religion</label>
              <input type="text" placeholder="Preferred Religion">
            </div>
            <div class="form-group">
              <label>Preferred Caste</label>
              <input type="text" placeholder="Preferred Caste">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Preferred Sub Caste</label>
              <input type="text" placeholder="Preferred Sub Caste">
            </div>
            <div class="form-group">
              <label>Preferred Marital Status</label>
              <input type="text" placeholder="Preferred Marital Status">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Preferred Annual Income</label>
              <input type="text" placeholder="Preferred Annual Income">
            </div>
            <div class="form-group">
              <label>Preferred Occupation</label>
              <input type="text" placeholder="Preferred Occupation">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Preferred Family Status</label>
              <input type="text" placeholder="Preferred Family Status">
            </div>
            <div class="form-group">
              <label>Preferred Eating Habits</label>
              <input type="text" placeholder="Preferred Eating Habits">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Preferred Ancestor Origin</label>
              <input type="text" placeholder="Preferred Ancestor Origin">
            </div>
            <div class="form-group">
              <label>Preferred Residing State</label>
              <input type="text" placeholder="Preferred Residing State">
            </div>
          </div>
          <div class="form-actions">
            <button type="button" class="btn btn-secondary" id="back-to-member">Previous</button>
            <button type="button" class="btn btn-primary" id="next-to-contact">Next</button>
          </div>
        </div>

        <!-- Contact Details Tab Content -->
        <div id="tab-content-contact" style="display:none;">
          <div class="section-header">Contact Details</div>
          <div class="form-row">
            <div class="form-group">
              <label>Customer Name</label>
              <input type="text" placeholder="Customer Name">
            </div>
            <div class="form-group">
              <label>Mobile Number</label>
              <input type="text" placeholder="Mobile Number">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Whatsapp No</label>
              <input type="text" placeholder="Whatsapp No">
            </div>
            <div class="form-group">
              <label>Email</label>
              <input type="email" placeholder="Email">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Alternate Contact</label>
              <input type="text" placeholder="Alternate Contact">
            </div>
            <div class="form-group">
              <label>Client</label>
              <input type="text" placeholder="Client">
            </div>
          </div>
          <div class="form-actions">
            <button type="button" class="btn btn-secondary" id="back-to-partner">Previous</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </div>
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
    function showTab(tab) {
      // Remove active from all tab buttons
      document.querySelectorAll('.tab-nav button').forEach(btn => btn.classList.remove('active'));
      // Hide all tab contents
      document.getElementById('tab-content-service').style.display = 'none';
      document.getElementById('tab-content-member').style.display = 'none';
      document.getElementById('tab-content-partner').style.display = 'none';
      document.getElementById('tab-content-contact').style.display = 'none';
      // Show the selected tab
      if(tab === 'service') {
        document.getElementById('tab-service').classList.add('active');
        document.getElementById('tab-content-service').style.display = '';
      } else if(tab === 'member') {
        document.getElementById('tab-member').classList.add('active');
        document.getElementById('tab-content-member').style.display = '';
      } else if(tab === 'partner') {
        document.getElementById('tab-partner').classList.add('active');
        document.getElementById('tab-content-partner').style.display = '';
      } else if(tab === 'contact') {
        document.getElementById('tab-contact').classList.add('active');
        document.getElementById('tab-content-contact').style.display = '';
      }
    }

    document.getElementById('tab-service').addEventListener('click', function() {
      showTab('service');
    });
    document.getElementById('tab-member').addEventListener('click', function() {
      showTab('member');
    });
    document.getElementById('tab-partner').addEventListener('click', function() {
      showTab('partner');
    });
    document.getElementById('tab-contact').addEventListener('click', function() {
      showTab('contact');
    });

    // Next button to Member Info
    document.getElementById('next-to-member').addEventListener('click', function() {
      showTab('member');
    });
    // Next button to Partner Preference
    document.getElementById('next-to-partner').addEventListener('click', function() {
      showTab('partner');
    });
    // Next button to Contact Details
    document.getElementById('next-to-contact').addEventListener('click', function() {
      showTab('contact');
    });
    // Previous button to Service
    var backBtn = document.getElementById('back-to-service');
    if (backBtn) {
      backBtn.addEventListener('click', function() {
        showTab('service');
      });
    }
    // Previous button to Member Info
    var backToMemberBtn = document.getElementById('back-to-member');
    if (backToMemberBtn) {
      backToMemberBtn.addEventListener('click', function() {
        showTab('member');
      });
    }
    // Previous button to Partner Preference
    var backToPartnerBtn = document.getElementById('back-to-partner');
    if (backToPartnerBtn) {
      backToPartnerBtn.addEventListener('click', function() {
        showTab('partner');
      });
    }

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