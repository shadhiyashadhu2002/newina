<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Loading Test</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h1>Dashboard Loading Test</h1>
    
    @if(session('success'))
        <div style="background: green; color: white; padding: 10px; margin: 10px 0;">
            {{ session('success') }}
        </div>
    @endif
    
    <div>
        <h2>Current User Info:</h2>
        @if(isset($currentUser) && $currentUser)
            <p><strong>Name:</strong> {{ $currentUser->name }}</p>
            <p><strong>Email:</strong> {{ $currentUser->email }}</p>
            <p><strong>User Type:</strong> {{ $currentUser->user_type ?? 'Not set' }}</p>
            <p><strong>Is Admin:</strong> {{ $currentUser->is_admin ? 'Yes' : 'No' }}</p>
        @else
            <p>No user information available</p>
        @endif
    </div>
    
    <div>
        <h2>Statistics:</h2>
        @if(isset($stats))
            <p><strong>Total Users:</strong> {{ $stats['total_users'] ?? 'N/A' }}</p>
            <p><strong>New Profiles Today:</strong> {{ $stats['new_profiles'] ?? 'N/A' }}</p>
        @else
            <p>No statistics available</p>
        @endif
    </div>
    
    <div>
        <h2>Navigation:</h2>
        <ul>
            <li><a href="/profile">Profiles</a></li>
            <li><a href="/fresh-data">Fresh Data</a></li>
            <li><a href="/services">Services</a></li>
        </ul>
    </div>
    
    <div>
        <form action="/logout" method="POST" style="margin-top: 20px;">
            @csrf
            <button type="submit" style="background: red; color: white; padding: 10px 20px; border: none;">Logout</button>
        </form>
    </div>
</body>
</html>