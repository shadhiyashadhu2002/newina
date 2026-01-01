@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
        min-height: 100vh;
    }
    .employee-sheet-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
    }


    

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #eee;
    }

    .page-header h1 {
        margin: 0;
        color: #fff;
        font-size: 28px;
        text-shadow: 0 2px 6px rgba(0,0,0,0.4);
    }

    .btn-add-employee {
        padding: 12px 24px;
        background: linear-gradient(135deg, #ac0742, #9d1955);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-add-employee:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(172, 7, 66, 0.3);
    }

    .table-wrapper {
        overflow-x: auto;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
    }

    .employee-table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        table-layout: auto;
    }

    .employee-table thead {
        background: linear-gradient(135deg, #ac0742, #9d1955);
        color: white;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .employee-table thead th {
        padding: 15px 12px;
        text-align: left;
        font-weight: 600;
        font-size: 13px;
        white-space: nowrap;
        border-right: 1px solid rgba(255,255,255,0.2);
    }

    .employee-table thead th:last-child {
        border-right: none;
    }

    .employee-table tbody tr {
        border-bottom: 1px solid #e0e0e0;
        transition: background 0.2s ease;
    }

    .employee-table tbody tr:hover {
        background: #f9f9f9;
    }

    .employee-table tbody td {
        padding: 12px;
        border-right: 1px solid #e0e0e0;
    }

    .employee-table tbody td:last-child {
        border-right: none;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
        white-space: nowrap;
    }

    .btn-edit, .btn-delete, .btn-save, .btn-cancel {
        padding: 6px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-edit {
        background: #2196F3;
        color: white;
    }

    .btn-edit:hover {
        background: #0b7dda;
    }

    .btn-delete {
        background: #f44336;
        color: white;
    }

    .btn-delete:hover {
        background: #da190b;
    }

    .btn-save {
        background: #4CAF50;
        color: white;
    }

    .btn-save:hover {
        background: #45a049;
    }

    .btn-cancel {
        background: #999;
        color: white;
    }

    .btn-cancel:hover {
        background: #777;
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
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: #fefefe;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        width: 90%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from { transform: translateY(-50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #eee;
    }

    .modal-header h2 {
        margin: 0;
        color: #2c3e50;
        font-size: 24px;
    }

    .close-modal {
        background: none;
        border: none;
        font-size: 28px;
        cursor: pointer;
        color: #999;
        transition: color 0.2s ease;
    }

    .close-modal:hover {
        color: #333;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        font-family: inherit;
        box-sizing: border-box;
        transition: border 0.2s ease;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #ac0742;
        box-shadow: 0 0 4px rgba(172, 7, 66, 0.2);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .modal-footer {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 25px;
        padding-top: 15px;
        border-top: 1px solid #eee;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
        border-left: 4px solid;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border-left-color: #28a745;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border-left-color: #f5c6cb;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #999;
    }

    .empty-state p {
        margin: 10px 0;
        font-size: 16px;
    }

    /* Card Layout Styles */
    .cards-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .employee-card {
        background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
        color: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .employee-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(172, 7, 66, 0.15);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid rgba(255, 255, 255, 0.3);
    }

    .card-title {
        font-size: 18px;
        font-weight: 700;
        margin: 0;
    }

    .card-code {
        background: rgba(255, 255, 255, 0.25);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .card-field {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .card-label {
        font-weight: 600;
        opacity: 0.9;
    }

    .card-value {
        text-align: right;
        opacity: 1;
    }

    .card-footer {
        display: flex;
        gap: 10px;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 2px solid rgba(255, 255, 255, 0.3);
    }

    .card-btn {
        flex: 1;
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .card-btn-edit {
        background: #4CAF50;
        color: white;
    }

    .card-btn-edit:hover {
        background: #45a049;
    }

    .card-btn-delete {
        background: #f44336;
        color: white;
    }

    .card-btn-delete:hover {
        background: #da190b;
    }

    .no-employees-message {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        color: #999;
        font-size: 16px;
    }

    /* Filter Section Styles */
    .filter-section {
        background: #f8f9fa;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 25px;
    }

    .filter-title {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .filter-input-group {
        display: flex;
        flex-direction: column;
    }

    .filter-input-group label {
        font-size: 13px;
        font-weight: 600;
        color: #555;
        margin-bottom: 5px;
    }

    .filter-input-group input {
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        transition: border 0.2s ease;
    }

    .filter-input-group input:focus {
        outline: none;
        border-color: #ac0742;
        box-shadow: 0 0 4px rgba(172, 7, 66, 0.2);
    }

    .filter-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .btn-filter-clear {
        padding: 10px 20px;
        background: #999;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-filter-clear:hover {
        background: #777;
    }

    .filter-results {
        font-size: 13px;
        color: #666;
        margin-top: 10px;
    }
</style>

<div class="employee-sheet-container">
    <div class="page-header">
        <h1>Employee Sheet</h1>
        <button class="btn-add-employee" onclick="openAddModal()">+ Add Employee</button>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-error">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
    @endif

    @if($employees->count() > 0)
    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-title">
            🔍 Filter Employees
        </div>
        <div class="filter-grid">
            <div class="filter-input-group">
                <label for="filterCode">Emp Code</label>
                <input type="text" id="filterCode" placeholder="Search by code..." onkeyup="applyFilters()">
            </div>
            <div class="filter-input-group">
                <label for="filterName">Name</label>
                <input type="text" id="filterName" placeholder="Search by name..." onkeyup="applyFilters()">
            </div>
            <div class="filter-input-group">
                <label for="filterContactPerson">Contact Person</label>
                <input type="text" id="filterContactPerson" placeholder="Search by contact..." onkeyup="applyFilters()">
            </div>
            <div class="filter-input-group">
                <label for="filterAadhar">Aadhar Card No</label>
                <input type="text" id="filterAadhar" placeholder="Search by aadhar..." onkeyup="applyFilters()">
            </div>
            <div class="filter-input-group">
                <label for="filterDepartment">Department</label>
                <input type="text" id="filterDepartment" placeholder="Search by department..." onkeyup="applyFilters()">
            </div>
        </div>
        <div class="filter-actions">
            <button class="btn-filter-clear" onclick="clearFilters()">Clear Filters</button>
        </div>
        <div class="filter-results">
            <span id="filterResults">Showing {{ $employees->count() }} employees</span>
        </div>
    </div>

    <div class="cards-container" id="employees-cards-container">
        @foreach($employees as $employee)
        <div class="employee-card" data-id="{{ $employee->id }}" 
             data-code="{{ strtolower($employee->emp_code) }}"
             data-name="{{ strtolower($employee->name) }}"
             data-contact="{{ strtolower($employee->contact_person ?? '') }}"
             data-aadhar="{{ strtolower($employee->aadhar_card_no ?? '') }}"
             data-department="{{ strtolower($employee->department ?? '') }}">
            <div class="card-header">
                <h3 class="card-title">{{ $employee->name }}</h3>
                <span class="card-code">{{ $employee->emp_code }}</span>
            </div>

            <div class="card-field">
                <span class="card-label">Emergency Mobile:</span>
                <span class="card-value">{{ $employee->emergency_mobile ?? '-' }}</span>
            </div>

            <div class="card-field">
                <span class="card-label">Email:</span>
                <span class="card-value">{{ $employee->email ?? '-' }}</span>
            </div>

            <div class="card-field">
                <span class="card-label">Contact Person:</span>
                <span class="card-value">{{ $employee->contact_person ?? '-' }}</span>
            </div>

            <div class="card-field">
                <span class="card-label">Aadhar Card:</span>
                <span class="card-value">{{ $employee->aadhar_card_no ?? '-' }}</span>
            </div>

            <div class="card-field">
                <span class="card-label">Address:</span>
                <span class="card-value">{{ $employee->address ?? '-' }}</span>
            </div>

            <div class="card-field">
                <span class="card-label">Date Of Joining:</span>
                <span class="card-value">{{ $employee->date_of_joining ? $employee->date_of_joining->format('d-m-Y') : '-' }}</span>
            </div>

            <div class="card-field">
                <span class="card-label">Designation:</span>
                <span class="card-value">{{ $employee->designation ?? '-' }}</span>
            </div>

            <div class="card-field">
                <span class="card-label">Department:</span>
                <span class="card-value">{{ $employee->department ?? '-' }}</span>
            </div>

            <div class="card-field">
                <span class="card-label">Company:</span>
                <span class="card-value">{{ $employee->company ?? '-' }}</span>
            </div>

            <div class="card-field">
                <span class="card-label">Salary:</span>
                <span class="card-value">{{ $employee->salary ? '₹ ' . number_format($employee->salary, 2) : '-' }}</span>
            </div>

            <div class="card-footer">
                <button class="card-btn card-btn-edit" onclick="openEditModal({{ $employee->id }})">Edit</button>
                <button class="card-btn card-btn-delete" onclick="deleteEmployee({{ $employee->id }})">Delete</button>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="cards-container">
        <div class="no-employees-message">
            📋 No employees found. Click "Add Employee" to get started.
        </div>
    </div>
    @endif
</div>

<!-- Add/Edit Employee Modal -->
<div id="employeeModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Add Employee</h2>
            <button class="close-modal" onclick="closeModal()">&times;</button>
        </div>
        <div id="modalErrors" style="display:none; margin-bottom:10px;"></div>
        <form id="employeeForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="emp_code">Emp Code *</label>
                    <input type="text" id="emp_code" name="emp_code" required>
                </div>
                <div class="form-group">
                    <label for="name">Name *</label>
                    <input type="text" id="name" name="name" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="emergency_mobile">Emergency Mobile</label>
                    <input type="text" id="emergency_mobile" name="emergency_mobile">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="contact_person">Contact Person</label>
                    <input type="text" id="contact_person" name="contact_person">
                </div>
                <div class="form-group">
                    <label for="aadhar_card_no">Aadhar Card NO</label>
                    <input type="text" id="aadhar_card_no" name="aadhar_card_no">
                </div>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" rows="2"></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="date_of_joining">Date Of Joining</label>
                    <input type="date" id="date_of_joining" name="date_of_joining">
                </div>
                <div class="form-group">
                    <label for="designation">Designation</label>
                    <input type="text" id="designation" name="designation">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="department">Department</label>
                    <input type="text" id="department" name="department">
                </div>
                <div class="form-group">
                    <label for="company">Company</label>
                    <input type="text" id="company" name="company">
                </div>
            </div>
            <div class="form-group">
                <label for="salary">Salary</label>
                <input type="number" id="salary" name="salary" step="0.01">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-save" id="employeeSaveButton">Save Employee</button>
            </div>
        </form>
    </div>
</div>

<script>
let currentEmployeeId = null;
// initial employees preloaded from server (latest 5)
const initialEmployees = {!! json_encode($employees->map(function($e){
    return [
        'id' => $e->id,
        'emp_code' => $e->emp_code,
        'name' => $e->name,
        'emergency_mobile' => $e->emergency_mobile,
        'email' => $e->email,
        'contact_person' => $e->contact_person,
        'aadhar_card_no' => $e->aadhar_card_no,
        'address' => $e->address,
        'date_of_joining' => $e->date_of_joining ? $e->date_of_joining->format('d-m-Y') : null,
        'designation' => $e->designation,
        'department' => $e->department,
        'company' => $e->company,
        'salary' => $e->salary
    ];
})); !!};

// Debounce helper to limit server calls
function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

function openAddModal() {
    currentEmployeeId = null;
    document.getElementById('modalTitle').textContent = 'Add Employee';
    document.getElementById('employeeForm').reset();
    document.getElementById('emp_code').disabled = false;
    // clear any previous errors
    const modalErrors = document.getElementById('modalErrors');
    if (modalErrors) { modalErrors.style.display = 'none'; modalErrors.innerHTML = ''; }
    document.querySelectorAll('.form-group .field-error').forEach(el => el.remove());
    document.getElementById('employeeModal').classList.add('show');
}

function openEditModal(id) {
    const card = document.querySelector(`[data-id="${id}"]`);
    if (!card) return;

    currentEmployeeId = id;
    document.getElementById('modalTitle').textContent = 'Edit Employee';
    document.getElementById('emp_code').disabled = true;

    const fields = {};
    fields.emp_code = card.querySelector('.card-code').textContent.trim();
    fields.name = card.querySelector('.card-title').textContent.trim();
    // More robust: collect card-field .card-value nodes sequentially
    const fieldVals = card.querySelectorAll('.card-field .card-value');
    fields.emergency_mobile = fieldVals[0] ? fieldVals[0].textContent.trim() : '';
    fields.email = fieldVals[1] ? fieldVals[1].textContent.trim() : '';
    fields.contact_person = fieldVals[2] ? fieldVals[2].textContent.trim() : '';
    fields.aadhar_card_no = fieldVals[3] ? fieldVals[3].textContent.trim() : '';
    fields.address = fieldVals[4] ? fieldVals[4].textContent.trim() : '';
    fields.date_of_joining = fieldVals[5] ? fieldVals[5].textContent.trim() : '';
    fields.designation = fieldVals[6] ? fieldVals[6].textContent.trim() : '';
    fields.department = fieldVals[7] ? fieldVals[7].textContent.trim() : '';
    fields.company = fieldVals[8] ? fieldVals[8].textContent.trim() : '';
    fields.salary = fieldVals[9] ? fieldVals[9].textContent.trim() : '';

    // Clean up values (remove dashes and rupee signs)
    Object.keys(fields).forEach(key => {
        let value = fields[key];
        if (value === '-') value = '';
        if (key === 'salary') value = value.replace('₹ ', '').replace(/,/g, '');
        if (key === 'date_of_joining' && value !== '') {
            // Convert dd-mm-yyyy to yyyy-mm-dd
            const [d, m, y] = value.split('-');
            value = `${y}-${m}-${d}`;
        }
        document.getElementById(key).value = value;
    });

    document.getElementById('employeeModal').classList.add('show');
    const modalErrors = document.getElementById('modalErrors');
    if (modalErrors) { modalErrors.style.display = 'none'; modalErrors.innerHTML = ''; }
    document.querySelectorAll('.form-group .field-error').forEach(el => el.remove());
}

function closeModal() {
    document.getElementById('employeeModal').classList.remove('show');
    document.getElementById('employeeForm').reset();
    currentEmployeeId = null;
}

document.getElementById('employeeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    submitEmployeeForm();
});

function submitEmployeeForm() {
    const saveBtn = document.getElementById('employeeSaveButton');
    saveBtn.disabled = true;
    saveBtn.textContent = 'Saving...';
    // Clear inline errors
    const modalErrors = document.getElementById('modalErrors');
    modalErrors.style.display = 'none';
    modalErrors.innerHTML = '';
    document.querySelectorAll('.form-group .field-error').forEach(el => el.remove());

    const formData = new FormData(document.getElementById('employeeForm'));
    const url = currentEmployeeId ? `/employee-sheet/${currentEmployeeId}` : '/employee-sheet';
    const method = currentEmployeeId ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: formData
    })
    .then(response => {
        const status = response.status;
        return response.json().then(data => ({ status, data }));
    })
    .then(({ status, data }) => {
        if (status === 422) {
            // validation errors
            const errors = data.errors || {};
            const errorList = Object.keys(errors).map(k => '<div>' + errors[k].join(', ') + '</div>').join('');
            modalErrors.innerHTML = `<div class="alert alert-error">Validation error:<div>${errorList}</div></div>`;
            modalErrors.style.display = '';
            // Also show under respective fields
            Object.keys(errors).forEach(field => {
                const input = document.querySelector(`[name="${field}"]`);
                if (input) {
                    const e = document.createElement('div');
                    e.className = 'field-error';
                    e.style.color = 'red';
                    e.style.fontSize = '13px';
                    e.textContent = errors[field].join(', ');
                    input.parentNode.appendChild(e);
                }
            });
        } else if (status >= 400) {
            showAlert(data.message || 'Failed to save employee', 'error');
            console.error('Save failed:', data);
        } else if (data && data.success) {
            showAlert(currentEmployeeId ? 'Employee updated successfully!' : 'Employee added successfully!', 'success');
            closeModal();
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert(data.message || 'Failed to save employee', 'error');
            console.error('Save failed:', data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred while saving', 'error');
    })
    .finally(() => {
        saveBtn.disabled = false;
        saveBtn.textContent = 'Save Employee';
    });
}

function deleteEmployee(id) {
    if (!confirm('Are you sure you want to delete this employee?')) return;

    fetch(`/employee-sheet/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Employee deleted successfully!', 'success');
            const card = document.querySelector(`[data-id="${id}"]`);
            card.remove();
            // Check if container is now empty
            const container = document.getElementById('employees-cards-container');
            if (container && container.children.length === 0) {
                location.reload();
            }
        } else {
            showAlert(data.message || 'Failed to delete employee', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred', 'error');
    });
}

function showAlert(message, type) {
    // Remove existing identical alert messages to avoid duplicates
    document.querySelectorAll(`.employee-sheet-container .alert.alert-${type}`).forEach(el => {
        if (el.textContent === message) el.remove();
    });
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    document.querySelector('.employee-sheet-container').insertBefore(alertDiv, document.querySelector('.page-header').nextSibling);
    setTimeout(() => alertDiv.remove(), 5000);
}

// Close modal when clicking outside of it
window.addEventListener('click', function(event) {
    const modal = document.getElementById('employeeModal');
    if (event.target === modal) {
        closeModal();
    }
});

// Filter functionality: use server-side search and render results
const applyFilters = debounce(function() {
    const filterCode = document.getElementById('filterCode').value.trim();
    const filterName = document.getElementById('filterName').value.trim();
    const filterContact = document.getElementById('filterContactPerson').value.trim();
    const filterAadhar = document.getElementById('filterAadhar').value.trim();
    const filterDepartment = document.getElementById('filterDepartment').value.trim();

    // If all filters are empty, restore initial employees
    if (!filterCode && !filterName && !filterContact && !filterAadhar && !filterDepartment) {
        renderEmployees(initialEmployees);
        document.getElementById('filterResults').textContent = `Showing ${initialEmployees.length} employees`;
        return;
    }

    const params = new URLSearchParams({
        code: filterCode,
        name: filterName,
        contact: filterContact,
        aadhar: filterAadhar,
        department: filterDepartment
    });

    fetch(`/employee-sheet/search?${params.toString()}`, { headers: { 'Accept': 'application/json' } })
    .then(res => res.json())
    .then(data => {
        if (data && data.success) {
            renderEmployees(data.employees);
            const count = (data.employees || []).length;
            if (count === 0) {
                document.getElementById('filterResults').textContent = '⚠️ No employees match the filters';
            } else {
                document.getElementById('filterResults').textContent = `Showing ${count} employee${count > 1 ? 's' : ''}`;
            }
        } else {
            showAlert('Could not fetch filtered employees', 'error');
        }
    })
    .catch(err => {
        console.error('Error fetching filtered employees', err);
        showAlert('Error while searching', 'error');
    });
}, 300);

function clearFilters() {
    document.getElementById('filterCode').value = '';
    document.getElementById('filterName').value = '';
    document.getElementById('filterContactPerson').value = '';
    document.getElementById('filterAadhar').value = '';
    document.getElementById('filterDepartment').value = '';

    // Restore initial employees (latest 5)
    renderEmployees(initialEmployees);
    document.getElementById('filterResults').textContent = `Showing ${initialEmployees.length} employees`;
}

// Render employees array into cards container. Each item should match server-returned structure.
function renderEmployees(employees) {
    const container = document.getElementById('employees-cards-container');
    if (!container) return;
    if (!employees || employees.length === 0) {
        container.innerHTML = `
            <div class="cards-container">
                <div class="no-employees-message">📋 No employees found.</div>
            </div>`;
        return;
    }

    const html = employees.map(emp => {
        const dateOfJoining = emp.date_of_joining ? emp.date_of_joining : '-';
        const emergency = emp.emergency_mobile || '-';
        const email = emp.email || '-';
        const contactPerson = emp.contact_person || '-';
        const aadhar = emp.aadhar_card_no || '-';
        const address = emp.address || '-';
        const designation = emp.designation || '-';
        const department = emp.department || '-';
        const company = emp.company || '-';
        const salary = emp.salary ? `₹ ${Number(emp.salary).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}` : '-';

        return `
            <div class="employee-card" data-id="${emp.id}" 
                 data-code="${(emp.emp_code||'').toLowerCase()}"
                 data-name="${(emp.name||'').toLowerCase()}"
                 data-contact="${(emp.contact_person||'').toLowerCase()}"
                 data-aadhar="${(emp.aadhar_card_no||'').toLowerCase()}"
                 data-department="${(emp.department||'').toLowerCase()}">
                <div class="card-header">
                    <h3 class="card-title">${emp.name}</h3>
                    <span class="card-code">${emp.emp_code}</span>
                </div>

                <div class="card-field"><span class="card-label">Emergency Mobile:</span><span class="card-value">${emergency}</span></div>
                <div class="card-field"><span class="card-label">Email:</span><span class="card-value">${email}</span></div>
                <div class="card-field"><span class="card-label">Contact Person:</span><span class="card-value">${contactPerson}</span></div>
                <div class="card-field"><span class="card-label">Aadhar Card:</span><span class="card-value">${aadhar}</span></div>
                <div class="card-field"><span class="card-label">Address:</span><span class="card-value">${address}</span></div>
                <div class="card-field"><span class="card-label">Date Of Joining:</span><span class="card-value">${dateOfJoining}</span></div>
                <div class="card-field"><span class="card-label">Designation:</span><span class="card-value">${designation}</span></div>
                <div class="card-field"><span class="card-label">Department:</span><span class="card-value">${department}</span></div>
                <div class="card-field"><span class="card-label">Company:</span><span class="card-value">${company}</span></div>
                <div class="card-field"><span class="card-label">Salary:</span><span class="card-value">${salary}</span></div>
                <div class="card-footer">
                    <button class="card-btn card-btn-edit" onclick="openEditModal(${emp.id})">Edit</button>
                    <button class="card-btn card-btn-delete" onclick="deleteEmployee(${emp.id})">Delete</button>
                </div>
            </div>`;
    }).join('');

    container.innerHTML = html;
}

// Ensure initial render matches our dynamic templates
document.addEventListener('DOMContentLoaded', function() {
    renderEmployees(initialEmployees);
});

</script>

@endsection
