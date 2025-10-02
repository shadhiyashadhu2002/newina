@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="header-left">
            <h1 class="dashboard-title">INA - Staff Dashboard</h1>
            <nav class="main-nav">
                <a href="{{ route('user.dashboard') }}" class="nav-link active">Home</a>
                <a href="{{ route('profile.hellow') }}" class="nav-link">Profiles</a>
                <div class="nav-dropdown">
                    <a href="#" class="nav-link">Sales ▼</a>
                </div>
                <a href="#" class="nav-link">HelpLine</a>
                <div class="nav-dropdown">
                    <a href="{{ route('fresh.data') }}" class="nav-link">Fresh Data</a>
                </div>
                <div class="nav-dropdown">
                    <a href="{{ route('services.page') }}" class="nav-link">Services ▼</a>
                </div>
            </nav>
        </div>
        <div class="header-right">
            <span class="user-info">Welcome, {{ $user->name }} ({{ $user->user_type }})</span>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                @csrf
            </form>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="dashboard-content">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="welcome-card">
                    <h2>Welcome to INA Staff Dashboard</h2>
                    <p>Hello <strong>{{ $user->name }}</strong>, you are logged in as a staff member.</p>
                    
                    <div class="staff-info">
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p><strong>Staff Code:</strong> {{ $user->code }}</p>
                        <p><strong>User Type:</strong> {{ $user->user_type }}</p>
                        @if($user->phone)
                            <p><strong>Phone:</strong> {{ $user->phone }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="dashboard-card">
                    <h4>Quick Actions</h4>
                    <ul>
                        <li><a href="{{ route('profile.hellow') }}">View Profiles</a></li>
                        <li><a href="{{ route('fresh.data') }}">Fresh Data</a></li>
                        <li><a href="{{ route('services.page') }}">Services</a></li>
                        <li><a href="{{ route('active.service') }}">Active Services</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="dashboard-card">
                    <h4>My Tasks</h4>
                    <p>Here you can view your assigned tasks and clients.</p>
                    <!-- Add task-related content here -->
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="dashboard-card">
                    <h4>Recent Activity</h4>
                    <p>Track your recent activities and updates.</p>
                    <!-- Add activity log here -->
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .dashboard-container {
        min-height: 100vh;
        background-color: #f8f9fa;
    }
    
    .dashboard-header {
        background: white;
        padding: 1rem 2rem;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .dashboard-title {
        color: #2c5aa0;
        margin: 0;
        font-size: 1.5rem;
        font-weight: bold;
    }
    
    .main-nav {
        display: flex;
        gap: 2rem;
        margin-left: 2rem;
    }
    
    .nav-link {
        color: #495057;
        text-decoration: none;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        transition: background-color 0.2s;
    }
    
    .nav-link:hover, .nav-link.active {
        background-color: #e9ecef;
        color: #2c5aa0;
        text-decoration: none;
    }
    
    .user-info {
        color: #495057;
        margin-right: 1rem;
        font-weight: 500;
    }
    
    .logout-btn {
        background: #dc3545;
        color: white;
        padding: 0.5rem 1rem;
        text-decoration: none;
        border-radius: 4px;
        transition: background-color 0.2s;
    }
    
    .logout-btn:hover {
        background: #c82333;
        text-decoration: none;
        color: white;
    }
    
    .dashboard-content {
        padding: 2rem;
    }
    
    .welcome-card, .dashboard-card {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        height: 100%;
    }
    
    .welcome-card h2 {
        color: #2c5aa0;
        margin-bottom: 1rem;
    }
    
    .staff-info {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 6px;
        margin-top: 1rem;
    }
    
    .staff-info p {
        margin: 0.5rem 0;
    }
    
    .dashboard-card h4 {
        color: #495057;
        margin-bottom: 1rem;
        border-bottom: 2px solid #2c5aa0;
        padding-bottom: 0.5rem;
    }
    
    .dashboard-card ul {
        list-style: none;
        padding: 0;
    }
    
    .dashboard-card li {
        margin: 0.5rem 0;
    }
    
    .dashboard-card a {
        color: #2c5aa0;
        text-decoration: none;
    }
    
    .dashboard-card a:hover {
        text-decoration: underline;
    }
    
    .alert {
        padding: 0.75rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: 0.25rem;
    }
    
    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }
</style>
@endsection