<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>INA Dashboard - Add New Fresh Data</title>
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
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: calc(100vh - 70px);
    }

    /* Form Container */
    .form-container {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.15);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      width: 100%;
      max-width: 500px;
    }

    .form-title {
      color: #2c3e50;
      font-size: 28px;
      font-weight: 600;
      margin-bottom: 30px;
      text-align: left;
    }

    /* Form Groups */
    .form-group {
      margin-bottom: 25px;
    }

    .form-group label {
      display: block;
      color: #555;
      font-weight: 500;
      font-size: 15px;
      margin-bottom: 8px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid #e0e0e0;
      border-radius: 10px;
      font-size: 15px;
      font-family: inherit;
      transition: all 0.3s ease;
      background: white;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
      outline: none;
      border-color: #4CAF50;
      box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
      transform: translateY(-1px);
    }

  .form-group select {
  -webkit-appearance: none;  /* Safari / Chrome */
  -moz-appearance: none;     /* Firefox */
  appearance: none;          /* Standard */

  cursor: pointer;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 12px center;
  background-size: 16px;
  padding-right: 40px;
}

    .form-group textarea {
      resize: vertical;
      min-height: 100px;
    }

    /* Button Container */
    .button-container {
      display: flex;
      gap: 15px;
      justify-content: center;
      margin-top: 35px;
    }

    .btn {
      padding: 14px 30px;
      border: none;
      border-radius: 25px;
      font-weight: 600;
      cursor: pointer;
      font-size: 15px;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      min-width: 120px;
    }

    .btn-add {
      background: linear-gradient(135deg, #4CAF50, #45a049);
      color: white;
      box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
    }

    .btn-add:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(76, 175, 80, 0.6);
    }

    .btn-back {
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
      color: #6c757d;
      box-shadow: 0 6px 20px rgba(108, 117, 125, 0.2);
      border: 2px solid #dee2e6;
    }

    .btn-back:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(108, 117, 125, 0.3);
      background: linear-gradient(135deg, #e9ecef, #dee2e6);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .main-content {
        padding: 20px;
      }
      
      .form-container {
        padding: 30px 20px;
      }
      
      .button-container {
        flex-direction: column;
      }
      
      .btn {
        width: 100%;
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
        <li><a href="#" data-page="home">Home</a></li>
        <li><a href="#" data-page="profiles">Profiles</a></li>
        <li><a href="#" data-page="sales">Sales <span class="dropdown-arrow">▼</span></a></li>
        <li><a href="#" data-page="helpline">HelpLine</a></li>
        <li><a href="#" data-page="fresh-data" class="active">Fresh Data</a></li>
        <li><a href="#" data-page="abc">abc</a></li>
        <li><a href="#" data-page="services">Services <span class="dropdown-arrow">▼</span></a></li>
      </ul>
    </nav>
    
    <button class="logout-btn">Logout</button>
  </header>

  <!-- Main Content Area -->
  <main class="main-content">
  <div class="form-container" style="margin-bottom: 200px;">
      <h1 class="form-title">Add New Fresh Data</h1>
      
      <form id="freshDataForm" method="POST" action="{{ route('add.fresh.data.store') }}">
        @csrf
        <div class="form-group">
          <label for="mobile">Mobile Number</label>
          <input type="tel" id="mobile" name="mobile" placeholder="Enter mobile number" required>
        </div>

        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" id="name" name="name" placeholder="Enter full name" required>
        </div>

        <div class="form-group">
          <label for="source">Source</label>
          <select id="source" name="source" required>
            <option value="">--Select Call Source--</option>
            <option value="website">Website</option>
            <option value="facebook">Facebook</option>
            <option value="google">Google Ads</option>
            <option value="instagram">Instagram</option>
            <option value="youtube">YouTube</option>
            <option value="referral">Referral</option>
            <option value="direct">Direct Call</option>
            <option value="whatsapp">WhatsApp</option>
            <option value="email">Email</option>
            <option value="newspaper">Newspaper</option>
            <option value="other">Other</option>
          </select>
        </div>

        <div class="form-group">
          <label for="remarks">Remarks</label>
          <textarea id="remarks" name="remarks" placeholder="Enter any additional remarks or notes"></textarea>
        </div>

        <div class="button-container">
          <button type="submit" class="btn btn-add">Add</button>
          <a href="#" class="btn btn-back" onclick="goBack()">Back</a>
        </div>
      </form>
    </div>
  </main>

  <script>
    // Navigation functionality
    document.querySelectorAll('.header-nav a').forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('.header-nav a').forEach(l => l.classList.remove('active'));
        this.classList.add('active');
        const page = this.getAttribute('data-page');
        console.log('Navigating to:', page);
      });
    });

    // Logout functionality
    document.querySelector('.logout-btn').addEventListener('click', function() {
      if(confirm('Are you sure you want to logout?')) {
        alert('Logging out...');
      }
    });

    // Form submission
    document.getElementById('freshDataForm').addEventListener('submit', function(e) {
      // Remove JS-only submission. Let backend handle POST and validation.
    });

    // Back button functionality
    function goBack() {
      if (confirm('Are you sure you want to go back? Any unsaved changes will be lost.')) {
        window.history.back();
      }
    }

    // Auto-format mobile number
    document.getElementById('mobile').addEventListener('input', function(e) {
      let value = e.target.value.replace(/\D/g, '');
      if (value.length > 10) {
        value = value.slice(0, 10);
      }
      e.target.value = value;
    });

    // Auto-capitalize name
    document.getElementById('name').addEventListener('input', function(e) {
      const words = e.target.value.split(' ');
      const capitalizedWords = words.map(word => {
        if (word.length > 0) {
          return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
        }
        return word;
      });
      e.target.value = capitalizedWords.join(' ');
    });
  </script>

</body>
</html>