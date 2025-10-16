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
      background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
      color: #333;
      min-height: 100vh;
    }

    /* Main Dashboard Header - Same as profile page */
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

    /* Hide number input spinners/arrows */
    #amount-paid-input::-webkit-outer-spin-button,
    #amount-paid-input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    #amount-paid-input {
      -moz-appearance: textfield;
      appearance: textfield;
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
      box-shadow: 0 4px 15px rgba(23,162,184,0.4);
    }

    .btn-info:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(23,162,184,0.6);
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
      background: #fdf2f8;
      color: #ac0742;
    }

    .status-badge.edit-mode {
      background: #fff3e0;
      color: #f57c00;
    }

    .status-badge.new-mode {
      background: #e3f2fd;
      color: #1976d2;
    }

    .status-badge.active-mode {
      background: #e8f5e8;
      color: #4CAF50;
    }

    .status-badge.completed-mode {
      background: #f3e5f5;
      color: #9c27b0;
    }

    .status-badge.cancelled-mode {
      background: #ffebee;
      color: #f44336;
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
        <li><a href="{{ route('dashboard') }}">Home</a></li>
        <li><a href="{{ route('profile.hellow') }}">Profiles</a></li>
        <li><a href="#" data-page="sales">Sales <span class="dropdown-arrow">▼</span></a></li>
        <li><a href="#" data-page="helpline">HelpLine</a></li>
        <li><a href="{{ route('fresh.data') }}">Fresh Data <span class="dropdown-arrow">▼</span></a></li>
        <li><a href="#" data-page="abc">abc</a></li>
        <li><a href="{{ route('services.page') }}" class="active">Services <span class="dropdown-arrow">▼</span></a></li>
      </ul>
    </nav>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
      @csrf
    </form>
    <button class="logout-btn" onclick="confirmLogout()">Logout</button>
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
              <button type="button" class="btn btn-info" id="edit-service-btn" style="margin-right: 10px;" onclick="enableEditMode()">Edit Service</button>
              <button type="button" class="btn btn-success" id="save-service-btn" style="margin-right: 10px; display: none;" onclick="saveAllChanges()">Save Changes</button>
            @else
              <button type="button" class="btn btn-info" id="test-edit-btn" style="margin-right: 10px;" onclick="enableEditMode()">Test Edit</button>
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
          @if(isset($service))
            @method('PUT')
          @endif
          <div id="step-1" class="wizard-step">
            <h3>Service Details</h3>
            <div class="profile-id-section">
              <h4>Profile ID 
                @if(isset($service) && $service->status)
                  <span class="status-badge {{ $service->status }}-mode">
                    {{ ucfirst($service->status) }}
                  </span>
                @endif
              </h4>
              <div class="form-row">
                <div class="form-group">
        <input type="text" name="profile_id" id="profile-id-input" placeholder="Profile ID" 
          value="{{ isset($service) ? $service->profile_id : 'INA001' }}" 
          readonly required>
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Service Name</label>
                <select name="service_name" id="service-name-input" {{ isset($service) ? 'disabled' : 'required' }}>
                  <option value="">Select service name</option>
                  <option value="Silver" {{ (isset($service) && $service->service_name == 'Silver') ? 'selected' : '' }}>Silver</option>
                  <option value="Gold" {{ (isset($service) && $service->service_name == 'Gold') ? 'selected' : '' }}>Gold</option>
                  <option value="Platinum" {{ (isset($service) && $service->service_name == 'Platinum') ? 'selected' : '' }}>Platinum</option>
                  <option value="Diamond" {{ (isset($service) && $service->service_name == 'Diamond') ? 'selected' : '' }}>Diamond</option>
                  <option value="Diamond Plus" {{ (isset($service) && $service->service_name == 'Diamond Plus') ? 'selected' : '' }}>Diamond Plus</option>
                  <option value="Royal" {{ (isset($service) && $service->service_name == 'Royal') ? 'selected' : '' }}>Royal</option>
                  <option value="Assisted" {{ (isset($service) && $service->service_name == 'Assisted') ? 'selected' : '' }}>Assisted</option>
                  <option value="Elite" {{ (isset($service) && $service->service_name == 'Elite') ? 'selected' : '' }}>Elite</option>
                </select>
              </div>
              <div class="form-group">
                <label>Service Price</label>
                <input type="number" name="service_price" id="service-price-input" placeholder="18000.00" step="0.01" 
                       value="{{ isset($service) ? $service->service_price : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Paid Amount</label>
                <input type="number" name="amount_paid" id="amount-paid-input" placeholder="0.00" step="0.01" 
                       value="{{ isset($service) ? $service->amount_paid : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
              <div class="form-group">
                <label>Success Fee</label>
                <input type="number" name="success_fee" id="success-fee-input" placeholder="0" step="0.01" 
                       value="{{ isset($service) ? $service->success_fee : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} readonly required>
              </div>
              <div class="form-group">
                <label>Start Date</label>
                <input type="date" name="start_date" id="start-date-input" 
                       value="{{ isset($service) ? $service->start_date : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
              <div class="form-group">
                <label>Expiry Date</label>
                <input type="date" name="expiry-date-input" 
                       value="{{ isset($service) ? $service->expiry_date : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group" style="width:100%">
                <label>Description</label>
                <textarea name="service_details" id="service-details-input" placeholder="Enter detailed service information" 
                          {{ isset($service) ? 'readonly' : '' }} required>{{ isset($service) ? $service->service_details : '' }}</textarea>
              </div>
            </div>
            <div class="form-actions" style="{{ isset($service) ? 'display: none;' : '' }}">
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
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
              <div class="form-group">
                <label>Age (Auto-calculated)</label>
                <input type="number" name="member_age" id="member-age-input" placeholder="Age" 
                       value="{{ isset($service) ? $service->member_age : '' }}" 
                       maxlength="2" max="99" min="18"
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Education</label>
                <input type="text" name="member_education" id="member-education-input" placeholder="Education" 
                       value="{{ isset($service) ? $service->member_education : '' }}" 
                       readonly required>
              </div>
              <div class="form-group">
                <label>Occupation</label>
                <input type="text" name="member_occupation" id="member-occupation-input" placeholder="Occupation" 
                       value="{{ isset($service) ? $service->member_occupation : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Annual Income</label>
                <input type="text" name="member_income" id="member-income-input" placeholder="Annual Income" 
                       value="{{ isset($service) ? $service->member_income : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
              <div class="form-group">
                <label>Marital Status</label>
                <input type="text" name="member_marital_status" id="member-marital-status-input" placeholder="Marital Status" 
                       value="{{ isset($service) ? $service->member_marital_status : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Family Status</label>
                <input type="text" name="member_family_status" id="member-family-status-input" placeholder="Family Status" 
                       value="{{ isset($service) ? $service->member_family_status : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
              <div class="form-group">
                <label>Father Details</label>
                <input type="text" name="member_father_details" id="member-father-details-input" placeholder="Father Details" 
                       value="{{ isset($service) ? $service->member_father_details : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Mother Details</label>
                <input type="text" name="member_mother_details" id="member-mother-details-input" placeholder="Mother Details" 
                       value="{{ isset($service) ? $service->member_mother_details : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
              <div class="form-group">
                <label>Sibling Details</label>
                <input type="text" name="member_sibling_details" id="member-sibling-details-input" placeholder="Sibling Details" 
                       value="{{ isset($service) ? $service->member_sibling_details : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Caste</label>
                <input type="text" name="member_caste" id="member-caste-input" placeholder="Caste" 
                       value="{{ isset($service) ? $service->member_caste : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
              <div class="form-group">
                <label>Subcaste</label>
                <input type="text" name="member_subcaste" id="member-subcaste-input" placeholder="Subcaste" 
                       value="{{ isset($service) ? $service->member_subcaste : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
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
                <input type="number" name="preferred_age" id="preferred-age-input" placeholder="Preferred Age" 
                       value="{{ isset($service) ? $service->preferred_age : '' }}" 
                       maxlength="2" max="99" min="18"
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
              <div class="form-group">
                <label>Preferred Weight</label>
                <input type="text" name="preferred_weight" id="preferred-weight-input" placeholder="Preferred Weight" 
                       value="{{ isset($service) ? $service->preferred_weight : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Preferred Education</label>
                <input type="text" name="preferred_education" id="preferred-education-input" placeholder="Preferred Education" 
                       value="{{ isset($service) ? $service->preferred_education : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
              <div class="form-group">
                <label>Preferred Religion</label>
                <input type="text" name="preferred_religion" id="preferred-religion-input" placeholder="Preferred Religion" 
                       value="{{ isset($service) ? $service->preferred_religion : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Preferred Caste</label>
                <input type="text" name="preferred_caste" id="preferred-caste-input" placeholder="Preferred Caste" 
                       value="{{ isset($service) ? $service->preferred_caste : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
              <div class="form-group">
                <label>Preferred Subcaste</label>
                <input type="text" name="preferred_subcaste" id="preferred-subcaste-input" placeholder="Preferred Subcaste" 
                       value="{{ isset($service) ? $service->preferred_subcaste : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Preferred Marital Status</label>
                <input type="text" name="preferred_marital_status" id="preferred-marital-status-input" placeholder="Preferred Marital Status" 
                       value="{{ isset($service) ? $service->preferred_marital_status : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
              <div class="form-group">
                <label>Preferred Annual Income</label>
                <input type="text" name="preferred_annual_income" id="preferred-annual-income-input" placeholder="Preferred Annual Income" 
                       value="{{ isset($service) ? $service->preferred_annual_income : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Preferred Occupation</label>
                <input type="text" name="preferred_occupation" id="preferred-occupation-input" placeholder="Preferred Occupation" 
                       value="{{ isset($service) ? $service->preferred_occupation : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
              <div class="form-group">
                <label>Preferred Family Status</label>
                <input type="text" name="preferred_family_status" id="preferred-family-status-input" placeholder="Preferred Family Status" 
                       value="{{ isset($service) ? $service->preferred_family_status : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Preferred Eating Habits</label>
                <input type="text" name="preferred_eating_habits" id="preferred-eating-habits-input" placeholder="Preferred Eating Habits" 
                       value="{{ isset($service) ? $service->preferred_eating_habits : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
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
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
              <div class="form-group">
                <label>Mobile No</label>
                <input type="text" name="contact_mobile_no" id="contact-mobile-no-input" placeholder="Mobile No" 
                       value="{{ isset($service) ? $service->contact_mobile_no : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>WhatsApp No</label>
                <input type="text" name="contact_whatsapp_no" id="contact-whatsapp-no-input" placeholder="WhatsApp No" 
                       value="{{ isset($service) ? $service->contact_whatsapp_no : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
              <div class="form-group">
                <label>Email</label>
                <input type="email" name="contact_email" id="contact-email-input" placeholder="Email" 
                       value="{{ isset($service) ? $service->contact_email : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label>Alternate Contact</label>
                <input type="text" name="contact_alternate" id="contact-alternate-input" placeholder="Alternate Contact" 
                       value="{{ isset($service) ? $service->contact_alternate : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
              </div>
              <div class="form-group">
                <label>Client</label>
                <input type="text" name="contact_client" id="contact-client-input" placeholder="Client" 
                       value="{{ isset($service) ? $service->contact_client : '' }}" 
                       {{ isset($service) ? 'readonly' : '' }} required>
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

// Function to refresh CSRF token
function refreshCSRFToken() {
  return fetch('/refresh-csrf', {
    method: 'GET',
    credentials: 'same-origin'
  })
  .then(response => response.json())
  .then(data => {
    if (data.token) {
      const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
      if (csrfTokenElement) {
        csrfTokenElement.setAttribute('content', data.token);
      }
      console.log('CSRF token refreshed');
      return data.token;
    }
    throw new Error('Failed to refresh CSRF token');
  });
}

// Set up form navigation for add/edit mode
function setupFormNavigation() {
  const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
  const csrfToken = csrfTokenElement ? csrfTokenElement.getAttribute('content') : '';
  
  if (!csrfToken) {
    console.error('CSRF token not found. Please refresh the page.');
    alert('Security token missing. Please refresh the page and try again.');
    return;
  }

  // Progressive save function
  function saveSection(section, data, callback, retryCount = 0) {
    const saveButtons = document.querySelectorAll('.btn-primary');
    let saveBtn = null;
    
    for (let btn of saveButtons) {
      if (btn.offsetParent !== null) {
        saveBtn = btn;
        break;
      }
    }
    
    const originalText = saveBtn ? saveBtn.textContent : '';
    if (saveBtn) {
      saveBtn.textContent = 'Saving...';
      saveBtn.disabled = true;
    }

    const currentCsrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    console.log('Saving section:', section);

    fetch('/save-service', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': currentCsrfToken,
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
          console.log('CSRF token expired, refreshing and retrying...');
          return refreshCSRFToken().then(() => {
            return saveSection(section, data, callback, retryCount + 1);
          });
        }
        throw new Error('Network response was not ok: ' + response.status);
      }
      return response.json();
    })
    .then(data => {
      console.log('Server response:', data);
      if (data && data.success) {
        console.log('Section saved successfully');
        callback();
      } else {
        alert('Error saving data: ' + (data?.message || 'Unknown error'));
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Error occurred while saving: ' + error.message);
    })
    .finally(() => {
      if (saveBtn) {
        saveBtn.textContent = originalText;
        saveBtn.disabled = false;
      }
    });
  }

  // Wizard navigation with tab highlight
  function setActiveTab(tabId) {
    ['tab-service', 'tab-member', 'tab-partner', 'tab-contact'].forEach(id => {
      const tab = document.getElementById(id);
      if (tab) tab.classList.remove('active');
    });
    const activeTab = document.getElementById(tabId);
    if (activeTab) activeTab.classList.add('active');
  }

  // Step navigation buttons
  const saveNext1 = document.getElementById('save-next-1');
  if (saveNext1) {
    saveNext1.onclick = function(e) {
      e.preventDefault();
      console.log('Save & Next 1 clicked');
      
      if (!validateStepFields(1)) return;
      
      saveSection('service', {
        profile_id: document.getElementById('profile-id-input').value,
        service_name: document.getElementById('service-name-input').value,
        service_price: document.getElementById('service-price-input').value,
        amount_paid: document.getElementById('amount-paid-input').value,
        success_fee: document.getElementById('success-fee-input').value,
        start_date: document.getElementById('start-date-input').value,
        expiry_date: document.querySelector('input[name="expiry-date-input"]').value,
        service_details: document.getElementById('service-details-input').value
      }, function() {
        document.getElementById('step-1').style.display = 'none';
        document.getElementById('step-2').style.display = 'block';
        setActiveTab('tab-member');
      });
    };
  }

  const back2 = document.getElementById('back-2');
  if (back2) {
    back2.onclick = function(e) {
      e.preventDefault();
      document.getElementById('step-2').style.display = 'none';
      document.getElementById('step-1').style.display = 'block';
      setActiveTab('tab-service');
    };
  }

  const saveNext2 = document.getElementById('save-next-2');
  if (saveNext2) {
    saveNext2.onclick = function(e) {
      e.preventDefault();
      console.log('Save & Next 2 clicked');
      
      if (!validateStepFields(2)) return;
      
      saveSection('member', {
        profile_id: document.getElementById('profile-id-input').value,
        member_name: document.getElementById('member-name-input')?.value || '',
        member_age: document.getElementById('member-age-input')?.value || '',
        member_education: document.getElementById('member-education-input')?.value || '',
        member_occupation: document.getElementById('member-occupation-input')?.value || '',
        member_income: document.getElementById('member-income-input')?.value || '',
        member_marital_status: document.getElementById('member-marital-status-input')?.value || '',
        member_family_status: document.getElementById('member-family-status-input')?.value || '',
        member_father_details: document.getElementById('member-father-details-input')?.value || '',
        member_mother_details: document.getElementById('member-mother-details-input')?.value || '',
        member_sibling_details: document.getElementById('member-sibling-details-input')?.value || '',
        member_caste: document.getElementById('member-caste-input')?.value || '',
        member_subcaste: document.getElementById('member-subcaste-input')?.value || ''
      }, function() {
        document.getElementById('step-2').style.display = 'none';
        document.getElementById('step-3').style.display = 'block';
        setActiveTab('tab-partner');
      });
    };
  }

  const back3 = document.getElementById('back-3');
  if (back3) {
    back3.onclick = function(e) {
      e.preventDefault();
      document.getElementById('step-3').style.display = 'none';
      document.getElementById('step-2').style.display = 'block';
      setActiveTab('tab-member');
    };
  }

  const saveNext3 = document.getElementById('save-next-3');
  if (saveNext3) {
    saveNext3.onclick = function(e) {
      e.preventDefault();
      console.log('Save & Next 3 clicked');
      
      if (!validateStepFields(3)) return;
      
      saveSection('partner', {
        profile_id: document.getElementById('profile-id-input').value,
        preferred_age: document.getElementById('preferred-age-input')?.value || '',
        preferred_weight: document.getElementById('preferred-weight-input')?.value || '',
        preferred_education: document.getElementById('preferred-education-input')?.value || '',
        preferred_religion: document.getElementById('preferred-religion-input')?.value || '',
        preferred_caste: document.getElementById('preferred-caste-input')?.value || '',
        preferred_subcaste: document.getElementById('preferred-subcaste-input')?.value || '',
        preferred_marital_status: document.getElementById('preferred-marital-status-input')?.value || '',
        preferred_annual_income: document.getElementById('preferred-annual-income-input')?.value || '',
        preferred_occupation: document.getElementById('preferred-occupation-input')?.value || '',
        preferred_family_status: document.getElementById('preferred-family-status-input')?.value || '',
        preferred_eating_habits: document.getElementById('preferred-eating-habits-input')?.value || ''
      }, function() {
        document.getElementById('step-3').style.display = 'none';
        document.getElementById('step-4').style.display = 'block';
        setActiveTab('tab-contact');
      });
    };
  }

  const back4 = document.getElementById('back-4');
  if (back4) {
    back4.onclick = function(e) {
      e.preventDefault();
      document.getElementById('step-4').style.display = 'none';
      document.getElementById('step-3').style.display = 'block';
      setActiveTab('tab-partner');
    };
  }

  const saveFinal = document.getElementById('save-final');
  if (saveFinal) {
    saveFinal.onclick = function(e) {
      e.preventDefault();
      console.log('Save & Complete clicked');
      
      if (!validateStepFields(4)) return;
      
      saveSection('contact', {
        profile_id: document.getElementById('profile-id-input').value,
        contact_customer_name: document.getElementById('contact-customer-name-input')?.value || '',
        contact_mobile_no: document.getElementById('contact-mobile-no-input')?.value || '',
        contact_whatsapp_no: document.getElementById('contact-whatsapp-no-input')?.value || '',
        contact_email: document.getElementById('contact-email-input')?.value || '',
        contact_alternate: document.getElementById('contact-alternate-input')?.value || '',
        contact_client: document.getElementById('contact-client-input')?.value || ''
      }, function() {
        alert('Service saved successfully!');
        window.location.href = '/new-service';
      });
    };
  }
}

