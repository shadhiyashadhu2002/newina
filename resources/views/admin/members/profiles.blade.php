@extends('layouts.admin')

@section('content')
<div class="dashboard-container">
    <!-- Header Section -->
    <div class="dashboard-header">
        <div class="header-left">
            <h1 class="dashboard-title">INA</h1>
            <nav class="main-nav">
                <a href="{{ route('dashboard') }}" class="nav-link">Home</a>
                <a href="{{ route('profiles') }}" class="nav-link active">Profiles</a>
                <div class="nav-dropdown">
                    <a href="#" class="nav-link">Sales ▼</a>
                </div>
                <a href="#" class="nav-link">HelpLine</a>
                <div class="nav-dropdown">
                    <a href="#" class="nav-link">Fresh Data ▼</a>
                </div>
                <a href="#" class="nav-link">abc</a>
                <div class="nav-dropdown">
                    <a href="#" class="nav-link">Services ▼</a>
                </div>
            </nav>
        </div>
        <div class="header-right">
            @if(isset($profileId) && $profileId)
                <span class="profile-id">Profile ID: {{ $profileId }}</span>
            @endif
            <a href="#" class="logout-btn">Logout</a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <!-- Profiles Section -->
    <div class="profiles-section">
        <div class="profiles-header">
            <h1 class="page-title">Profiles</h1>
            <button class="add-profile-btn">
                <span class="add-icon">+</span>
                Add New Profile
            </button>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <button class="tab-btn" data-filter="12-more-days">12 More Days Data</button>
            <button class="tab-btn" data-filter="no-welcome">No Welcome Calls</button>
            <button class="tab-btn" data-filter="post-pone">Post Pone Payment</button>
            <button class="tab-btn" data-filter="not-assigned">Not Assigned</button>
            <button class="tab-btn" data-filter="zero-followup">ZERO Follow-Ups</button>
            <button class="tab-btn" data-filter="followup-today">Followup Today</button>
            <button class="tab-btn" data-filter="followup-due">Followup Due</button>
            <button class="tab-btn active" data-filter="all">All</button>
        </div>

        <!-- Table Controls -->
        <div class="table-controls">
            <div class="show-entries">
                <label>Show</label>
                <select name="per_page" onchange="handlePerPageChange(this.value)">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
                <span>entries</span>
            </div>
            
            <div class="search-box">
                <label>Search:</label>
                <input type="text" id="searchInput" placeholder="Search profiles..." onkeyup="handleSearch()">
            </div>
        </div>

        <!-- Profiles Table -->
        <div class="data-table-section">
            <table class="data-table profiles-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th class="sortable" data-sort="profile_id">
                            Profile ID
                            <span class="sort-arrow">▲</span>
                        </th>
                        <th class="sortable" data-sort="name">Name</th>
                        <th class="sortable" data-sort="reg_date">Reg Date</th>
                        <th class="sortable" data-sort="assign_date">Assign Date</th>
                        <th class="sortable" data-sort="followup_date">Followup Date</th>
                        <th>Assigned To</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($profiles as $profile)
                    <tr>
                        <td><input type="checkbox" class="row-checkbox" value="{{ $profile->id }}"></td>
                        <td class="profile-id-link">
                            <a href="{{ route('profile.view', $profile->id) }}">
                                {{ $profile->code ?? $profile->id ?? 'N/A' }}
                            </a>
                        </td>
                        <td>{{ $profile->name ?? 'No Name' }}</td>
                        <td>{{ $profile->created_at ? $profile->created_at->format('d-M-Y') : 'N/A' }}</td>
                        <td>{{ $profile->assign_date ?? 'Not Assigned' }}</td>
                        <td>{{ $profile->followup_date ?? '-' }}</td>
                        <td>{{ $profile->assigned_to ?? $profile->email ?? 'Not Assigned' }}</td>
                        <td>
                            <span class="status-badge {{ strtolower(str_replace(' ', '-', $profile->status ?? 'active')) }}">
                                {{ $profile->status ?? 'Active' }}
                            </span>
                        </td>
                        <td class="action-buttons">
                            <button class="action-btn edit" title="Edit Profile" onclick="editProfile({{ $profile->id }})">
                                <span class="edit-icon">✏️</span>
                            </button>
                            <button class="action-btn delete" title="Delete Profile" onclick="deleteProfile({{ $profile->id }})">
                                <span class="delete-icon">🗑️</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <!-- No profiles found -->
                    <tr>
                        <td colspan="9" class="no-data">
                            <div class="no-data-message">
                                <h3>No profiles found</h3>
                                <p>There are no member profiles to display at the moment.</p>
                                <button class="add-profile-btn" onclick="addNewProfile()">
                                    <span class="add-icon">+</span>
                                    Add Your First Profile
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Table Footer -->
            <div class="table-footer">
                <div class="table-info">
                    @if(isset($profiles) && method_exists($profiles, 'total'))
                        Showing {{ $profiles->firstItem() ?? 0 }} to {{ $profiles->lastItem() ?? 0 }} of {{ $profiles->total() }} entries
                    @else
                        Showing 0 to 0 of 0 entries
                    @endif
                </div>
                
                @if(isset($profiles) && method_exists($profiles, 'links'))
                    <div class="pagination">
                        {{ $profiles->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Inherit all existing styles from dashboard and add profiles-specific styles */

/* Alert Messages */
.alert {
    padding: 12px 20px;
    margin: 20px 30px;
    border-radius: 8px;
    font-weight: 500;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* No Data Message */
.no-data {
    text-align: center;
    padding: 60px 20px;
}

.no-data-message h3 {
    color: #666;
    margin-bottom: 10px;
}

.no-data-message p {
    color: #999;
    margin-bottom: 20px;
}

/* Profiles Section */
.profiles-section {
    padding: 30px;
}

.profiles-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.page-title {
    color: white;
    font-size: 32px;
    font-weight: bold;
    margin: 0;
}

.add-profile-btn {
    background: linear-gradient(135deg, #4CAF50, #45a049);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
}

.add-profile-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
}

.add-icon {
    font-size: 18px;
    font-weight: bold;
}

/* Filter Tabs */
.filter-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 25px;
    flex-wrap: wrap;
}

.tab-btn {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s;
    backdrop-filter: blur(10px);
}

.tab-btn:hover {
    background: rgba(255, 255, 255, 0.2);
}

.tab-btn.active {
    background: #4CAF50;
    border-color: #4CAF50;
}

/* Table Controls */
.table-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    background: rgba(255, 255, 255, 0.9);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.show-entries {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #333;
}

.show-entries select {
    padding: 6px 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.search-box {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #333;
}

.search-box input {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    width: 200px;
}

.search-box input:focus {
    outline: none;
    border-color: #4CAF50;
}

/* Enhanced Table Styles */
.profiles-table {
    background: white;
}

.profiles-table th {
    background: #f8f9fa;
    position: relative;
    user-select: none;
}

.sortable {
    cursor: pointer;
}

.sortable:hover {
    background: #e9ecef;
}

.sort-arrow {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 12px;
    color: #666;
}

.profile-id-link a {
    color: #4CAF50;
    text-decoration: none;
    font-weight: 500;
}

.profile-id-link a:hover {
    text-decoration: underline;
}

/* Status Badges */
.status-badge {
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 11px;
    font-weight: 500;
    text-transform: capitalize;
}

.status-badge.postpone-payment {
    background: #FF9800;
    color: white;
}

.status-badge.active {
    background: #4CAF50;
    color: white;
}

.status-badge.interest-followup {
    background: #2196F3;
    color: white;
}

.status-badge.pending {
    background: #FFC107;
    color: #333;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
}

.action-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 6px 10px;
    border-radius: 6px;
    transition: all 0.3s;
}

.action-btn.edit {
    background: #4CAF50;
    color: white;
}

.action-btn.edit:hover {
    background: #45a049;
}

.action-btn.delete {
    background: #f44336;
    color: white;
}

.action-btn.delete:hover {
    background: #d32f2f;
}

.edit-icon, .delete-icon {
    font-size: 14px;
}

/* Table Footer */
.table-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

.table-info {
    color: #666;
    font-size: 14px;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .filter-tabs {
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .profiles-header {
        flex-direction: column;
        gap: 20px;
        text-align: center;
    }
    
    .table-controls {
        flex-direction: column;
        gap: 15px;
    }
    
    .filter-tabs {
        justify-content: center;
    }
    
    .search-box input {
        width: 150px;
    }
    
    .data-table-section {
        overflow-x: auto;
    }
    
    .profiles-table {
        min-width: 800px;
    }
    
    .table-footer {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .profiles-section {
        padding: 20px;
    }
    
    .filter-tabs {
        flex-direction: column;
    }
    
    .tab-btn {
        text-align: center;
    }
}
</style>

<script>
// JavaScript for interactive functionality
document.addEventListener('DOMContentLoaded', function() {
    // Select All Checkbox
    const selectAllCheckbox = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    // Individual checkbox handling
    rowCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(rowCheckboxes).every(cb => cb.checked);
            const someChecked = Array.from(rowCheckboxes).some(cb => cb.checked);
            
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = someChecked && !allChecked;
            }
        });
    });
    
    // Tab filtering
    const tabBtns = document.querySelectorAll('.tab-btn');
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all tabs
            tabBtns.forEach(tab => tab.classList.remove('active'));
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Filter logic would go here
            const filter = this.dataset.filter;
            console.log('Filtering by:', filter);
        });
    });
    
    // Table sorting
    const sortableHeaders = document.querySelectorAll('.sortable');
    let currentSort = { column: null, direction: 'asc' };
    
    sortableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const column = this.dataset.sort;
            const arrow = this.querySelector('.sort-arrow');
            
            // Reset all arrows
            sortableHeaders.forEach(h => {
                const a = h.querySelector('.sort-arrow');
                if (a) a.textContent = '▲';
            });
            
            // Set current arrow
            if (currentSort.column === column) {
                currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
            } else {
                currentSort.direction = 'asc';
            }
            
            currentSort.column = column;
            if (arrow) {
                arrow.textContent = currentSort.direction === 'asc' ? '▲' : '▼';
            }
            
            // Sorting logic would go here
            console.log('Sorting by:', column, currentSort.direction);
        });
    });

    // Auto-hide alert messages after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});

// Search functionality
function handleSearch() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const tableRows = document.querySelectorAll('.profiles-table tbody tr');
    
    tableRows.forEach(row => {
        // Skip the "no data" row
        if (row.querySelector('.no-data')) return;
        
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Per page change
function handlePerPageChange(value) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', value);
    window.location = url;
}

// Action functions
function editProfile(profileId) {
    // Implement edit functionality
    console.log('Edit profile:', profileId);
    // You can redirect to edit page or show modal
    // window.location.href = `/admin/profile/${profileId}/edit`;
}

function deleteProfile(profileId) {
    if (confirm('Are you sure you want to delete this profile?')) {
        // Implement delete functionality
        console.log('Delete profile:', profileId);
        // You can make AJAX request or redirect to delete route
        // Or use a form submission
        /*
        fetch(`/admin/profile/${profileId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
        */
    }
}

function addNewProfile() {
    // Redirect to add profile page
    // window.location.href = '/admin/profile/create';
    console.log('Add new profile');
}
</script>
@endsection