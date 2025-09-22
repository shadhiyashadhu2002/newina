<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
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

    /* View Mode Styling */
    .form-group input[readonly],
    .form-group textarea[readonly] {
      background-color: #f8f9fa;
      border-color: #e9ecef;
      color: #495057;
      cursor: not-allowed;
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

    .btn-info {
      background: linear-gradient(135deg, #17a2b8, #138496);
      color: white;
      box-shadow: 0 4px 15px rgba(23, 162, 184, 0.4);
    }

    .btn-info:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(23, 162, 184, 0.6);
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

    /* Status Badge */
    .status-badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .status-badge.view-mode {
      background: #e3f2fd;
      color: #1976d2;
    }

    .status-badge.edit-mode {
      background: #fff3e0;
      color: #f57c00;
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
    <!-- Single unified form for both viewing and editing -->
    <div id="unified-service-form">
      <h1 class="page-title">{{ isset($service) ? 'Service Details' : 'Add New Service' }}</h1>
      <div class="form-container">
        <!-- Action buttons at the top -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
          <div>
            @if(isset($service))
              <span class="status-badge view-mode">Viewing Service</span>
            @else
              <span class="status-badge edit-mode">Adding New Service</span>
            @endif
          </div>
          <div>
            @if(isset($service))
              <button type="button" class="btn btn-info" id="edit-service-btn" style="margin-right: 10px;">Edit Service</button>
            @endif
            <a href="/new-service" class="btn btn-secondary">Back to Services</a>
          </div>
        </div>
        <!-- Tab Navigation -->
        <div class="tab-nav">
          <button id="tab-service" class="active" type="button"><span>📋</span>Service</button>
          <button id="tab-member" type="button"><span>👤</span>Member Info</button>
          <button id="tab-partner" type="button"><span>💝</span>Partner Preference</button>
          <button id="tab-contact" type="button"><span>📞</span>Contact Details</button>
        </div>

        <form id="addServiceForm" method="POST" action="{{ route('new.service.store') }}">
          @csrf
          <div id="step-1" class="wizard-step">
            <h3>Service Details</h3>
            <div class="profile-id-section">
              <h4>Profile ID</h4>
              <div class="form-row">
                <div class="form-group">
                  <input type="text" name="profile_id" id="profile-id-input" placeholder="Profile ID" 
                         value="{{ isset($service) ? $service->profile_id : 'INA001' }}" 
                         {{ isset($service) ? 'readonly' : 'readonly' }}>
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Service Name</label>
                <input type="text" name="service_name" id="service-name-input" placeholder="Enter service name" 
                       value="{{ isset($service) ? $service->service_name : '' }}" 
                       {{ isset($service) ? 'readonly' : 'required' }}>
              </div>
              <div class="form-group">
                <label>Price</label>
                <input type="number" name="amount_paid" id="amount-paid-input" placeholder="7000.00" step="0.01" 
                       value="{{ isset($service) ? $service->amount_paid : '' }}" 
                       {{ isset($service) ? 'readonly' : 'required' }}>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Success Fee</label>
                <input type="number" name="success_fee" id="success-fee-input" placeholder="0" step="0.01" 
                       value="{{ isset($service) ? $service->success_fee : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
              <div class="form-group">
                <label>Start Date</label>
                <input type="date" name="start_date" id="start-date-input" 
                       value="{{ isset($service) ? $service->start_date : '' }}" 
                       {{ isset($service) ? 'readonly' : 'required' }}>
              </div>
              <div class="form-group">
                <label>Expiry Date</label>
                <input type="date" name="expiry_date" id="expiry-date-input" 
                       value="{{ isset($service) ? $service->expiry_date : '' }}" 
                       {{ isset($service) ? 'readonly' : 'required' }}>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group" style="width:100%">
                <label>Description</label>
                <textarea name="service_details" id="service-details-input" placeholder="Enter detailed service information" 
                          {{ isset($service) ? 'readonly' : '' }}>{{ isset($service) ? $service->service_details : '' }}</textarea>
              </div>
            </div>
            <div class="form-actions" style="{{ isset($service) ? 'display: none;' : '' }}">
              <button type="button" class="btn btn-info" onclick="testAjax()" style="margin-right: 10px;">Test AJAX</button>
              <button type="button" class="btn btn-primary" id="save-next-1">Save & Next</button>
            </div>
          </div>

          <div id="step-2" class="wizard-step" style="display:none;">
            <h3>Member Info</h3>
            <div class="form-row">
              <div class="form-group">
                <label>Name</label>
                <input type="text" name="member_name" id="member-name-input" placeholder="Name" 
                       value="{{ isset($service) ? $service->member_name : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
              <div class="form-group">
                <label>Age</label>
                <input type="number" name="member_age" id="member-age-input" placeholder="Age" 
                       value="{{ isset($service) ? $service->member_age : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Education</label>
                <input type="text" name="member_education" id="member-education-input" placeholder="Education" 
                       value="{{ isset($service) ? $service->member_education : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
              <div class="form-group">
                <label>Occupation</label>
                <input type="text" name="member_occupation" id="member-occupation-input" placeholder="Occupation" 
                       value="{{ isset($service) ? $service->member_occupation : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Annual Income</label>
                <input type="text" name="member_income" id="member-income-input" placeholder="Annual Income" 
                       value="{{ isset($service) ? $service->member_income : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
              <div class="form-group">
                <label>Marital Status</label>
                <input type="text" name="member_marital_status" id="member-marital-status-input" placeholder="Marital Status" 
                       value="{{ isset($service) ? $service->member_marital_status : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Family Status</label>
                <input type="text" name="member_family_status" id="member-family-status-input" placeholder="Family Status" 
                       value="{{ isset($service) ? $service->member_family_status : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
              <div class="form-group">
                <label>Father Details</label>
                <input type="text" name="member_father_details" id="member-father-details-input" placeholder="Father Details" 
                       value="{{ isset($service) ? $service->member_father_details : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Mother Details</label>
                <input type="text" name="member_mother_details" id="member-mother-details-input" placeholder="Mother Details" 
                       value="{{ isset($service) ? $service->member_mother_details : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
              <div class="form-group">
                <label>Sibling Details</label>
                <input type="text" name="member_sibling_details" id="member-sibling-details-input" placeholder="Sibling Details" 
                       value="{{ isset($service) ? $service->member_sibling_details : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Caste</label>
                <input type="text" name="member_caste" id="member-caste-input" placeholder="Caste" 
                       value="{{ isset($service) ? $service->member_caste : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
              <div class="form-group">
                <label>Subcaste</label>
                <input type="text" name="member_subcaste" id="member-subcaste-input" placeholder="Subcaste" 
                       value="{{ isset($service) ? $service->member_subcaste : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
            </div>
            <div class="form-actions" style="{{ isset($service) ? 'display: none;' : '' }}">
              <button type="button" class="btn btn-secondary" id="back-2">Back</button>
              <button type="button" class="btn btn-primary" id="save-next-2">Save & Next</button>
            </div>
          </div>

          <div id="step-3" class="wizard-step" style="display:none;">
            <h3>Partner Preferences</h3>
            <div class="form-row">
              <div class="form-group">
                <label>Preferred Age</label>
                <input type="text" name="preferred_age" id="preferred-age-input" placeholder="Preferred Age" 
                       value="{{ isset($service) ? $service->preferred_age : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
              <div class="form-group">
                <label>Preferred Weight</label>
                <input type="text" name="preferred_weight" id="preferred-weight-input" placeholder="Preferred Weight" 
                       value="{{ isset($service) ? $service->preferred_weight : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Preferred Education</label>
                <input type="text" name="preferred_education" id="preferred-education-input" placeholder="Preferred Education" 
                       value="{{ isset($service) ? $service->preferred_education : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
              <div class="form-group">
                <label>Preferred Religion</label>
                <input type="text" name="preferred_religion" id="preferred-religion-input" placeholder="Preferred Religion" 
                       value="{{ isset($service) ? $service->preferred_religion : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Preferred Caste</label>
                <input type="text" name="preferred_caste" id="preferred-caste-input" placeholder="Preferred Caste" 
                       value="{{ isset($service) ? $service->preferred_caste : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
              <div class="form-group">
                <label>Preferred Subcaste</label>
                <input type="text" name="preferred_subcaste" id="preferred-subcaste-input" placeholder="Preferred Subcaste" 
                       value="{{ isset($service) ? $service->preferred_subcaste : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Preferred Marital Status</label>
                <input type="text" name="preferred_marital_status" id="preferred-marital-status-input" placeholder="Preferred Marital Status" 
                       value="{{ isset($service) ? $service->preferred_marital_status : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
              <div class="form-group">
                <label>Preferred Annual Income</label>
                <input type="text" name="preferred_annual_income" id="preferred-annual-income-input" placeholder="Preferred Annual Income" 
                       value="{{ isset($service) ? $service->preferred_annual_income : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Preferred Occupation</label>
                <input type="text" name="preferred_occupation" id="preferred-occupation-input" placeholder="Preferred Occupation" 
                       value="{{ isset($service) ? $service->preferred_occupation : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
              <div class="form-group">
                <label>Preferred Family Status</label>
                <input type="text" name="preferred_family_status" id="preferred-family-status-input" placeholder="Preferred Family Status" 
                       value="{{ isset($service) ? $service->preferred_family_status : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Preferred Eating Habits</label>
                <input type="text" name="preferred_eating_habits" id="preferred-eating-habits-input" placeholder="Preferred Eating Habits" 
                       value="{{ isset($service) ? $service->preferred_eating_habits : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
            </div>
            <div class="form-actions" style="{{ isset($service) ? 'display: none;' : '' }}">
              <button type="button" class="btn btn-secondary" id="back-3">Back</button>
              <button type="button" class="btn btn-primary" id="save-next-3">Save & Next</button>
            </div>
          </div>

          <div id="step-4" class="wizard-step" style="display:none;">
            <h3>Contact Details</h3>
            <div class="form-row">
              <div class="form-group">
                <label>Customer Name</label>
                <input type="text" name="contact_customer_name" id="contact-customer-name-input" placeholder="Customer Name" 
                       value="{{ isset($service) ? $service->contact_customer_name : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
              <div class="form-group">
                <label>Mobile No</label>
                <input type="text" name="contact_mobile_no" id="contact-mobile-no-input" placeholder="Mobile No" 
                       value="{{ isset($service) ? $service->contact_mobile_no : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>WhatsApp No</label>
                <input type="text" name="contact_whatsapp_no" id="contact-whatsapp-no-input" placeholder="WhatsApp No" 
                       value="{{ isset($service) ? $service->contact_whatsapp_no : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
              <div class="form-group">
                <label>Email</label>
                <input type="email" name="contact_email" id="contact-email-input" placeholder="Email" 
                       value="{{ isset($service) ? $service->contact_email : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Alternate Contact</label>
                <input type="text" name="contact_alternate" id="contact-alternate-input" placeholder="Alternate Contact" 
                       value="{{ isset($service) ? $service->contact_alternate : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
              <div class="form-group">
                <label>Client</label>
                <input type="text" name="contact_client" id="contact-client-input" placeholder="Client" 
                       value="{{ isset($service) ? $service->contact_client : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }}>
              </div>
            </div>
            <div class="form-actions" style="{{ isset($service) ? 'display: none;' : '' }}">
              <button type="button" class="btn btn-secondary" id="back-4">Back</button>
              <button type="button" class="btn btn-primary" id="save-final">Save & Complete</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </main>

  <script>
    // Check if we're in view mode or edit mode
    const isViewMode = {{ isset($service) ? 'true' : 'false' }};
    
    // Initialize the page
    function initializePage() {
      if (!isViewMode) {
        // Set default profile ID for new services
        document.getElementById('profile-id-input').value = 'INA001';
        
        // Set up form navigation for new services
        setupFormNavigation();
      } else {
        // Set up tab navigation for viewing existing services
        setupTabNavigation();
        
        // Set up edit button functionality
        document.getElementById('edit-service-btn').onclick = function() {
          enableEditMode();
        };
      }
    }

    // Enable edit mode
    function enableEditMode() {
      // Remove readonly from all form inputs
      const inputs = document.querySelectorAll('#unified-service-form input, #unified-service-form textarea');
      inputs.forEach(input => {
        input.removeAttribute('readonly');
      });
      
      // Show save buttons
      const formActions = document.querySelectorAll('.form-actions');
      formActions.forEach(action => {
        action.style.display = 'flex';
      });
      
      // Update the status badge
      const badge = document.querySelector('.status-badge');
      badge.textContent = 'Editing Service';
      badge.className = 'status-badge edit-mode';
      
      // Hide edit button, show save button
      document.getElementById('edit-service-btn').style.display = 'none';
      
      // Set up form navigation for editing
      setupFormNavigation();
    }

    // Set up tab navigation (for viewing mode)
    function setupTabNavigation() {
      const tabs = ['tab-service', 'tab-member', 'tab-partner', 'tab-contact'];
      const steps = ['step-1', 'step-2', 'step-3', 'step-4'];
      
      tabs.forEach((tabId, index) => {
        document.getElementById(tabId).onclick = function() {
          // Remove active from all tabs
          tabs.forEach(id => document.getElementById(id).classList.remove('active'));
          // Hide all steps
          steps.forEach(id => document.getElementById(id).style.display = 'none');
          
          // Show selected tab and step
          this.classList.add('active');
          document.getElementById(steps[index]).style.display = 'block';
        };
      });
    }

    // Set up form navigation (for add/edit mode)
    function setupFormNavigation() {
      // Tab switching
      const tabs = ['tab-service', 'tab-member', 'tab-partner', 'tab-contact'];
      const steps = ['step-1', 'step-2', 'step-3', 'step-4'];
      
      tabs.forEach((tabId, index) => {
        document.getElementById(tabId).onclick = function() {
          showStep(index + 1);
        };
      });

      // Save & Next buttons
      document.getElementById('save-next-1').onclick = function() {
        saveCurrentSection(1);
      };

      document.getElementById('save-next-2').onclick = function() {
        saveCurrentSection(2);
      };

      document.getElementById('save-next-3').onclick = function() {
        saveCurrentSection(3);
      };

      // Back buttons
      document.getElementById('back-2').onclick = function() {
        showStep(1);
      };

      document.getElementById('back-3').onclick = function() {
        showStep(2);
      };

      document.getElementById('back-4').onclick = function() {
        showStep(3);
      };

      // Final Save button
      document.getElementById('save-service').onclick = function() {
        saveCurrentSection(4);
      };

      // Back to Services button
      document.getElementById('back-to-services').onclick = function() {
        window.location.href = '/profile';
      };
    }

    // Show specific step
    function showStep(step) {
      // Hide all steps
      for (let i = 1; i <= 4; i++) {
        document.getElementById('step-' + i).style.display = 'none';
      }
      
      // Show target step
      document.getElementById('step-' + step).style.display = 'block';
      
      // Update tab states
      const tabs = ['tab-service', 'tab-member', 'tab-partner', 'tab-contact'];
      tabs.forEach((tabId, index) => {
        const tab = document.getElementById(tabId);
        if (index === step - 1) {
          tab.classList.add('active');
        } else {
          tab.classList.remove('active');
        }
      });
    }

    // Save current section and proceed
    function saveCurrentSection(currentStep) {
      const formData = new FormData(document.getElementById('unified-service-form'));
      formData.append('current_step', currentStep);

      fetch('/save-service', {
        method: 'POST',
        body: formData,
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          console.log('Section saved successfully');
          
          if (currentStep < 4) {
            showStep(currentStep + 1);
          } else {
            // Final save - redirect to services list
            alert('Service saved successfully!');
            window.location.href = '/profile';
          }
        } else {
          console.error('Save failed:', data.message);
          alert('Failed to save: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving.');
      });
    }

    // Initialize page when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
      initializePage();
    });

    // Function to refresh CSRF token
    function refreshCSRFToken() {
      return fetch('/csrf-token', {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.csrf_token) {
          const metaTag = document.querySelector('meta[name="csrf-token"]');
          if (metaTag) {
            metaTag.setAttribute('content', data.csrf_token);
          }
          return data.csrf_token;
        }
        throw new Error('Failed to get CSRF token');
      });
    }
  </script>

  <script>
    // Set up form navigation for add/edit mode
    function setupFormNavigation() {
      // CSRF Token for AJAX requests
      const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
      const csrfToken = csrfTokenElement ? csrfTokenElement.getAttribute('content') : '';
      
      if (!csrfToken) {
        console.error('CSRF token not found. Please refresh the page.');
        alert('Security token missing. Please refresh the page and try again.');
        return;
      }

      // Progressive save function with CSRF retry
      function saveSection(section, data, callback, retryCount = 0) {
        // Show loading state
        const saveBtn = event.target;
        const originalText = saveBtn.textContent;
        saveBtn.textContent = 'Saving...';
        saveBtn.disabled = true;

        // Get fresh CSRF token
        const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfTokenElement ? csrfTokenElement.getAttribute('content') : '';
        
        if (!csrfToken) {
          console.error('CSRF token not found. Please refresh the page.');
          alert('Security token missing. Please refresh the page and try again.');
          saveBtn.textContent = originalText;
          saveBtn.disabled = false;
          return;
        }

        console.log('Saving section:', section, 'Data:', data);
        console.log('CSRF Token:', csrfToken.substring(0, 10) + '...');

        fetch('/save-service', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify({
            ...data,
            section: section
          })
        })
        .then(response => {
          console.log('Response status:', response.status);
          if (!response.ok) {
            if (response.status === 419 && retryCount < 1) {
              // CSRF token expired, try to refresh and retry once
              console.log('CSRF token expired, refreshing token and retrying...');
              return refreshCSRFToken().then(() => {
                // Retry the save operation with fresh token
                return saveSection(section, data, callback, retryCount + 1);
              });
            }
            if (response.status === 419) {
              throw new Error('CSRF token expired. Please refresh the page and try again.');
            }
            throw new Error('Network response was not ok: ' + response.status);
          }
          return response.json();
        })
        .then(data => {
          console.log('Server response:', data);
          if (data.success) {
            console.log('Section saved successfully:', section);
            callback();
          } else {
            alert('Error saving data: ' + (data.message || 'Unknown error'));
          }
        })
        .catch(error => {
          console.error('Error:', error);
          if (error.message.includes('CSRF token expired')) {
            alert('Your session has expired. Please refresh the page and try again.');
            // Optionally auto-refresh the page
            // window.location.reload();
          } else {
            alert('Network error occurred while saving: ' + error.message);
          }
        })
        .finally(() => {
          // Restore button state
          saveBtn.textContent = originalText;
          saveBtn.disabled = false;
        });
      }

      // Wizard navigation with tab highlight
      function setActiveTab(tabId) {
        ['tab-service', 'tab-member', 'tab-partner', 'tab-contact'].forEach(id => {
          document.getElementById(id).classList.remove('active');
        });
        document.getElementById(tabId).classList.add('active');
      }

      // Step navigation with progressive saving
      document.getElementById('save-next-1').onclick = function(e) {
        e.preventDefault();
        console.log('Save & Next 1 clicked');
        
        // Validate required fields for section 1
        const profileId = document.getElementById('profile-id-input').value;
        const serviceName = document.getElementById('service-name-input').value;
        const amountPaid = document.getElementById('amount-paid-input').value;
        const startDate = document.getElementById('start-date-input').value;
        const expiryDate = document.getElementById('expiry-date-input').value;
        
        if (!profileId || !serviceName || !amountPaid || !startDate || !expiryDate) {
          alert('Please fill in all required fields (Profile ID, Service Name, Price, Start Date, Expiry Date)');
          return;
        }
        
        saveSection('service', {
          profile_id: profileId,
          service_name: serviceName,
          amount_paid: amountPaid,
          success_fee: document.getElementById('success-fee-input').value || '0',
          start_date: startDate,
          expiry_date: expiryDate,
          service_details: document.getElementById('service-details-input').value || ''
        }, function() {
          document.getElementById('step-1').style.display = 'none';
          document.getElementById('step-2').style.display = 'block';
          setActiveTab('tab-member');
        });
      };

      document.getElementById('back-2').onclick = function() {
        document.getElementById('step-2').style.display = 'none';
        document.getElementById('step-1').style.display = 'block';
        setActiveTab('tab-service');
      };

      document.getElementById('save-next-2').onclick = function(e) {
        e.preventDefault();
        console.log('Save & Next 2 clicked');
        
        saveSection('member', {
          profile_id: document.getElementById('profile-id-input').value,
          member_name: document.getElementById('member-name-input').value || '',
          member_age: document.getElementById('member-age-input').value || '',
          member_education: document.getElementById('member-education-input').value || '',
          member_occupation: document.getElementById('member-occupation-input').value || '',
          member_income: document.getElementById('member-income-input').value || '',
          member_marital_status: document.getElementById('member-marital-status-input').value || '',
          member_family_status: document.getElementById('member-family-status-input').value || '',
          member_father_details: document.getElementById('member-father-details-input').value || '',
          member_mother_details: document.getElementById('member-mother-details-input').value || '',
          member_sibling_details: document.getElementById('member-sibling-details-input').value || '',
          member_caste: document.getElementById('member-caste-input').value || '',
          member_subcaste: document.getElementById('member-subcaste-input').value || ''
        }, function() {
          document.getElementById('step-2').style.display = 'none';
          document.getElementById('step-3').style.display = 'block';
          setActiveTab('tab-partner');
        });
      };

      document.getElementById('back-3').onclick = function() {
        document.getElementById('step-3').style.display = 'none';
        document.getElementById('step-2').style.display = 'block';
        setActiveTab('tab-member');
      };

      document.getElementById('save-next-3').onclick = function(e) {
        e.preventDefault();
        console.log('Save & Next 3 clicked');
        
        saveSection('partner', {
          profile_id: document.getElementById('profile-id-input').value,
          preferred_age: document.getElementById('preferred-age-input').value || '',
          preferred_weight: document.getElementById('preferred-weight-input').value || '',
          preferred_education: document.getElementById('preferred-education-input').value || '',
          preferred_religion: document.getElementById('preferred-religion-input').value || '',
          preferred_caste: document.getElementById('preferred-caste-input').value || '',
          preferred_subcaste: document.getElementById('preferred-subcaste-input').value || '',
          preferred_marital_status: document.getElementById('preferred-marital-status-input').value || '',
          preferred_annual_income: document.getElementById('preferred-annual-income-input').value || '',
          preferred_occupation: document.getElementById('preferred-occupation-input').value || '',
          preferred_family_status: document.getElementById('preferred-family-status-input').value || '',
          preferred_eating_habits: document.getElementById('preferred-eating-habits-input').value || ''
        }, function() {
          document.getElementById('step-3').style.display = 'none';
          document.getElementById('step-4').style.display = 'block';
          setActiveTab('tab-contact');
        });
      };

      document.getElementById('back-4').onclick = function() {
        document.getElementById('step-4').style.display = 'none';
        document.getElementById('step-3').style.display = 'block';
        setActiveTab('tab-partner');
      };

      document.getElementById('save-final').onclick = function(e) {
        e.preventDefault();
        console.log('Save & Complete clicked');
        
        saveSection('contact', {
          profile_id: document.getElementById('profile-id-input').value,
          contact_customer_name: document.getElementById('contact-customer-name-input').value || '',
          contact_mobile_no: document.getElementById('contact-mobile-no-input').value || '',
          contact_whatsapp_no: document.getElementById('contact-whatsapp-no-input').value || '',
          contact_email: document.getElementById('contact-email-input').value || '',
          contact_alternate: document.getElementById('contact-alternate-input').value || '',
          contact_client: document.getElementById('contact-client-input').value || ''
        }, function() {
          // Final save - redirect to services list
          alert('Service saved successfully!');
          window.location.href = '/new-service';
        });
      };

      // Form submission
      document.getElementById('addServiceForm').onsubmit = function(e) {
        // Allow the form to submit normally to the server
        // The server will handle the redirect to new.service route
        return true;
      };
    }

    // Navigation functionality for header
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

    // Redirect to service details page with current profile data
    function redirectToServiceDetails() {
      // Get the profile ID from the form
      const profileId = document.getElementById('profile-id-input').value || 'INA001';
      const serviceName = document.querySelector('input[name="service_name"]').value || 'Service';
      
      // Redirect to the service details route
      window.location.href = `/service-details/${profileId}/${serviceName}`;
    }

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
      initializePage();
    });

    // Test AJAX function
    function testAjax() {
      const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
      const csrfToken = csrfTokenElement ? csrfTokenElement.getAttribute('content') : '';
      
      if (!csrfToken) {
        alert('CSRF token not found!');
        return;
      }
      
      console.log('Testing AJAX connection...');
      
      fetch('/save-service', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
          profile_id: 'TEST001',
          section: 'service',
          service_name: 'Test Service',
          member_name: 'Test Member',
          contact_customer_name: 'Test Customer'
        })
      })
      .then(response => {
        console.log('Response status:', response.status);
        return response.json();
      })
      .then(data => {
        console.log('Response data:', data);
        alert('AJAX Test - Success: ' + data.success + ', Message: ' + data.message);
      })
      .catch(error => {
        console.error('AJAX Test Error:', error);
        alert('AJAX Test Failed: ' + error.message);
      });
    }
  </script>