// Show specific step
function showStep(step) {
  for (let i = 1; i <= 4; i++) {
    const stepEl = document.getElementById('step-' + i);
    if (stepEl) stepEl.style.display = 'none';
  }
  
  const targetStep = document.getElementById('step-' + step);
  if (targetStep) targetStep.style.display = 'block';
  
  const tabs = ['tab-service', 'tab-member', 'tab-partner', 'tab-contact'];
  tabs.forEach((tabId, index) => {
    const tab = document.getElementById(tabId);
    if (tab) {
      if (index === step - 1) {
        tab.classList.add('active');
      } else {
        tab.classList.remove('active');
      }
    }
  });
}

// Function to setup automatic service pricing
function setupServicePricing() {
  const serviceNameSelect = document.getElementById('service-name-input');
  const servicePriceInput = document.getElementById('service-price-input');
  const paidAmountInput = document.getElementById('amount-paid-input');
  const successFeeInput = document.getElementById('success-fee-input');
  
  if (serviceNameSelect && servicePriceInput) {
    serviceNameSelect.addEventListener('change', function() {
      const servicePrices = {
        'Silver': 3300,
        'Gold': 4400,
        'Platinum': 5600,
        'Diamond': 18000,
        'Diamond Plus': 25000,
        'Royal': 50000,
        'Assisted': 18000,
        'Elite': 100000
      };

      if (servicePrices[this.value]) {
        servicePriceInput.value = servicePrices[this.value];
        calculateSuccessFee();
      }

      // Auto-set expiry date based on service type
      const startDateInput = document.getElementById('start-date-input');
      const expiryDateInput = document.querySelector('input[name="expiry-date-input"]');
      if (startDateInput && expiryDateInput && startDateInput.value) {
  let monthsToAdd = 0;
  let yearsToAdd = 0;
  if (this.value === 'Diamond' || this.value === 'Assisted') monthsToAdd = 3;
  if (this.value === 'Diamond Plus' || this.value === 'Royal') monthsToAdd = 6;
  if (this.value === 'Elite') yearsToAdd = 1;

        if (monthsToAdd > 0 || yearsToAdd > 0) {
          const start = new Date(startDateInput.value);
          let expiry = new Date(start);
          if (monthsToAdd > 0) {
            expiry.setMonth(expiry.getMonth() + monthsToAdd);
          }
          if (yearsToAdd > 0) {
            expiry.setFullYear(expiry.getFullYear() + yearsToAdd);
          }
          // Format as yyyy-mm-dd
          const yyyy = expiry.getFullYear();
          const mm = String(expiry.getMonth() + 1).padStart(2, '0');
          const dd = String(expiry.getDate()).padStart(2, '0');
          expiryDateInput.value = `${yyyy}-${mm}-${dd}`;
        } else {
          expiryDateInput.value = '';
        }
      }
    });
    // Also update expiry date if start date changes and service is selected
    const startDateInput = document.getElementById('start-date-input');
    if (startDateInput) {
      startDateInput.addEventListener('change', function() {
        const service = serviceNameSelect.value;
  let monthsToAdd = 0;
  let yearsToAdd = 0;
  if (service === 'Diamond' || service === 'Assisted') monthsToAdd = 3;
  if (service === 'Diamond Plus' || service === 'Royal') monthsToAdd = 6;
  if (service === 'Elite') yearsToAdd = 1;
        if (monthsToAdd > 0 || yearsToAdd > 0) {
          const start = new Date(this.value);
          let expiry = new Date(start);
          if (monthsToAdd > 0) {
            expiry.setMonth(expiry.getMonth() + monthsToAdd);
          }
          if (yearsToAdd > 0) {
            expiry.setFullYear(expiry.getFullYear() + yearsToAdd);
          }
          const yyyy = expiry.getFullYear();
          const mm = String(expiry.getMonth() + 1).padStart(2, '0');
          const dd = String(expiry.getDate()).padStart(2, '0');
          const expiryDateInput = document.querySelector('input[name="expiry-date-input"]');
          if (expiryDateInput) expiryDateInput.value = `${yyyy}-${mm}-${dd}`;
        }
      });
    }
    if (serviceNameSelect.value && !servicePriceInput.value) {
      serviceNameSelect.dispatchEvent(new Event('change'));
    }
  }
  
  if (paidAmountInput) {
    paidAmountInput.addEventListener('input', calculateSuccessFee);
  }
  
  if (servicePriceInput) {
    servicePriceInput.addEventListener('input', calculateSuccessFee);
  }
  
  function calculateSuccessFee() {
    if (servicePriceInput && paidAmountInput && successFeeInput) {
      const servicePrice = parseFloat(servicePriceInput.value) || 0;
      const paidAmount = parseFloat(paidAmountInput.value) || 0;
      const successFee = servicePrice - paidAmount;
      successFeeInput.value = Math.max(0, successFee).toFixed(2);
    }
  }
  
  calculateSuccessFee();
}

