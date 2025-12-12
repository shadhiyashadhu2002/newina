@extends('layouts.app')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
        min-height: 100vh;
    }

    .new-profiles-container {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        min-height: 100vh;
        padding: 30px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .page-title {
        color: white;
        font-size: 28px;
        font-weight: 600;
    }

    .add-profile-btn {
        background: linear-gradient(135deg, #4CAF50, #45a049);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
    }

    .add-profile-btn:hover {
        background: linear-gradient(135deg, #45a049, #3d8b40);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
        text-decoration: none;
        color: white;
    }

    .data-table-section {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .table-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background: #f8f9fa;
        border-bottom: 2px solid #e0e0e0;
    }

    .show-entries {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        color: #333;
    }

    .show-entries select {
        padding: 8px 12px;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        font-size: 14px;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        background: #f8f9fa;
        padding: 15px;
        text-align: left;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #e0e0e0;
    }

    .data-table td {
        padding: 15px;
        border-bottom: 1px solid #e0e0e0;
        color: #333;
    }

    .data-table tr:hover {
        background: rgba(172, 7, 66, 0.05);
    }

    .status-badge {
        background: #FF9800;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }

    .status-badge.blank {
        background: #e0e0e0;
        color: #666;
    }

    .action-btn {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        margin-right: 5px;
        transition: background 0.3s;
        font-weight: 500;
    }

    .action-btn.update {
        background: #4CAF50;
        color: white;
    }

    .action-btn.update:hover {
        background: #45a049;
    }

    .action-btn.history {
        background: #ac0742;
        color: white;
    }

    .action-btn.history:hover {
        background: #9d1955;
    }

    .pagination-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background: #f8f9fa;
        border-top: 2px solid #e0e0e0;
    }

    .pagination-text {
        color: #333;
        font-size: 14px;
    }

    .pagination-buttons {
        display: flex;
        gap: 8px;
    }

    .pagination-btn {
        background: #4CAF50;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.3s;
    }

    .pagination-btn:hover {
        background: #45a049;
        text-decoration: none;
        color: white;
    }

    .back-btn {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 10px 20px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
    }

    .back-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        text-decoration: none;
        color: white;
    }

    .no-data {
        padding: 40px;
        text-align: center;
        color: #666;
        font-size: 16px;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 100000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(5px);
    }

    .modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        padding: 30px;
        border-radius: 15px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        animation: slideDown 0.3s ease;
        max-height: 90vh;
        overflow-y: auto;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .modal-title {
        font-size: 22px;
        font-weight: 600;
        color: #333;
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 28px;
        cursor: pointer;
        color: #999;
        line-height: 1;
        transition: color 0.3s;
    }

    .close-btn:hover {
        color: #333;
    }

    .modal-body {
        margin-bottom: 0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #333;
        font-weight: 500;
        font-size: 14px;
    }

    .form-input, .form-select {
        width: 100%;
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: #ac0742;
    }

    .form-input:read-only {
        background: #f8f9fa;
        color: #666;
        cursor: not-allowed;
    }

    .modal-footer {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 2px solid #f0f0f0;
    }

    .cancel-btn, .submit-btn {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.3s;
    }

    .cancel-btn {
        background: #f0f0f0;
        color: #333;
    }

    .cancel-btn:hover {
        background: #e0e0e0;
    }

    .submit-btn {
        background: linear-gradient(135deg, #4CAF50, #45a049);
        color: white;
    }

    .submit-btn:hover {
        background: linear-gradient(135deg, #45a049, #3d8b40);
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
    }
</style>

<div class="new-profiles-container">
    <div class="page-header">
        <div>
            <a href="{{ route('dashboard') }}" class="back-btn">← Back to Dashboard</a>
        </div>
        <h1 class="page-title">New Profiles</h1>
        <a href="{{ route('profile.addnew') }}" class="add-profile-btn">
            <span style="font-size: 18px;">+</span>
            Add New Profile
        </a>
    </div>

    <div class="data-table-section">
        <div class="table-controls">
            <div class="show-entries">
                <label for="per_page">Show</label>
                <form method="get" style="margin: 0; display: inline;">
                    <select name="per_page" id="per_page" onchange="this.form.submit()">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </form>
                <span>entries</span>
            </div>
        </div>

        @if($profiles->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th>Profile ID</th>
                    <th>Name</th>
                    <th>Mobile Number</th>
                    <th>Assigned Date</th>
                    <th>Follow-up Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($profiles as $profile)
                <tr>
                    <td>{{ $profile->code ?? '-' }}</td>
                    <td>{{ $profile->first_name ?? '-' }}</td>
                    <td>{{ $profile->phone ?? '-' }}</td>
                    <td>{{ $profile->assigned_date ? \Carbon\Carbon::parse($profile->assigned_date)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $profile->followup_date ? \Carbon\Carbon::parse($profile->followup_date)->format('d-m-Y') : '-' }}</td>
                    <td>
                        @if(empty($profile->status))
                            <span class="status-badge blank">-</span>
                        @else
                            <span class="status-badge">{{ $profile->status }}</span>
                        @endif
                    </td>
                    <td>
                        <button class="action-btn update" onclick="openUpdateModal({{ json_encode($profile) }})">Update</button>
                        <button class="action-btn history" onclick="viewHistory({{ $profile->id }})">History</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination-info">
            <div class="pagination-text">
                Showing {{ $profiles->firstItem() ?? 0 }} to {{ $profiles->lastItem() ?? 0 }} of {{ $profiles->total() }} entries
            </div>
            <div class="pagination-buttons">
                @if(!$profiles->onFirstPage())
                    <a href="{{ $profiles->previousPageUrl() }}" class="pagination-btn">Previous</a>
                @endif
                @if($profiles->hasMorePages())
                    <a href="{{ $profiles->nextPageUrl() }}" class="pagination-btn">Next</a>
                @endif
            </div>
        </div>
        @else
        <div class="no-data">
            <p>No new profiles found. Click "Add New Profile" to create one.</p>
        </div>
        @endif
    </div>
</div>

<!-- Update Profile Modal -->
<div id="updateModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Update Profile</h2>
            <button class="close-btn" onclick="closeUpdateModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="updateProfileForm" method="POST">
                @csrf
                @method('PUT')
                
                <input type="hidden" id="profile_id" name="profile_id">

                <!-- Status Dropdown -->
                <div class="form-group">
                    <label for="status">Status *</label>
                    <select id="status" name="status" class="form-select" required>
                        <option value="">Select Status</option>
                        <option value="RNR">RNR</option>
                        <option value="CNC">CNC</option>
                        <option value="Pending">Pending</option>
                        <option value="Not Interested">Not Interested</option>
                        <option value="Interested">Interested</option>
                    </select>
                </div>

                <!-- Follow-up Date -->
                <div class="form-group">
                    <label for="followup_date">Follow-up Date *</label>
                    <input type="date" id="followup_date" name="followup_date" class="form-input" required>
                </div>

                <!-- Customer Name (Editable) -->
                <div class="form-group">
                    <label for="customer_name">Customer Name *</label>
                    <input type="text" id="customer_name" name="customer_name" class="form-input" required>
                </div>

                <!-- IMID / Profile ID (Read-only, fetched) -->
                <div class="form-group">
                    <label for="imid">IMID / Profile ID</label>
                    <input type="text" id="imid" class="form-input" readonly>
                </div>

                <!-- Mobile Number (Read-only, fetched) -->
                <div class="form-group">
                    <label for="mobile_number">Mobile Number</label>
                    <input type="text" id="mobile_number" class="form-input" readonly>
                </div>

                <!-- Assigned Date (Read-only, fetched) -->
                <div class="form-group">
                    <label for="assigned_date">Assigned Date</label>
                    <input type="text" id="assigned_date" class="form-input" readonly>
                </div>

                <div class="modal-footer">
                    <button type="button" class="cancel-btn" onclick="closeUpdateModal()">Cancel</button>
                    <button type="submit" class="submit-btn">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openUpdateModal(profile) {
        const modal = document.getElementById('updateModal');
        const form = document.getElementById('updateProfileForm');
        
        // Set form action
        form.action = `/profile/${profile.id}/update-status`;
        
        // Fill hidden profile ID
        document.getElementById('profile_id').value = profile.id;
        
        // Fill editable fields
        document.getElementById('status').value = profile.status || '';
        document.getElementById('followup_date').value = profile.followup_date || '';
        
        // Fill read-only fields (fetched data)
        document.getElementById('customer_name').value = profile.first_name || '-';
        document.getElementById('imid').value = profile.code || '-';
        document.getElementById('mobile_number').value = profile.phone || '-';
        document.getElementById('assigned_date').value = profile.assigned_date ? formatDate(profile.assigned_date) : '-';
        
        modal.classList.add('active');
    }

    function closeUpdateModal() {
        const modal = document.getElementById('updateModal');
        modal.classList.remove('active');
        document.getElementById('updateProfileForm').reset();
    }

    function viewHistory(profileId) {
        alert('View history for profile ID: ' + profileId);
        // You can implement history functionality here
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}-${month}-${year}`;
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('updateModal');
        if (event.target === modal) {
            closeUpdateModal();
        }
    }
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
                        <thead class="thead-dark">
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
                        <thead class="thead-dark">
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
