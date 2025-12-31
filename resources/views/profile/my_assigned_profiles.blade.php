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

    .form-input, .form-select, .form-textarea {
        width: 100%;
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-input:focus, .form-select:focus, .form-textarea:focus {
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

    .success-message {
        background: #c6f6d5;
        color: #22543d;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        border-radius: 8px;
        border-left: 4px solid #48bb78;
    }

    .hidden {
        display: none;
    }

    /* Update Button Styles */
    .action-btn {
        padding: 8px 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .action-btn.update {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .action-btn.update:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: white;
        margin: 5% auto;
        padding: 0;
        border-radius: 10px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 5px 30px rgba(0, 0, 0, 0.3);
    }

    .modal-header {
        padding: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px 10px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        margin: 0;
        font-size: 24px;
    }

    .close-btn {
        background: none;
        border: none;
        color: white;
        font-size: 28px;
        cursor: pointer;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .close-btn:hover {
        opacity: 0.7;
    }

    .modal-body {
        padding: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 80px;
    }

    .modal-footer {
        padding: 20px 30px;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        border-top: 1px solid #eee;
    }

    .cancel-btn,
    .submit-btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .cancel-btn {
        background: #e0e0e0;
        color: #333;
    }

    .cancel-btn:hover {
        background: #d0d0d0;
    }

    .submit-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
</style>

<div class="new-profiles-container">
    <div class="page-header">
        <div>
            <a href="{{ route('dashboard') }}" class="back-btn">← Back to Dashboard</a>
        </div>
        <h1 class="page-title">My Assigned Profiles</h1>
    </div>

    @if(session('success'))
    <div class="success-message">
        {{ session('success') }}
    </div>
    @endif

    <div class="data-table-section">
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body text-center">
                    <h5 class="card-title mb-2">Total Profiles</h5>
                    <h2 class="mb-0">{{ $stats['total'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body text-center">
                    <h5 class="card-title mb-2">Pending</h5>
                    <h2 class="mb-0">{{ $stats['pending'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body text-center">
                    <h5 class="card-title mb-2">Completed</h5>
                    <h2 class="mb-0">{{ $stats['completed'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body text-center">
                    <h5 class="card-title mb-2">Follow-up Today</h5>
                    <h2 class="mb-0">{{ $stats['followup_today'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

        <div class="table-controls">
            <div class="show-entries">
                <label for="per_page">Show</label>
                <form method="get" style="margin: 0; display: inline;">
                    <select name="per_page" id="per_page" onchange="this.form.submit()">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </form>
                <span>entries</span>
            </div>
        </div>

        @if($freshData->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th>NAME</th>
                    <th>MOBILE</th>
                    <th>ASSIGNED DATE</th>
                    <th>FOLLOW-UP DATE</th>
                    <th>STATUS</th>
                    <th>IMID</th>
                    <th>SECONDARY PHONE</th>
                    <th>NEW LEAD</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                @foreach($freshData as $profile)
                @php
                    $assignedDate = '-';
                    if(isset($profile->created_at)) {
                        if(is_object($profile->created_at) && method_exists($profile->created_at, 'format')) {
                            $assignedDate = $profile->created_at->format('d-m-Y');
                        } elseif(is_string($profile->created_at)) {
                            $assignedDate = date('d-m-Y', strtotime($profile->created_at));
                        }
                    }

                    $followUpDate = '-';
                    $followUpDateInput = '';
                    if(isset($profile->follow_up_date) && $profile->follow_up_date) {
                        if(is_object($profile->follow_up_date) && method_exists($profile->follow_up_date, 'format')) {
                            $followUpDate = $profile->follow_up_date->format('d-m-Y');
                            $followUpDateInput = $profile->follow_up_date->format('Y-m-d');
                        } elseif(is_string($profile->follow_up_date)) {
                            $followUpDate = date('d-m-Y', strtotime($profile->follow_up_date));
                            $followUpDateInput = date('Y-m-d', strtotime($profile->follow_up_date));
                        }
                    }
                @endphp
                <tr>
                    <td>{{ $profile->customer_name ?? $profile->name ?? '-' }}</td>
                    <td>{{ $profile->mobile ?? '-' }}</td>
                    <td>{{ $assignedDate }}</td>
                    <td>{{ $followUpDate }}</td>
                    <td>
                        @if(empty($profile->status))
                            <span class="status-badge blank">-</span>
                        @else
                            <span class="status-badge">{{ $profile->status }}</span>
                        @endif
                    </td>
                    <td>{{ $profile->imid ?? "-" }}</td>
                    <td>{{ $profile->secondary_phone ?? "-" }}</td>
                    <td>
                        @if($profile->is_new_lead === "yes")
                            <span class="badge bg-success">Yes</span>
                        @elseif($profile->is_new_lead === "no")
                            <span class="badge bg-secondary">No</span>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <button class="action-btn update" onclick="openUpdateModal({{ $profile->id }}, '{{ $profile->customer_name ?? $profile->name }}', '{{ $followUpDateInput }}', '{{ $profile->status ?? '' }}')">
                            Update
                        </button>
                        <button class="action-btn history" onclick="showHistory({{ $profile->id }})" style="margin-left: 5px;">
                            History
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination-info">
            <div class="pagination-text">
                Showing {{ $freshData->count() }} entries
            </div>
        </div>
        @else
        <div class="no-data">
            <p>No profiles assigned to you yet.</p>
        </div>
        @endif
    </div>
</div>

<!-- History Modal -->
<div id="historyModal" class="modal">
    <div class="modal-content" style="max-width: 1200px;">
        <div class="modal-header">
            <h2 class="modal-title">Profile Update History</h2>
            <button class="close-btn" onclick="closeHistoryModal()">&times;</button>
        </div>
        <div class="modal-body" style="overflow-x: auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Executive</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Assigned Date</th>
                        <th>Follow-up Date</th>
                        <th>IMID</th>
                        <th>Remarks</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="historyTableBody">
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 20px;">Loading...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    

    

    function toggleCreatedFields() {
        const status = document.getElementById('status').value;
        const createdFields = document.getElementById('createdFields');
        const imidField = document.getElementById('imid');

        if (status === 'Created') {
            createdFields.style.display = 'block';
            imidField.required = true;
        } else {
            createdFields.style.display = 'none';
            imidField.required = false;
        }
    }

    

    

    function formatDateTime(datetime) {
        if (!datetime) return 'N/A';
        const date = new Date(datetime);
        return date.toLocaleString('en-IN', {
            year: 'numeric',
            month: 'short',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function formatDate(dateStr) {
        if (!dateStr) return 'N/A';
        const date = new Date(dateStr);
        return date.toLocaleDateString('en-IN', {
            year: 'numeric',
            month: 'short',
            day: '2-digit'
        });
    }

    function getStatusColor(status) {
        const colors = {
            'Created': '#28a745',
            'Interested': '#007bff',
            'Not Interested': '#dc3545',
            'Follow Up': '#ffc107',
            'Pending': '#6c757d',
            'CNC': '#17a2b8',
            'RNR': '#6c757d',
            'Closed': '#6c757d'
        };
        return colors[status] || '#ac0742';
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        const updateModal = document.getElementById('updateModal');
        const historyModal = document.getElementById('historyModal');
        if (event.target === updateModal) {
            closeUpdateModal();
        }
        if (event.target === historyModal) {
            closeHistoryModal();
        }
    }

    // Handle form submission
        const updateForm = document.getElementById("updateProfileForm");
        if (updateForm) {
            updateForm.addEventListener("submit", function(e) {
        e.preventDefault();
        
        console.log("=== FORM SUBMIT TRIGGERED ===");
        
        const formData = new FormData(this);
        const profileId = document.getElementById("profile_id").value;
        
        console.log("Profile ID:", profileId);
        console.log("Form Data:");
        for (let [key, value] of formData.entries()) {
            console.log("  " + key + ":", value);
        }
        fetch('/fresh-data/update-status', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json'
            }
        })
        .then(response => { console.log("Response Status:", response.status); return response.json(); })
        .then(data => {
            console.log("Response Data:", data);
            console.log('Response received:', data);
            if(data.success) {
                console.log('Success! Profile data:', data.profile);
                console.log('Profile ID:', profileId);
                alert('Profile updated successfully!');
                closeUpdateModal();
                
                // Update the table row with new data
                updateTableRow(profileId, data.profile);
            } else {
                alert('Error: ' + (data.message || 'Failed to update profile'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the profile');
        });
    });
        }
    
    // Function to update table row after successful update
    

    // Handle form submission - wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        const updateForm = document.getElementById("updateProfileForm");
        if (updateForm) {
            updateForm.addEventListener("submit", function(e) {
                e.preventDefault();
                
                console.log("=== FORM SUBMIT TRIGGERED ===");
                
                const formData = new FormData(this);
                const profileId = document.getElementById("profile_id").value;
                
                console.log("Profile ID:", profileId);
                console.log("Form Data:");
                for (let [key, value] of formData.entries()) {
                    console.log("  " + key + ":", value);
                }
                
                fetch('/fresh-data/update-status', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    }
                })
                .then(response => { 
                    console.log("Response Status:", response.status); 
                    return response.json(); 
                })
                .then(data => {
                    console.log("Response Data:", data);
                    if(data.success) {
                        console.log('Success! Profile data:', data.profile);
                        alert('Profile updated successfully!');
                        closeUpdateModal();
                        location.reload(); // Reload the page to show updated data
                    } else {
                        alert('Error: ' + (data.message || 'Failed to update profile'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the profile');
                });
            });
        } else {
            console.error('updateProfileForm not found!');
        }
    });
</script>

<!-- Update Profile Modal -->
<div id="updateModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Update Profile</h2>
            <button class="close-btn" onclick="closeUpdateModal()">&times;</button>
        </div>
        <form id="updateProfileForm" method="POST">
            @csrf
            <input type="hidden" name="profile_id" id="profile_id">
            
            <div class="modal-body">
                <div class="form-group">
                    <label>Customer Name</label>
                    <input type="text" name="customer_name" id="customer_name">
                </div>

                <div class="form-group">
                    <label>Follow-up Date</label>
                    <input type="date" name="follow_up_date" id="follow_up_date">
                </div>

                <div class="form-group">
                    <label>Status *</label>
                    <select name="status" id="status" required onchange="toggleCreatedFields()">
                        <option value="">Select Status</option>
                        <option value="Created">Created</option>
                        <option value="Interested">Interested</option>
                        <option value="Not Interested">Not Interested</option>
                        <option value="Call Back">Call Back</option>
                        <option value="Wrong Number">Wrong Number</option>
                        <option value="Not Responding">Not Responding</option>
                        <option value="RNR">RNR</option>
                        <option value="CNC">CNC</option>
                        <option value="Pending">Pending</option>
                        <option value="Converted">Converted</option>
                    </select>
                </div>

                <!-- Fields that show only when "Created" is selected -->
                <div id="createdFields" style="display: none;">
                    <div class="form-group">
                        <label>IMID *</label>
                        <input type="text" name="imid" id="imid" class="form-input">
                    </div>

                    <div class="form-group">
                        <label>Secondary Phone Number</label>
                        <input type="text" name="secondary_phone" id="secondary_phone" class="form-input">
                    </div>

                    <div class="form-group">
                        <label>Is this a new lead?</label>
                        <select name="is_new_lead" id="is_new_lead" class="form-select">
                            <option value="">Select</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Remarks</label>
                    <textarea name="remarks" id="remarks"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="cancel-btn" onclick="closeUpdateModal()">Cancel</button>
                <button type="submit" class="submit-btn">Update Profile</button>
            </div>
        </form>
    </div>
</div>

<script>
// Open Update Modal
function openUpdateModal(profileId, customerName, followUpDate, status) {
    const modal = document.getElementById('updateModal');
    const form = document.getElementById('updateProfileForm');
    
    // Set form action
    form.action = '{{ route("fresh.data.update.status") }}';
    
    // Fill form fields
    document.getElementById('profile_id').value = profileId;
    document.getElementById('customer_name').value = customerName;
    document.getElementById('follow_up_date').value = followUpDate;
    document.getElementById('status').value = status;
    document.getElementById('remarks').value = '';
    
    // Show modal
    modal.style.display = 'block';
}

// Close Update Modal
function closeUpdateModal() {
    document.getElementById('updateModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('updateModal');
    if (event.target === modal) {
        closeUpdateModal();
    }
}
</script>

@endsection