// Navigation functionality for header
document.querySelectorAll('.header-nav a').forEach(link => {
  link.addEventListener('click', function(e) {
    if (!this.getAttribute('href') || this.getAttribute('href') === '#') {
      e.preventDefault();
      document.querySelectorAll('.header-nav a').forEach(l => l.classList.remove('active'));
      this.classList.add('active');
    }
  });
});

// Logout functionality
window.confirmLogout = function() {
  if (confirm('Are you sure you want to logout?')) {
    document.getElementById('logout-form').submit();
  }
};



const isViewMode = {{ isset($service) ? 'true' : 'false' }};

// Initialize the page
function initializePage() {
  setupFormValidations();
  const isAdmin = {{ Auth::check() && Auth::user()->is_admin ? 'true' : 'false' }};
  if (!isViewMode) {
    if (document.getElementById('profile-id-input')) {
      document.getElementById('profile-id-input').value = 'INA001';
    }
    setupFormNavigation();
    showStep(1);
    setActiveTab('tab-service');
  } else {
    setupTabNavigation();
    showStep(1);
    setActiveTab('tab-service');
  }
  const editBtn = document.getElementById('edit-service-btn');
  if (editBtn) {
    editBtn.onclick = function() {
      enableEditMode();
    };
  }
}

// Setup form validations
function setupFormValidations() {
  const startDateInput = document.getElementById('start-date-input');
  const expiryDateInput = document.querySelector('input[name="expiry-date-input"]');
  if (startDateInput && expiryDateInput) {
    startDateInput.addEventListener('change', function() {
      const startDate = this.value;
      if (startDate) {
        expiryDateInput.setAttribute('min', startDate);
        if (expiryDateInput.value && expiryDateInput.value < startDate) {
          expiryDateInput.value = '';
          alert('Expiry date cannot be before the start date. Please select a valid expiry date.');
        }
      }
    });
    expiryDateInput.addEventListener('change', function() {
      const startDate = startDateInput.value;
      const expiryDate = this.value;
      if (startDate && expiryDate && expiryDate < startDate) {
        this.value = '';
        alert('Expiry date cannot be before the start date. Please select a valid expiry date.');
      }
    });
  }
  const memberAgeInput = document.getElementById('member-age-input');
  if (memberAgeInput) {
    memberAgeInput.addEventListener('input', function() {
      this.value = this.value.replace(/[^0-9]/g, '');
      if (this.value.length > 2) {
        this.value = this.value.slice(0, 2);
      }
    });
  }
  const preferredAgeInput = document.getElementById('preferred-age-input');
  if (preferredAgeInput) {
    preferredAgeInput.addEventListener('input', function() {
      this.value = this.value.replace(/[^0-9]/g, '');
      if (this.value.length > 2) {
        this.value = this.value.slice(0, 2);
      }
    });
  }
}

