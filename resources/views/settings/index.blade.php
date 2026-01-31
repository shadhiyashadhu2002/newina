<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - INA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        h1 {
            color: #764ba2;
            margin-bottom: 30px;
            font-size: 32px;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .settings-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 25px;
            border: 2px solid #e9ecef;
        }

        .settings-section h2 {
            color: #764ba2;
            margin-bottom: 20px;
            font-size: 24px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }

        .submit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            margin-top: 10px;
        }

        .submit-btn:hover {
            opacity: 0.9;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }

        .data-table th,
        .data-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .data-table th {
            background: #667eea;
            color: white;
            font-weight: bold;
        }

        .data-table tr:hover {
            background: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-admin {
            background: #28a745;
            color: white;
        }

        .badge-user {
            background: #6c757d;
            color: white;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-item, .dept-item {
            display: inline-block;
            padding: 8px 15px;
            background: #e9ecef;
            border-radius: 20px;
            margin: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('dashboard') }}" class="back-link">← Back to Dashboard</a>
        
        <h1>Settings</h1>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert" style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
            <ul style="margin-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="settings-grid">
            <!-- Add Staff Section -->
            <div class="settings-section">
                <h2>1. Add Staff</h2>
                <form action="{{ route('settings.add-staff') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Staff Name *</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Staff Designation *</label>
                        <input type="text" name="designation" placeholder="e.g., Sales Executive, HR Manager" required>
                    </div>
                    <div class="form-group">
                        <label>Mobile Number *</label>
                        <input type="text" name="phone" placeholder="+91XXXXXXXXXX" required>
                    </div>
                    <div class="form-group">
                        <label>Employee ID *</label>
                        <input type="text" name="emp_id" placeholder="e.g., EMP001" required>
                    </div>
                    <div class="form-group">
                        <label>Role *</label>
                        <select name="role" required>
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>
                    <button type="submit" class="submit-btn">Save Staff</button>
                </form>
            </div>

            <!-- Add Status Section -->
            <div class="settings-section">
                <h2>2. Add Status</h2>
                <form action="{{ route('settings.add-status') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Status *</label>
                        <input type="text" name="status_name" placeholder="e.g., Interested, Not Interested" required>
                    </div>
                    <button type="submit" class="submit-btn">Save Status</button>
                </form>

                <h3 style="margin-top: 25px; color: #333; font-size: 18px;">Existing Statuses</h3>
                <div style="margin-top: 15px;">
                    @forelse($statuses as $status)
                        <span class="status-item">{{ $status }}</span>
                    @empty
                        <p style="color: #666;">No statuses found</p>
                    @endforelse
                </div>
            </div>

            <!-- Add Department Section -->
            <div class="settings-section">
                <h2>3. Add Department</h2>
                <form action="{{ route('settings.add-department') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Department Name *</label>
                        <input type="text" name="department_name" placeholder="e.g., Sales, HR, Operations" required>
                    </div>
                    <button type="submit" class="submit-btn">Save Department</button>
                </form>

                <h3 style="margin-top: 25px; color: #333; font-size: 18px;">Existing Departments</h3>
                <div style="margin-top: 15px;">
                    @forelse($departments as $dept)
                        <span class="dept-item">{{ ucfirst($dept) }}</span>
                    @empty
                        <p style="color: #666;">No departments found</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Staff List Table -->
        <div class="settings-section" style="grid-column: 1 / -1;">
