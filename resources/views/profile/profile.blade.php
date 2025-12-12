<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>INA Dashboard - Profiles</title>
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

    /* Main Dashboard Header */
    .main-header {
      background: linear-gradient(135deg, #ac0742, #9d1955);
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
    }

    .page-title {
      color: #fff;
      font-size: 28px;
      font-weight: 600;
      margin-bottom: 25px;
      text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    /* Top buttons and filters */
    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      flex-wrap: wrap;
      gap: 15px;
    }

    .btn-add {
      background: linear-gradient(135deg, #4CAF50, #45a049);
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 25px;
      font-weight: 600;
      cursor: pointer;
      font-size: 14px;
      box-shadow: 0 4px 15px rgba(76, 175, 80, 0.4);
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .btn-add:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(76, 175, 80, 0.6);
    }

    .filters {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }

    .filters button {
      background: rgba(255, 255, 255, 0.9);
      border: 1px solid rgba(255, 255, 255, 0.3);
      padding: 8px 16px;
      border-radius: 20px;
      cursor: pointer;
      font-size: 13px;
      font-weight: 500;
      transition: all 0.3s ease;
      white-space: nowrap;
      color: #555;
      backdrop-filter: blur(10px);
    }

    .filters button:hover {
      background: rgba(255, 255, 255, 1);
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .filters button.active {
      background: #4CAF50;
      color: white;
      border-color: #4CAF50;
      box-shadow: 0 4px 15px rgba(76, 175, 80, 0.4);
    }

    /* Table container */
    .table-container {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      padding: 25px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.3);
    }

    /* Search and entries */
    .table-controls {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      font-size: 14px;
      color: #666;
    }

    .table-controls select, 
    .table-controls input {
      padding: 8px 12px;
      border: 2px solid #e0e0e0;
      border-radius: 8px;
      font-size: 14px;
      transition: all 0.3s ease;
      background: white;
    }

    .table-controls select:focus,
    .table-controls input:focus {
      outline: none;
      border-color: #4CAF50;
      box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
    }

    .table-controls input {
      width: 250px;
    }

    /* Table styling */
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
      background: #fff;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      border: 1px solid #d0e8f2;
    }

    table th, table td {
      padding: 15px 12px;
      text-align: center;
      vertical-align: middle;
      border: 1px solid #d0e8f2;
    }

    table th {
      background: linear-gradient(135deg, #f8fbff, #e8f4fd);
      font-weight: 600;
      color: #2c3e50;
      text-transform: uppercase;
      font-size: 12px;
      letter-spacing: 0.5px;
      border-bottom: 2px solid #d0e8f2;
    }

    table tbody tr {
      transition: all 0.3s ease;
    }

    table tbody tr:nth-child(even) {
      background: #f8fbff;
    }

    table tbody tr:hover {
      background: #e0f2fe;
      transform: scale(1.01);
      box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    table th:first-child,
    table td:first-child {
      width: 40px;
    }

    /* Profile ID links */
    a.profile-link {
      color: #ac0742;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }

    a.profile-link:hover {
      color: #9d1955;
      text-decoration: underline;
    }

    /* Status styling */
    .status-postpone {
      background: linear-gradient(135deg, #fff3e0, #ffcc02);
      color: #e65100;
      padding: 6px 12px;
      border-radius: 15px;
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
    }

    .status-interest {
      background: linear-gradient(135deg, #e8f5e8, #4CAF50);
      color: #1b5e20;
      padding: 6px 12px;
      border-radius: 15px;
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
    }

    /* Action buttons */
    .actions {
      display: flex;
      gap: 8px;
      justify-content: center;
    }

    .actions button {
      padding: 8px 12px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 16px;
      transition: all 0.3s ease;
      min-width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .actions .edit {
      background: linear-gradient(135deg, #17a2b8, #138496);
      color: white;
      box-shadow: 0 4px 15px rgba(23, 162, 184, 0.4);
    }

    .actions .edit:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(23, 162, 184, 0.6);
    }

    .actions .delete {
      background: linear-gradient(135deg, #dc3545, #c82333);
      color: white;
      box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
    }

    .actions .delete:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(220, 53, 69, 0.6);
    }

    /* Checkbox styling */
    input[type="checkbox"] {
      width: 18px;
      height: 18px;
      cursor: pointer;
      accent-color: #4CAF50;
    }

    /* Empty cell styling */
    .empty-cell {
      color: #999;
      font-style: italic;
    }

    /* Names styling */
    .name-cell {
      font-weight: 500;
      color: #2c3e50;
    }

    /* Email styling */
    .email-cell {
      color: #666;
      font-size: 13px;
    }
  </style>
</head>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<body>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Main Dashboard Header -->
  <header class="main-header">
    <a href="#" class="header-brand">INA</a>
    
    <nav>
      <ul class="header-nav">
  <li><a href="{{ route('dashboard') }}">Home</a></li>
  <li><a href="{{ route('profile.hellow') }}" class="active">Profiles</a></li>
    <li><a href="{{ route('sales.management') }}">Sales <span class="dropdown-arrow">▼</span></a></li>
    <li><a href="#" data-page="helpline">HelpLine</a></li>
    <li><a href="{{ route('fresh.data') }}">Fresh Data <span class="dropdown-arrow">▼</span></a></li>
    <li><a href="#" data-page="abc">abc</a></li>
  <li><a href="{{ route('services.page') }}">Services <span class="dropdown-arrow">▼</span></a></li>
      </ul>
    </nav>
    
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
      @csrf
    </form>
    <button class="logout-btn" id="logout-btn">Logout</button>
  </header>

  <!-- Main Content Area -->
  <main class="main-content">
    <h1 class="page-title">Profiles</h1>

    <div class="top-bar">
      <div class="filters">
        <button onclick="window.location.href='{{ route('profile.hellow', ['filter' => '12_more_days']) }}'" class="{{ request('filter') == '12_more_days' ? 'active' : '' }}">12 More Days Data</button>
        <button onclick="window.location.href='{{ route('profile.hellow', ['filter' => 'no_welcome_calls']) }}'" class="{{ request('filter') == 'no_welcome_calls' ? 'active' : '' }}">No Welcome Calls</button>
        <button onclick="window.location.href='{{ route('profile.hellow', ['filter' => 'postpone_payment']) }}'" class="{{ request('filter') == 'postpone_payment' ? 'active' : '' }}">Post Pone Payment</button>
        <button onclick="window.location.href='{{ route('profile.hellow', ['filter' => 'not_assigned']) }}'" class="{{ request('filter') == 'not_assigned' ? 'active' : '' }}">Not Assigned</button>
        <button onclick="window.location.href='{{ route('profile.hellow', ['filter' => 'zero_followups']) }}'" class="{{ request('filter') == 'zero_followups' ? 'active' : '' }}">ZERO Follow-Ups</button>
        <button onclick="window.location.href='{{ route('profile.hellow', ['filter' => 'followup_today']) }}'" class="{{ request('filter') == 'followup_today' ? 'active' : '' }}">Followup Today</button>
        <button onclick="window.location.href='{{ route('profile.hellow', ['filter' => 'followup_due']) }}'" class="{{ request('filter') == 'followup_due' ? 'active' : '' }}">Followup Due</button>
        <button onclick="window.location.href='{{ route('profile.hellow') }}'" class="{{ !request('filter') || request('filter') == 'all' ? 'active' : '' }}">All</button>
      </div>
      <a href="{{ route('profile.addnew') }}" class="btn-add" style="text-decoration:none;">
        <span>⊕</span>
        Add New Profile
      </a>
    </div>

    <div class="table-container">
      <div class="table-controls">
        <div>
          Show
          <select>
            <option>10</option>
            <option>25</option>
            <option>50</option>
            <option>100</option>
          </select>
          entries
        </div>
        <div>
          Search: <input type="text" placeholder="Search profiles..." />
        </div>
      </div>

      <table>
        <thead>
          <tr>
            <th></th>
            <th>Profile ID</th>
            <th>Name</th>
            <th>Reg Date</th>
            <th>Assign Date</th>
            <th>Followup Date</th>
            <th>Assigned To</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        @foreach($profiles as $user)
          <tr>
            <td><input type="checkbox"></td>
            <td><a href="#" class="profile-link">{{ $user->imid ?? $user->id }}</a></td>
            <td class="name-cell">{{ $user->customer_name ?? $user->name ?? '-' }}</td>
            <td>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d-M-Y') : '-' }}</td>
            <td>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d-M-Y') : '-' }}</td>
            <td>{{ $user->follow_up_date ? \Carbon\Carbon::parse($user->follow_up_date)->format('d-M-Y') : '-' }}</td>
            <td class="email-cell">{{ $user->assigned_to ?? '-' }}</td>
            <td>{{ $user->status ?? '-' }}</td>
            <td class="actions">
              <button class="btn btn-sm btn-info" onclick="viewHistory({{ $user->id }})" title="History" style="margin-right: 5px;">📜</button>
              <button class="edit" onclick="editProfile({{ $user->id }})">✏️</button>
              <button class="delete" onclick="deleteProfile({{ $user->id }})">🗑️</button>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
      <div style="margin-top: 20px; display: flex; justify-content: center;">
        {{ $profiles->links() }}
      </div>
    </div>
  </main>

  <script>
    // Logout functionality
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
    // Logout functionality
    document.querySelector('.logout-btn').addEventListener('click', function() {
      if(confirm('Are you sure you want to logout?')) {
        alert('Logging out...');
        // Add logout logic here
      }
    });
    document.querySelectorAll('.delete').forEach(button => {

      button.addEventListener('click', function() {
        const row = this.closest('tr');
        const profileId = row.querySelector('.profile-link').textContent;
        if(confirm('Are you sure you want to delete profile ' + profileId + '?')) {
          row.remove();
        }
      });
    });

    // Search functionality
    document.querySelector('input[type="text"]').addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase();
      const rows = document.querySelectorAll('tbody tr');
      
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });
    // Entries per page functionality
    document.querySelector('select').addEventListener('change', function() {
      console.log('Entries per page changed to:', this.value);
    });
  </script>

<!-- Edit Profile Modal -->

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Edit Profile Follow-up</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editProfileForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_profile_id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Customer Name</label>
                                <input type="text" class="form-control" id="edit_customer_name" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Profile ID</label>
                                <input type="text" class="form-control" id="edit_profile_im_id" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Current Date</label>
                                <input type="date" class="form-control" id="edit_current_date" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_status" required>
                                    <option value="">Select Status</option>
                                    <option value="RNR">RNR</option>
                                    <option value="Other State">Other State</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Another Call">Another Call</option>
                                    <option value="Busy">Busy</option>
                                    <option value="Call Back">Call Back</option>
                                    <option value="CNC">CNC</option>
                                    <option value="Created">Created</option>
                                    <option value="Marriage Fixed">Marriage Fixed</option>
                                    <option value="NI">NI</option>
                                    <option value="Switch Off">Switch Off</option>
                                    <option value="Wrong Number">Wrong Number</option>
                                    <option value="Duplicate">Duplicate</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Next Follow-up Date</label>
                        <input type="date" class="form-control" id="edit_next_follow_up_date">
                    </div>

                    <div class="form-group">
                        <label>Remarks</label>
                        <textarea class="form-control" id="edit_remarks" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editProfile(profileId) {
    console.log('Edit clicked for profile:', profileId);
    
    $('#editProfileModal').modal('show');
    
    $.ajax({
        url: '/profiles/' + profileId + '/edit-data',
        type: 'GET',
        success: function(response) {
            console.log('Profile data loaded:', response);
            
            $('#edit_profile_id').val(response.id);
            $('#edit_customer_name').val(response.customer_name || response.name || 'N/A');
            $('#edit_profile_im_id').val(response.imid || response.profile_id || response.id);
            $('#edit_current_date').val(new Date().toISOString().split('T')[0]);
            $('#edit_status').val(response.status || '');
            $('#edit_next_follow_up_date').val(response.follow_up_date || '');
            $('#edit_remarks').val('');
        },
        error: function(xhr) {
            console.error('Error loading profile:', xhr);
            alert('Error loading profile data');
            $('#editProfileModal').modal('hide');
        }
    });
}

$(document).ready(function() {
    $('#editProfileForm').on('submit', function(e) {
        e.preventDefault();
        
        const profileId = $('#edit_profile_id').val();
        const submitBtn = $(this).find('button[type="submit"]');
        
        submitBtn.prop('disabled', true).text('Saving...');
        
        $.ajax({
            url: '/profiles/' + profileId + '/update-followup',
            type: 'PUT',
            data: {
                status: $('#edit_status').val(),
                follow_up_date: $('#edit_next_follow_up_date').val(),
                remarks: $('#edit_remarks').val(),
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log('Update successful:', response);
                alert('Profile updated successfully!');
                location.reload();
            },
            error: function(xhr) {
                console.error('Update error:', xhr);
                alert('Error updating profile: ' + (xhr.responseJSON?.error || 'Unknown error'));
                submitBtn.prop('disabled', false).text('Save Changes');
            }
        });
    });
});
</script>

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Profile History</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="history_profile_info" class="mb-3">
                    <h6>Profile: <span id="history_customer_name"></span> (ID: <span id="history_profile_id"></span>)</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Date & Time</th>
                                <th>Updated By</th>
                                <th>Status</th>
                                <th>Follow-up Date</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody id="history_table_body">
                            <tr>
                                <td colspan="5" class="text-center">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
function viewHistory(profileId) {
    console.log('View history for profile:', profileId);
    
    $('#historyModal').modal('show');
    $('#history_table_body').html('<tr><td colspan="5" class="text-center">Loading...</td></tr>');
    
    $.ajax({
        url: '/profiles/' + profileId + '/history',
        type: 'GET',
        success: function(response) {
            console.log('History loaded:', response);
            
            $('#history_customer_name').text(response.profile.customer_name || response.profile.name || 'N/A');
            $('#history_profile_id').text(response.profile.imid || response.profile.id);
            
            if (response.history && response.history.length > 0) {
                let html = '';
                response.history.forEach(function(record) {
                    html += '<tr>';
                    html += '<td>' + (record.created_at || 'N/A') + '</td>';
                    html += '<td>' + (record.executive_name || record.updated_by || 'N/A') + '</td>';
                    html += '<td><span class="badge badge-primary">' + (record.status || 'N/A') + '</span></td>';
                    html += '<td>' + (record.follow_up_date || '-') + '</td>';
                    html += '<td>' + (record.remarks || '-') + '</td>';
                    html += '</tr>';
                });
                $('#history_table_body').html(html);
            } else {
                $('#history_table_body').html('<tr><td colspan="5" class="text-center text-muted">No history records found</td></tr>');
            }
        },
        error: function(xhr) {
            console.error('Error loading history:', xhr);
            $('#history_table_body').html('<tr><td colspan="5" class="text-center text-danger">Error loading history</td></tr>');
        }
    });
}
</script>
</body>
</html>