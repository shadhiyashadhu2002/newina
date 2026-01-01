<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sales Dashboard - INA Matrimony</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      padding: 20px;
    }
    .dashboard-container {
      max-width: 1400px;
      margin: 0 auto;
      background: white;
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
    .dashboard-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      padding-bottom: 20px;
      border-bottom: 3px solid #667eea;
    }
    .dashboard-title {
      font-size: 2.5rem;
      font-weight: bold;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .back-btn {
      padding: 12px 30px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      border-radius: 25px;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
      transition: transform 0.2s;
    }
    .back-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
      color: white;
    }
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }
    .stat-card-link {
      text-decoration: none;
      display: block;
    }
    .stat-card {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      transition: transform 0.3s;
      position: relative;
      overflow: hidden;
      cursor: pointer;
    }
    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    .stat-card::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 200%;
      height: 200%;
      background: rgba(255,255,255,0.1);
      transform: rotate(45deg);
      transition: all 0.5s;
    }
    .stat-card:hover::before {
      top: -100%;
      right: -100%;
    }
    .stat-card h3 {
      font-size: 1.1rem;
      margin-bottom: 15px;
      opacity: 0.9;
      position: relative;
      z-index: 1;
    }
    .stat-number {
      font-size: 2.5rem;
      font-weight: bold;
      position: relative;
      z-index: 1;
    }
    .stat-card.green { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .stat-card.orange { background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%); }
    .stat-card.blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .stat-card.teal { background: linear-gradient(135deg, #0575e6 0%, #021b79 100%); }
    .stat-card.red { background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); }
    .stat-card.dark-green { background: linear-gradient(135deg, #134e5e 0%, #71b280 100%); }
    .stat-card.sales-card {
      background: linear-gradient(135deg, #fc4a1a 0%, #f7b733 100%);
    }
    .sales-amount {
      font-size: 2.5rem;
      font-weight: bold;
      margin-bottom: 10px;
    }
    .sales-target {
      font-size: 1rem;
      opacity: 0.9;
      margin-bottom: 10px;
    }
    .achievement-badge {
      display: inline-block;
      background: rgba(255,255,255,0.2);
      padding: 5px 15px;
      border-radius: 20px;
      font-size: 0.9rem;
      margin-bottom: 15px;
    }
    .progress-bar {
      width: 100%;
      height: 10px;
      background: rgba(255,255,255,0.3);
      border-radius: 5px;
      overflow: hidden;
    }
    .progress-fill {
      height: 100%;
      background: white;
      border-radius: 5px;
      transition: width 1s ease;
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <div class="dashboard-header">
      <h1 class="dashboard-title">📊 Sales Dashboard</h1>
      <a href="{{ route('dashboard') }}" class="back-btn">← Back to Dashboard</a>
    </div>

    <div class="stats-grid">
      <!-- Follow-up Today - Clickable -->
      <a href="{{ route('profiles.followup.today') }}" class="stat-card-link">
        <div class="stat-card green">
          <h3>Follow-up Today</h3>
          <div class="stat-number">{{ $stats['followup_today'] ?? 0 }}</div>
        </div>
      </a>

      <!-- Follow-up Due - Clickable -->
      <a href="{{ route('profiles.followup.due') }}" class="stat-card-link">
        <div class="stat-card orange">
          <h3>Follow-up Due</h3>
          <div class="stat-number">{{ $stats['followup_due'] ?? 0 }}</div>
        </div>
      </a>

      <!-- New Profiles - Clickable -->
      <a href="{{ route('assigned.profiles.view') }}" class="stat-card-link">
        <div class="stat-card blue">
          <h3>New Profiles</h3>
          <div class="stat-number">{{ $stats['new_profiles'] ?? 0 }}</div>
        </div>
      </a>

      <!-- Reassigned Profiles - Not clickable (no route yet) -->
      <div class="stat-card teal">
        <h3>Reassigned Profiles</h3>
        <div class="stat-number">{{ $stats['reassigned_profiles'] ?? 0 }}</div>
      </div>

      <!-- Assigned Today - Not clickable (no route yet) -->
      <div class="stat-card red">
        <h3>Assigned Today</h3>
        <div class="stat-number">{{ $stats['assigned_today'] ?? 0 }}</div>
      </div>

      <!-- Clients Contacted - Not clickable (no route yet) -->
      <div class="stat-card dark-green">
        <h3>Clients Contacted</h3>
        <div class="stat-number">{{ $stats['clients_contacted'] ?? 0 }}</div>
      </div>

      <!-- Total Sales - Not clickable -->
      <div class="stat-card sales-card">
        <h3>Total Sales</h3>
        <div class="sales-amount">₹ {{ $stats['total_sales'] ?? '0' }}</div>
        <div class="sales-target">Target: ₹{{ $stats['target'] ?? '50,000' }}</div>
        <div class="achievement-badge">{{ $stats['achievement_percentage'] ?? 0 }}% Achieved</div>
        <div class="progress-bar">
          <div class="progress-fill" style="width: {{ $stats['achievement_percentage'] ?? 0 }}%"></div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
