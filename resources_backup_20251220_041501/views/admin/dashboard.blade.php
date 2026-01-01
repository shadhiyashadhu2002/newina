<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">User Dashboard</a>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        {{ $user->first_name ?? $user->name ?? 'User' }}
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Menu</h6>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('user.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                            <li><a href="{{ route('admin.profile') }}" class="text-decoration-none">My Profile</a></li>
                            <li><a href="#" class="text-decoration-none">My Settings</a></li>
                            <li><a href="#" class="text-decoration-none">My Activity</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="col-md-10">
                <!-- Welcome Section -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Welcome, {{ $user->first_name ?? $user->name ?? 'User' }}!</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Profile Information</h6>
                                        <p><strong>Name:</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
                                        <p><strong>Email:</strong> {{ $user->email }}</p>
                                        <p><strong>Phone:</strong> {{ $user->phone }}</p>
                                        <p><strong>User Type:</strong> {{ $user->user_type }}</p>
                                        <p><strong>Member Since:</strong> {{ $user->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Quick Actions</h6>
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-outline-primary">Update Profile</button>
                                            <button class="btn btn-outline-secondary">Change Password</button>
                                            <button class="btn btn-outline-info">View My Activity</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Stats -->
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5>Account Status</h5>
                                @if($user->blocked)
                                    <span class="badge bg-danger fs-6">Blocked</span>
                                @elseif($user->deactivated)
                                    <span class="badge bg-warning fs-6">Deactivated</span>
                                @else
                                    <span class="badge bg-success fs-6">Active</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5>Balance</h5>
                                <h4>${{ number_format($user->balance, 2) }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5>Membership</h5>
                                <span class="badge bg-info fs-6">{{ $user->membership ? 'Active' : 'Inactive' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Section -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6>Recent Activity</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">No recent activity to display.</p>
                                <small class="text-muted">Your activities and transactions will appear here.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>