// Validate all required fields in a step
function validateStepFields(stepNum) {
  const step = document.getElementById('step-' + stepNum);
  if (!step) return false;
  const requiredFields = step.querySelectorAll('input[required], select[required], textarea[required]');
  for (let field of requiredFields) {
    if (field.offsetParent !== null && !field.value.trim()) {
      field.focus();
      alert('Please fill all fields before continuing.');
      return false;
    }
  }
  return true;
}

// Enable edit mode
function enableEditMode() {
  const inputs = document.querySelectorAll('#addServiceForm input, #addServiceForm textarea');
  inputs.forEach(input => {
    if (input.id !== 'profile-id-input') {
      input.removeAttribute('readonly');
    }
  });
  const selects = document.querySelectorAll('#addServiceForm select');
  selects.forEach(select => {
    select.removeAttribute('disabled');
  });
  const formActions = document.querySelectorAll('.form-actions');
  formActions.forEach(action => {
    action.style.display = 'flex';
  });
  const badge = document.querySelector('.status-badge');
  if (badge) {
    badge.textContent = 'Editing Service';
    badge.className = 'status-badge edit-mode';
  }
  const editBtn = document.getElementById('edit-service-btn');
  const saveBtn = document.getElementById('save-service-btn');
  if (editBtn) editBtn.style.display = 'none';
  if (saveBtn) {
    saveBtn.style.display = 'inline-block';
    saveBtn.onclick = function() {
      saveAllChanges();
    };
  }
  setupFormValidations();
  setupServicePricing();
  setupFormNavigation();
}

