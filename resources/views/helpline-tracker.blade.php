<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Helpline Tracker - INA Matrimony</title>
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
        /* Header */
        .main-header {
            background: linear-gradient(135deg, #ac0742, #9d1955);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }
        .nav-menu {
            display: flex;
            list-style: none;
            gap: 25px;
            align-items: center;
        }
        .nav-link {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            font-size: 15px;
            transition: color 0.3s;
            padding: 8px 15px;
            border-radius: 5px;
        }
        .nav-link:hover, .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
        }
        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }
        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        /* Main Container */
        .container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .page-header {
            background: white;
            padding: 25px 30px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        .page-title {
            font-size: 24px;
            color: #ac0742;
            font-weight: 600;
        }
        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .btn-primary {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background: #45a049;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(76, 175, 80, 0.3);
        }
        .search-box {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            width: 250px;
        }
        /* Table Container */
        .table-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        .entries-selector {
            margin-bottom: 15px;
            font-size: 14px;
        }
        .entries-selector select {
            padding: 5px 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin: 0 5px;
        }
        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
        }
        table thead {
            background: linear-gradient(135deg, #ac0742, #9d1955);
            color: white;
        }
        table th {
            padding: 12px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            white-space: nowrap;
        }
        table td {
            padding: 12px 10px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 13px;
        }
        table tbody tr:hover {
            background: #f8f9fa;
        }
        /* Action Buttons */
        .action-btns {
            display: flex;
            gap: 8px;
        }
        .btn-edit {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        .btn-delete {
            background: #f44336;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        .btn-edit:hover {
            background: #45a049;
        }
        .btn-delete:hover {
            background: #da190b;
        }
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .modal.active {
            display: flex;
        }
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        .modal-title {
            font-size: 22px;
            color: #ac0742;
            font-weight: 600;
        }
        .close-btn {
            background: none;
            border: none;
            font-size: 28px;
            color: #999;
            cursor: pointer;
            line-height: 1;
        }
        .close-btn:hover {
            color: #333;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        .form-group label {
            font-size: 13px;
            color: #555;
            margin-bottom: 6px;
            font-weight: 500;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .checkbox-group input[type="checkbox"] {
            width: auto;
        }
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 25px;
        }
        .btn-cancel {
            background: #999;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            cursor: pointer;
        }
        .btn-submit {
            background: #ac0742;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            cursor: pointer;
        }
        .btn-cancel:hover {
            background: #888;
        }
        .btn-submit:hover {
            background: #9d1955;
        }
        /* Alert Messages */
        .alert {
            padding: 12px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: none;
        }
        .alert.show {
            display: block;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        /* Pagination */
        .pagination-wrapper {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }
        /* Responsive */
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .header-actions {
                width: 100%;
                justify-content: space-between;
            }
            .search-box {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <a href="{{ route('dashboard') }}" class="logo">INA</a>
        <nav>
            <ul class="nav-menu">
                <li><a href="{{ route('dashboard') }}" class="nav-link">Home</a></li>
                <li><a href="{{ route('profile.hellow') }}" class="nav-link">Profiles</a></li>
                <li><a href="{{ route('addsale.page') }}" class="nav-link">Sales</a></li>
                <li><a href="{{ route('helpline.index') }}" class="nav-link active">HelpLine</a></li>
                <li><a href="{{ route('fresh.data.index') }}" class="nav-link">Fresh Data</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </li>
            </ul>
        </nav>
    </header>

    <!-- Main Container -->
    <div class="container">
        <!-- Success Message -->
        @if(session('success'))
        <div class="alert alert-success show">
            {{ session('success') }}
        </div>
        @endif

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Helpline Tracker</h1>
            <div class="header-actions">
                <form method="GET" action="{{ route('helpline.index') }}" style="display: inline;">
                    <input type="text" name="search" class="search-box" placeholder="Search..." value="{{ request('search') }}">
                </form>
                <button class="btn-primary" onclick="openModal()">
                    <span>➕</span> Add New Query
                </button>
            </div>
        </div>

        <!-- Table Container -->
        <div class="table-container">
            <div class="entries-selector">
                Show 
                <select onchange="window.location.href='?per_page='+this.value">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>
                entries
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Call Status</th>
                        <th>Nature of Call</th>
                        <th>Video Source</th>
                        <th>Video Reference</th>
                        <th>Mobile Number</th>
                        <th>Profile ID</th>
                        <th>Executive Name</th>
                        <th>Remarks</th>
                        <th>Purpose</th>
                        <th>New Lead</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($queries as $query)
                    <tr>
                        <td>{{ $query->date->format('d-M-Y') }}</td>
                        <td>{{ $query->call_status ?? '-' }}</td>
                        <td>{{ $query->nature_of_call ?? '-' }}</td>
                        <td>{{ $query->video_source ?? '-' }}</td>
                        <td>{{ $query->video_reference ?? '-' }}</td>
                        <td>{{ $query->mobile_number ?? '-' }}</td>
                        <td>{{ $query->profile_id ?? '-' }}</td>
                        <td>{{ $query->executive_name ?? '-' }}</td>
                        <td>{{ Str::limit($query->remarks, 30) ?? '-' }}</td>
                        <td>{{ $query->purpose ?? '-' }}</td>
                        <td>{{ $query->new_lead ? 'true' : 'false' }}</td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-edit" onclick='openEditModal(@json($query))'>✏️ Edit</button>
                                <form method="POST" action="{{ route('helpline.destroy', $query->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">🗑️ Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" style="text-align: center; padding: 30px; color: #999;">
                            No queries found. Click "Add New Query" to create your first entry.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $queries->links('pagination::custom-clean') }}
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="queryModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalTitle">Add New Query</h2>
                <button class="close-btn" onclick="closeModal()">×</button>
            </div>
            <form id="queryForm" method="POST" action="{{ route('helpline.store') }}">
                @csrf
                <input type="hidden" id="method" name="_method" value="POST">
                <input type="hidden" id="queryId" name="query_id">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>Date *</label>
                        <input type="date" name="date" id="date" required>
                    </div>
                    <div class="form-group">
                        <label>Call Status</label>
                        <select name="call_status" id="call_status">
                            <option value="">Select...</option>
                            <option value="Whatsapp">Whatsapp</option>
                            <option value="Call">Call</option>
                            <option value="Email">Email</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Nature of Call</label>
                        <select name="nature_of_call" id="nature_of_call">
                            <option value="">Select...</option>
                            <option value="Whatsapp Message">Whatsapp Message</option>
                            <option value="Phone Call">Phone Call</option>
                            <option value="Video Call">Video Call</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Video Source</label>
                        <select name="video_source" id="video_source">
                            <option value="">Select...</option>
                            <option value="Instagram">Instagram</option>
                            <option value="Facebook">Facebook</option>
                            <option value="YouTube">YouTube</option>
                            <option value="WhatsApp Status">WhatsApp Status</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Video Reference</label>
                        <input type="text" name="video_reference" id="video_reference" placeholder="Advertisement">
                    </div>
                    <div class="form-group">
                        <label>Mobile Number</label>
                        <input type="text" name="mobile_number" id="mobile_number" placeholder="Enter mobile number">
                    </div>
                    <div class="form-group">
                        <label>Profile ID</label>
                        <input type="text" name="profile_id" id="profile_id" placeholder="Enter profile ID">
                    </div>
                    <div class="form-group">
                        <label>Executive Name</label>
                        <input type="text" name="executive_name" id="executive_name" placeholder="Enter executive name">
                    </div>
                    <div class="form-group">
                        <label>Purpose</label>
                        <select name="purpose" id="purpose">
                            <option value="">Select...</option>
                            <option value="Product Information">Product Information</option>
                            <option value="Service Inquiry">Service Inquiry</option>
                            <option value="Complaint">Complaint</option>
                            <option value="Follow-up">Follow-up</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="checkbox-group">
                            <input type="checkbox" name="new_lead" id="new_lead" value="1">
                            <span>New Lead</span>
                        </label>
                    </div>
                    <div class="form-group full-width">
                        <label>Remarks</label>
                        <textarea name="remarks" id="remarks" placeholder="Enter any additional remarks"></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Save Query</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('queryModal').classList.add('active');
            document.getElementById('modalTitle').textContent = 'Add New Query';
            document.getElementById('queryForm').action = '{{ route("helpline.store") }}';
            document.getElementById('method').value = 'POST';
            document.getElementById('queryForm').reset();
            document.getElementById('date').valueAsDate = new Date();
        }

        function openEditModal(query) {
            document.getElementById('queryModal').classList.add('active');
            document.getElementById('modalTitle').textContent = 'Edit Query';
            document.getElementById('queryForm').action = '/helpline/' + query.id;
            document.getElementById('method').value = 'PUT';
            
            // Populate form fields
            document.getElementById('date').value = query.date;
            document.getElementById('call_status').value = query.call_status || '';
            document.getElementById('nature_of_call').value = query.nature_of_call || '';
            document.getElementById('video_source').value = query.video_source || '';
            document.getElementById('video_reference').value = query.video_reference || '';
            document.getElementById('mobile_number').value = query.mobile_number || '';
            document.getElementById('profile_id').value = query.profile_id || '';
            document.getElementById('executive_name').value = query.executive_name || '';
            document.getElementById('remarks').value = query.remarks || '';
            document.getElementById('purpose').value = query.purpose || '';
            document.getElementById('new_lead').checked = query.new_lead;
        }

        function closeModal() {
            document.getElementById('queryModal').classList.remove('active');
        }

        // Close modal when clicking outside
        document.getElementById('queryModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Auto-hide success message
        setTimeout(function() {
            const alert = document.querySelector('.alert.show');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 5000);
    </script>
</body>
</html>
