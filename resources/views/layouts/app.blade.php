<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @yield('styles')
</head>
<body>
    <header>
    </header>
    <style>
        .dashboard-header { background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%); padding: 15px 30px; display:flex; justify-content:space-between; align-items:center; color:white; border-bottom: 1px solid rgba(255,255,255,0.3); }
        .header-left { display:flex; align-items:center; }
        .dashboard-title { color: white; font-size: 24px; margin:0 30px 0 0; font-weight:bold; }
        .main-nav { display:flex; gap:20px; align-items:center; position:relative; }
        .nav-link { color: white; text-decoration:none; padding:8px 15px; border-radius:5px; transition: background 0.3s; }
        .nav-link:hover, .nav-link.active { background: rgba(255,255,255,0.2); }
        .nav-dropdown { position: relative; display: inline-block; }
        .dropdown-content { display: none; position: absolute; background: white; min-width: 200px; box-shadow: 0 8px 16px rgba(0,0,0,0.2); z-index: 999; top: 100%; left: 0; border-radius: 5px; padding: 0; margin-top: 5px; }
        .nav-dropdown:hover .dropdown-content { display: block; }
        .dropdown-item { color: #333; padding: 12px 16px; text-decoration: none; display: block; }
        .dropdown-item:hover { background: #f0f0f0; }
        .header-right { display:flex; align-items:center; }
        .logout-btn { background: transparent; border:1px solid white; color:white; padding:8px 16px; border-radius:5px; cursor:pointer; transition: background 0.3s; font-weight:500; }
        .logout-btn:hover { background: rgba(255,255,255,0.1); }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var logoutBtn = document.getElementById('logout-btn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if(confirm('Are you sure you want to logout?')) {
                        document.getElementById('logout-form').submit();
                    }
                });
            }
        });
    </script>
    <main class="py-4">
        @yield('content')
    </main>
    @yield('scripts')
    @stack('page-scripts')
</body>
</html>