// Save all changes function
function saveAllChanges() {
  const form = document.getElementById('addServiceForm');
  if (!form) {
    alert('Form not found');
    return;
  }
  for (let i = 1; i <= 4; i++) {
    if (!validateStepFields(i)) {
      return;
    }
  }
  const formData = new FormData(form);
  const csrfToken = document.querySelector('input[name="_token"]').value;
  formData.append('_token', csrfToken);
  formData.append('_method', 'PUT');
  const saveBtn = document.getElementById('save-service-btn');
  if (saveBtn) {
    saveBtn.textContent = 'Saving...';
    saveBtn.disabled = true;
  }
  fetch(form.action, {
    method: 'POST',
    body: formData,
    headers: {
      'X-CSRF-TOKEN': csrfToken
    }
  })
  .then(response => {
    if (response.ok) {
      alert('Changes saved successfully!');
      location.reload();
    } else {
      throw new Error('Failed to save changes');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('Error saving changes: ' + error.message);
  })
  .finally(() => {
    if (saveBtn) {
      saveBtn.textContent = 'Save Changes';
      saveBtn.disabled = false;
    }
  });
}

// Set up tab navigation (for viewing mode)
// Set up tab navigation (for viewing mode)
function setupTabNavigation() {
  const tabs = ['tab-service', 'tab-member', 'tab-partner', 'tab-contact'];
  const steps = ['step-1', 'step-2', 'step-3', 'step-4'];
  
  tabs.forEach((tabId, index) => {
    const tabElement = document.getElementById(tabId);
    if (tabElement) {
      tabElement.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Remove active from all tabs
        tabs.forEach(id => {
          const tab = document.getElementById(id);
          if (tab) tab.classList.remove('active');
        });
        
        // Hide all steps
        steps.forEach(id => {
          const step = document.getElementById(id);
          if (step) step.style.display = 'none';
        });
        
        // Activate current tab and show current step
        this.classList.add('active');
        const currentStep = document.getElementById(steps[index]);
        if (currentStep) currentStep.style.display = 'block';
      });
    }
  });
}

