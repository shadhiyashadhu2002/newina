<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Expired Services</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        /* Header styles */
        .main-header {
            background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
            color: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }
        
        .header-nav {
            list-style: none;
            display: flex;
            gap: 2rem;
            align-items: center;
        }
        
        .header-nav li a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        
        .header-nav li a:hover,
        .header-nav li a.active {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        /* Container styles */
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .page-title {
            font-size: 2rem;
            color: #333;
            margin-bottom: 2rem;
            text-align: center;
        }
        
        /* Table styles */
        .table-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 2rem;
        }
        
        .table-title {
            font-size: 1.5rem;
            color: #ac0742;
            margin-bottom: 1rem;
        }
        
        .services-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        .services-table th,
        .services-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .services-table th {
            background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
            color: white;
            font-weight: 600;
        }
        
        .services-table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        /* Search and controls styles */
        .controls-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .search-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .search-form input,
        .search-form select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .search-btn {
            background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .search-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(172, 7, 66, 0.3);
        }
        
        /* Status badge */
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            background: #dc3545;
            color: white;
        }
        
        /* Delete info styles */
        .delete-info {
            font-size: 12px;
            color: #666;
            line-height: 1.4;
        }
        
        .delete-comment {
            font-style: italic;
            color: #888;
            margin-top: 4px;
            max-width: 200px;
            word-wrap: break-word;
        }
        
        /* Pagination styles */
        .pagination-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .pagination-info {
            color: #666;
            font-size: 14px;
        }
        
        .pagination-nav {
            display: flex;
            gap: 5px;
            align-items: center;
        }
        
        .pagination-nav a,
        .pagination-nav span {
            padding: 8px 12px;
            text-decoration: none;
            border: 1px solid #ddd;
            border-radius: 5px;
            color: #666;
            font-size: 14px;
        }
        
        .pagination-nav a:hover {
            background: #f8f9fa;
        }
        
        .pagination-nav .current {
            background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
            color: white;
            border-color: #ac0742;
        }
        
        /* Back button */
        .back-btn {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }
        
        .back-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(108, 117, 125, 0.3);
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
        <li><a href="{{ route('services.page') }}">Services <span class="dropdown-arrow">▼</span></a></li>
      </ul>
    </nav>
  </header>

  <div class="container">
    <h1 class="page-title">Expired Services</h1>
    
    <a href="{{ route('services.page') }}" class="back-btn">
      <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
      </svg>
      Back to Active Services
    </a>
    
    <div class="table-container">
      <div class="controls-container">
        <h2 class="table-title">Expired Services
          @if(isset($services) && method_exists($services, 'total'))
          <span style="font-size: 14px; font-weight: normal; color: #666;">
            ({{ $services->firstItem() ?? 0 }} - {{ $services->lastItem() ?? 0 }} of {{ $services->total() }} expired services)
          </span>
          @endif
        </h2>
        
        <!-- Search Form -->
        <form method="GET" action="{{ route('expired.services') }}" class="search-form">
          <select name="per_page" onchange="this.form.submit()">
            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>Show 10</option>
            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>Show 50</option>
            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>Show 100</option>
          </select>
          
          <input type="text" name="search" placeholder="Search expired services..." 
                 value="{{ request('search') }}" style="min-width: 250px;">
          
          <button type="submit" class="search-btn">Search</button>
          
          @if(request('search'))
          <a href="{{ route('expired.services') }}" style="text-decoration: none; color: #dc3545; font-size: 12px;">Clear</a>
          @endif
        </form>
      </div>

      <table class="services-table">
        <thead>
          <tr>
            <th>Profile ID</th>
            <th>Name</th>
            <th>Plan</th>
            <th>Service Executive</th>
            <th>Status</th>
            <th>Deleted At</th>
            <th>Delete Reason</th>
          </tr>
        </thead>
        <tbody>
        @if(isset($services) && count($services) > 0)
          @foreach($services as $service)
          <tr>
            <td>{{ $service->profile_id }}</td>
            <td>{{ $service->name }}</td>
            <td>{{ $service->plan_name }}</td>
            <td>{{ $service->service_executive }}</td>
            <td><span class="status-badge">Expired</span></td>
            <td>
              <div class="delete-info">
                {{ $service->deleted_at ? \Carbon\Carbon::parse($service->deleted_at)->format('d-M-Y H:i') : 'N/A' }}
                @if($service->deleted_by)
                <br><small>By: {{ \App\Models\User::find($service->deleted_by)->name ?? 'Unknown' }}</small>
                @endif
              </div>
            </td>
            <td>
              <div class="delete-comment">
                {{ $service->delete_comment ?? 'No reason provided' }}
              </div>
            </td>
          </tr>
          @endforeach
        @else
          <tr><td colspan="7" style="text-align:center;">No expired services found.</td></tr>
        @endif
        </tbody>
      </table>
      
      <!-- Pagination -->
      @if(isset($services) && method_exists($services, 'links'))
      <div class="pagination-wrapper">
        <div class="pagination-info">
          Showing {{ $services->firstItem() ?? 0 }} to {{ $services->lastItem() ?? 0 }} of {{ $services->total() }} results
        </div>
        <div class="pagination-nav">
          {!! $services->links() !!}
        </div>
      </div>
      @endif
    </div>
  </div>

</body>
</html>