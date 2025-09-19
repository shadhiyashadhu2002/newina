<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>INA Dashboard - Client Details</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: #333;
      min-height: 100vh;
      padding: 0;
      margin: 0;
    }

    /* Main Dashboard Header */
    .main-header {
      background: linear-gradient(135deg, #4a69bd, #5a4fcf);
      padding: 12px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .header-brand {
      color: white;
      font-size: 22px;
      font-weight: 700;
      text-decoration: none;
    }

    .header-nav {
      display: flex;
      list-style: none;
      gap: 15px;
      align-items: center;
    }

    .header-nav li {
      position: relative;
    }

    .header-nav a {
      color: rgba(255, 255, 255, 0.9);
      text-decoration: none;
      font-weight: 500;
      font-size: 14px;
      padding: 8px 12px;
      border-radius: 6px;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .header-nav a:hover {
      color: white;
      background: rgba(255, 255, 255, 0.1);
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
      padding: 6px 12px;
      border-radius: 15px;
      cursor: pointer;
      transition: all 0.3s ease;
      font-weight: 500;
      font-size: 14px;
    }

    .logout-btn:hover {
      background: rgba(255, 255, 255, 0.2);
    }

    /* Main Content Area */
    .main-content {
  padding: 40px 30px;
  max-width: 1400px;
  margin: 0 auto;
    }

    .page-title {
      color: #fff;
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 20px;
      text-shadow: 0 2px 4px rgba(0,0,0,0.3);
      text-align: center;
    }

    /* Box Container */
    .boxes-container {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 40px;
  margin-bottom: 40px;
  align-items: stretch;
    }

    .box {
  background: rgba(255, 255, 255, 0.95);
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.2);
  border: 1px solid rgba(255, 255, 255, 0.3);
  min-height: 600px;
  height: auto;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
    }

    .box-header {
  display: flex;
  align-items: center;
  margin-bottom: 15px;
  padding-bottom: 10px;
  border-bottom: 2px solid #e0e0e0;
  background: #ffe066;
  border-radius: 8px 8px 0 0;
  padding-top: 10px;
  padding-left: 10px;
  padding-right: 10px;
    }

    .box-icon {
      font-size: 20px;
      margin-right: 10px;
    }

    .box-title {
      font-size: 18px;
      font-weight: bold;
      color: #2c3e50;
    }

    /* Info Items */
    .info-item {
      display: flex;
      margin-bottom: 12px;
    }

    .info-label {
      font-weight: 600;
      color: #555;
      min-width: 140px;
      padding-right: 10px;
    }

    .info-value {
      color: #333;
      flex: 1;
    }

    /* Contact Items */
    .contact-item {
      display: flex;
      margin-bottom: 12px;
      align-items: flex-start;
    }

    .contact-label {
      font-weight: 600;
      color: #555;
      min-width: 140px;
      padding-right: 10px;
    }

    .contact-value {
      color: #333;
      flex: 1;
    }

    /* Responsive design */
    @media (max-width: 900px) {
      .boxes-container {
        grid-template-columns: 1fr 1fr;
      }
    }

    @media (max-width: 600px) {
      .main-header {
        flex-direction: column;
        gap: 10px;
        padding: 10px;
      }
      .header-nav {
        flex-wrap: wrap;
        justify-content: center;
        gap: 8px;
      }
      .header-nav a {
        font-size: 13px;
        padding: 6px 10px;
      }
      .boxes-container {
        grid-template-columns: 1fr;
      }
      .info-item,
      .contact-item {
        flex-direction: column;
        margin-bottom: 15px;
      }
      .info-label,
      .contact-label {
        min-width: auto;
        margin-bottom: 5px;
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
    <h1 class="page-title">Client Details - 38584</h1>

    <div class="boxes-container">
  <!-- Service Details Box -->
  <div class="box service-details-box">

        <div class="box-header">
          <span class="box-icon">⚙️</span>
          <h2 class="box-title">Service Details</h2>
        </div>
        <div class="info-item">
          <span class="info-label">Profile ID:</span>
          <span class="info-value">38584</span>
        </div>
        <div class="info-item">
          <span class="info-label">Service Name:</span>
          <span class="info-value">Premium</span>
        </div>
        <div class="info-item">
          <span class="info-label">Price:</span>
          <span class="info-value">01-Jan-2025</span>
        </div>
        <div class="info-item">
          <span class="info-label">Success Fee:</span>
          <span class="info-value">31-Dec-2025</span>
        </div>
        <div class="info-item">
          <span class="info-label">Start Date:</span>
          <span class="info-value">31-Dec-2025</span>
        </div>
        <div class="info-item">
          <span class="info-label">Expiry Date:</span>
          <span class="info-value">31-Dec-2025</span>
        </div>
         <div class="info-item">
          <span class="info-label">Discription:</span>
          <span class="info-value">Royal service 7 months</span>
        </div>
        <div class="info-item">
        </div>
      </div>

  <!-- Member Info Box -->
  <div class="box service-details-box">
    
        <div class="box-header">
          <span class="box-icon">👤</span>
          <h2 class="box-title">Member Info</h2>
        </div>
        <div class="info-item">
          <span class="info-label">Name:</span>
          <span class="info-value">Musthafia</span>
        </div>
        <div class="info-item">
          <span class="info-label">Age:</span>
          <span class="info-value">30</span>
        </div>
        <div class="info-item">
          <span class="info-label">Education:</span>
          <span class="info-value">B.TECH</span>
        </div>
        <div class="info-item">
          <span class="info-label">Occupation:</span>
          <span class="info-value">BUSINESS</span>
        </div>
        <div class="info-item">
          <span class="info-label">Annual Income:</span>
          <span class="info-value">200/2020</span>
        </div>
        <div class="info-item">
          <span class="info-label">Marital Status:</span>
          <span class="info-value">News Married</span>
        </div>
        <div class="info-item">
          <span class="info-label">Family Status:</span>
          <span class="info-value">Weekly</span>
        </div>
        <div class="info-item">
          <span class="info-label">Father Details:</span>
          <span class="info-value">Change activity</span>
        </div>
        <div class="info-item">
          <span class="info-label">Mother Details:</span>
          <span class="info-value">sin</span>
        </div>
        <div class="info-item">
          <span class="info-label">Sibling Details:</span>
          <span class="info-value">Dr. notch, phara</span>
        </div>
        <div class="info-item">
          <span class="info-label">Caste:</span>
          <span class="info-value"></span>
        </div>
        <div class="info-item">
          <span class="info-label">SubCaste:</span>
          <span class="info-value">min</span>
        </div>
         <div class="info-item">
          <span class="info-label">Eating Habits:</span>
          <span class="info-value">All</span>
        </div>
         <div class="info-item">
          <span class="info-label">country Living In:</span>
          <span class="info-value">India</span>
        </div>
         <div class="info-item">
          <span class="info-label">Residing State:</span>
          <span class="info-value">Kerala</span>
        </div>
      </div>

  <!-- Partner Preference Box -->
  <div class="box">

        <div class="box-header">
          <span class="box-icon">💞</span>
          <h2 class="box-title">Partner Preference</h2>
        </div>
        <div class="info-item">
          <span class="info-label">Preferred Age:</span>
          <span class="info-value">22 - 27</span>
        </div>
        <div class="info-item">
          <span class="info-label">Preferred Height:</span>
          <span class="info-value">160 - 168</span>
        </div>
        <div class="info-item">
          <span class="info-label">Preferred Weight:</span>
          <span class="info-value">50 - 65</span>
        </div>
        <div class="info-item">
          <span class="info-label">Preferred Education:</span>
          <span class="info-value">AAY</span>
        </div>
        <div class="info-item">
          <span class="info-label">Preferred Religion:</span>
          <span class="info-value">Islam</span>
        </div>
        <div class="info-item">
          <span class="info-label">Preferred Caste:</span>
          <span class="info-value">AAY</span>
        </div>
        <div class="info-item">
          <span class="info-label">Preferred Sub-Caste:</span>
          <span class="info-value">AAY</span>
        </div>
        <div class="info-item">
          <span class="info-label">Preferred Marital Status:</span>
          <span class="info-value">Never Married</span>
        </div>
        <div class="info-item">
          <span class="info-label">Preferred Annual Income:</span>
          <span class="info-value">0</span>
        </div>
        <div class="info-item">
          <span class="info-label">Preferred Occupation:</span>
          <span class="info-value">AAY</span>
        </div>
        <div class="info-item">
          <span class="info-label">Preferred Family Status:</span>
          <span class="info-value">Weekly</span>
        </div>
         <div class="info-item">
          <span class="info-label">Preferred Eating Habits:</span>
          <span class="info-value">Weekly</span>
        </div>
         <div class="info-item">
          <span class="info-label">Preferred Ancestor Origin :</span>
          <span class="info-value">Ernakulam</span>
        </div>
         <div class="info-item">
          <span class="info-label">Preferred Residing State :</span>
          <span class="info-value">Kerala</span>
        </div>
      </div>

      <!-- Contact Details Box -->
      <div class="box">
        <div class="box-header">
          <span class="box-icon">📞</span>
          <h2 class="box-title">Contact Details</h2>
        </div>
        <div class="contact-item">
          <span class="contact-label">Customer Name:</span>
          <span class="contact-value">Musthafia</span>
        </div>
        <div class="contact-item">
          <span class="contact-label">Mobile No.:</span>
          <span class="contact-value">+971506668472</span>
        </div>
        <div class="contact-item">
          <span class="contact-label">WhatsApp No.:</span>
          <span class="contact-value">+97150668472</span>
        </div>
        <div class="contact-item">
          <span class="contact-label">Email:</span>
          <span class="contact-value">greenimiringsshop@email.com</span>
        </div>
        <div class="contact-item">
          <span class="contact-label">Alternate Contact:</span>
          <span class="contact-value">+971559161021</span>
        </div>
        <div class="contact-item">
          <span class="contact-label">Client:</span>
          <span class="contact-value">father</span>
        </div>
      </div>
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
        }
      });
    });

    // Logout functionality
    document.querySelector('.logout-btn').addEventListener('click', function() {
      if(confirm('Are you sure you want to logout?')) {
        alert('Logging out...');
      }
    });
  </script>

</body>
</html>