// Set active tab
// Set active tab
function setActiveTab(tabId) {
  const tabs = document.querySelectorAll('.tab-nav button');
  tabs.forEach(tab => {
    tab.classList.remove('active');
  });
  const activeTab = document.getElementById(tabId);
  if (activeTab) {
    activeTab.classList.add('active');
  }
}

// Show step
function showStep(stepNum) {
  for (let i = 1; i <= 4; i++) {
    const step = document.getElementById('step-' + i);
    if (step) {
      step.style.display = (i === stepNum) ? 'block' : 'none';
    }
  }
}

// Auto-fetch member data function
function fetchMemberDataFromDB(userId) {
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  fetch('/get-member-data', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrfToken,
      'Accept': 'application/json'
    },
    body: JSON.stringify({ user_id: userId })
  })
  .then(response => response.json())
  .then(data => {
    console.log('Fetched data:', data);
    if (data.success) {
      document.getElementById('member-name-input').value = data.member_name || '';
      document.getElementById('member-birthday-input').value = data.birthday || '';
      document.getElementById('member-age-input').value = data.age || '';
      document.getElementById('member-education-input').value = data.education || '';
      document.getElementById('member-marital-status-input').value = data.marital_status || '';
      document.getElementById('member-occupation-input').value = data.occupation || '';
      document.getElementById('member-income-input').value = data.income || '';
      document.getElementById('member-family-status-input').value = data.family_status || '';
      document.getElementById('member-father-details-input').value = data.father_details || '';
      document.getElementById('member-mother-details-input').value = data.mother_details || '';
      document.getElementById('member-sibling-details-input').value = data.sibling_details || '';
      document.getElementById('member-caste-input').value = data.caste || '';
      document.getElementById('member-subcaste-input').value = data.subcaste || '';
    } else {
      console.warn('Error:', data.message);
    }
  })
  .catch(error => {
    console.error('Fetch error:', error);
  });
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
  initializePage();
  setTimeout(function() {
    setupServicePricing();
  }, 100);
  // Auto-fetch member data if we have a service (viewing mode)
  const profileIdInput = document.getElementById('profile-id-input');
  if (profileIdInput && profileIdInput.value && profileIdInput.value !== 'INA001') {
    console.log('Fetching data for profile ID:', profileIdInput.value);
    fetchMemberDataFromDB(profileIdInput.value);
  }

  // Age is fetched from the database and set via fetchMemberDataFromDB; no auto-calculation from birthday.
});
</script>