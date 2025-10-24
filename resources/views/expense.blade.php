@extends('layouts.app')

@section('content')
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

    /* Dashboard Cards */
    .dashboard-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .dashboard-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.25);
    }

    .card-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        font-weight: bold;
        color: white;
    }

    .card-icon.today {
        background: linear-gradient(135deg, #FF6B6B, #C92A2A);
    }

    .card-icon.week {
        background: linear-gradient(135deg, #4CAF50, #45a049);
    }

    .card-icon.month {
        background: linear-gradient(135deg, #2196F3, #1976D2);
    }

    .card-icon.total {
        background: linear-gradient(135deg, #ac0742, #9d1955);
    }

    .card-content h3 {
        font-size: 32px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .card-content p {
        color: #666;
        font-weight: 500;
        font-size: 16px;
    }

    /* Expense Section */
    .expense-section {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #e0e0e0;
    }

    .section-header-left {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .section-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #FF6B6B, #C92A2A);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 600;
        color: #2c3e50;
    }

    /* Table styling */
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border: 1px solid #d0e8f2;
        border-radius: 8px;
        overflow: hidden;
    }

    table th, table td {
        padding: 12px;
        text-align: center;
        vertical-align: middle;
        border-bottom: 1px solid #e0e0e0;
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
    }

    table tfoot tr {
        background: linear-gradient(135deg, #ac0742, #9d1955);
        color: white;
        font-weight: 700;
        font-size: 16px;
    }

    table tfoot td {
        padding: 18px 12px;
        border-bottom: none;
    }

    /* Editable input fields in table */
    .editable-input {
        width: 100%;
        padding: 8px;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        font-size: 14px;
        background: white;
        transition: all 0.3s ease;
    }

    .editable-input:focus {
        outline: none;
        border-color: #ac0742;
        box-shadow: 0 0 0 2px rgba(172, 7, 66, 0.1);
    }

    .editable-input.date-input {
        min-width: 130px;
    }

    .editable-input.amount-input {
        min-width: 100px;
        text-align: right;
        font-weight: 600;
        color: #ac0742;
    }

    .editable-textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        font-size: 14px;
        background: white;
        resize: vertical;
        min-height: 60px;
        font-family: inherit;
    }

    .editable-textarea:focus {
        outline: none;
        border-color: #ac0742;
        box-shadow: 0 0 0 2px rgba(172, 7, 66, 0.1);
    }

    .btn-save {
        background: linear-gradient(135deg, #4CAF50, #45a049);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s ease;
        margin-right: 5px;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
    }

    .btn-delete {
        background: #f44336;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s ease;
    }

    .btn-delete:hover {
        background: #d32f2f;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(244, 67, 54, 0.4);
    }

    .btn-add-row {
        background: linear-gradient(135deg, #2196F3, #1976D2);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 25px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        margin-top: 15px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(33, 150, 243, 0.4);
    }

    .btn-add-row:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(33, 150, 243, 0.6);
    }

    /* Filters */
    .filters-container {
        margin-bottom: 16px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: flex-end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        font-size: 12px;
        color: #666;
        margin-bottom: 4px;
        font-weight: 500;
    }

    .filter-group input,
    .filter-group select {
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid #ddd;
        font-size: 14px;
    }

    .filter-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-filter {
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .btn-filter.primary {
        background: #ac0742;
        color: #fff;
    }

    .btn-filter.primary:hover {
        background: #9d1955;
    }

    .btn-filter.secondary {
        background: #eee;
        color: #333;
        text-decoration: none;
        display: inline-block;
    }

    .btn-filter.secondary:hover {
        background: #ddd;
    }

    .success-message {
        background: #d4edda;
        color: #155724;
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid #28a745;
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .main-header {
            flex-direction: column;
            gap: 15px;
            padding: 20px;
        }

        .header-nav {
            flex-wrap: wrap;
            justify-content: center;
        }

        .dashboard-cards {
            grid-template-columns: 1fr;
        }

        .main-content {
            padding: 20px;
        }

        table {
            font-size: 12px;
        }

        table th, table td {
            padding: 8px 6px;
        }

        .section-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }

        .filters-container {
            flex-direction: column;
            align-items: stretch;
        }

        .editable-input {
            font-size: 12px;
            padding: 6px;
        }
    }
</style>

<!-- Main Dashboard Header -->
<header class="main-header">
    <a href="#" class="header-brand">INA</a>
    
    <nav>
        <ul class="header-nav">
            <li><a href="{{ route('dashboard') }}" class="nav-link">Home</a></li>
            <li><a href="{{ route('profile.hellow') }}" class="nav-link">Profiles</a></li>
            <li><a href="#" class="nav-link">Sales <span class="dropdown-arrow">▼</span></a></li>
            <li><a href="#" class="nav-link">HelpLine</a></li>
            <li><a href="{{ route('fresh.data') }}" class="nav-link">Fresh Data <span class="dropdown-arrow">▼</span></a></li>
            <li><a href="#" class="nav-link">abc</a></li>
            <li><a href="{{ route('services.page') }}" class="nav-link">Services <span class="dropdown-arrow">▼</span></a></li>
            <li><a href="{{ route('addsale.page') }}" class="nav-link">Accounts <span class="dropdown-arrow">▼</span></a></li>
            <li><a href="{{ route('expense.page') }}" class="nav-link active">Expenses</a></li>
        </ul>
    </nav>
    
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
    </form>
    <button class="logout-btn" id="logout-btn">Logout</button>
</header>

<!-- Main Content Area -->
<main class="main-content">
    <h1 class="page-title">Expense Dashboard</h1>

    <!-- Dashboard Cards -->
    <div class="dashboard-cards">
        <div class="dashboard-card">
            <div class="card-icon today">📅</div>
            <div class="card-content">
                <h3>₹{{ number_format($todayExpense ?? 0, 2) }}</h3>
                <p>Today's Expenses</p>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-icon week">📊</div>
            <div class="card-content">
                <h3>₹{{ number_format($weekExpense ?? 0, 2) }}</h3>
                <p>This Week</p>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-icon month">📈</div>
            <div class="card-content">
                <h3>₹{{ number_format($monthExpense ?? 0, 2) }}</h3>
                <p>This Month</p>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-icon total">💰</div>
            <div class="card-content">
                <h3>₹{{ number_format($totalExpense ?? 0, 2) }}</h3>
                <p>Total Expenses</p>
            </div>
        </div>
    </div>

    <!-- Expense Section -->
    <div class="expense-section">
        <div class="section-header">
            <div class="section-header-left">
                <div class="section-icon">💸</div>
                <h2 class="section-title">Expense Records</h2>
            </div>
        </div>

        @if(session('success'))
        <div class="success-message">
            ✓ {{ session('success') }}
        </div>
        @endif

        <!-- Filters -->
        <form method="GET" action="{{ route('expense.page') }}" class="filters-container">
            <div class="filter-group">
                <label>Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}">
            </div>

            <div class="filter-group">
                <label>Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}">
            </div>

            <div class="filter-group">
                <label>Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search description...">
            </div>

            <div class="filter-buttons">
                <button type="submit" class="btn-filter primary">Filter</button>
                <a href="{{ route('expense.page') }}" class="btn-filter secondary">Reset</a>
            </div>
        </form>

        <form id="expenses-form">
            <table>
                <thead>
                    <tr>
                        <th style="width: 60px;">Sl No</th>
                        <th style="width: 140px;">Date</th>
                        <th style="width: 200px;">Description</th>
                        <th>Notes</th>
                        <th style="width: 120px;">Amount</th>
                        <th style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="expense-tbody">
                    @forelse($expenses ?? [] as $index => $expense)
                    <tr data-expense-id="{{ $expense->id }}">
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <input type="date" 
                                   class="editable-input date-input" 
                                   name="date_{{ $expense->id }}"
                                   value="{{ $expense->date }}" 
                                   data-field="date">
                        </td>
                        <td>
                            <input type="text" 
                                   class="editable-input" 
                                   name="description_{{ $expense->id }}"
                                   value="{{ $expense->description }}" 
                                   placeholder="Enter description"
                                   data-field="description">
                        </td>
                        <td>
                            <textarea class="editable-textarea" 
                                      name="notes_{{ $expense->id }}"
                                      placeholder="Enter notes..."
                                      data-field="notes">{{ $expense->notes }}</textarea>
                        </td>
                        <td>
                            <input type="number" 
                                   class="editable-input amount-input" 
                                   name="amount_{{ $expense->id }}"
                                   value="{{ $expense->amount }}" 
                                   step="0.01"
                                   placeholder="0.00"
                                   data-field="amount"
                                   onchange="updateTotal()">
                        </td>
                        <td>
                            <button type="button" class="btn-save" onclick="saveExpense({{ $expense->id }})">Save</button>
                            <button type="button" class="btn-delete" onclick="deleteExpense({{ $expense->id }})">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <!-- Empty state will be handled by new row addition -->
                    @endforelse
                    
                    <!-- New Empty Row Template -->
                    <tr id="new-row-template" style="display: none;">
                        <td class="row-number">-</td>
                        <td>
                            <input type="date" 
                                   class="editable-input date-input" 
                                   value="{{ date('Y-m-d') }}"
                                   data-field="date">
                        </td>
                        <td>
                            <input type="text" 
                                   class="editable-input" 
                                   placeholder="Enter description"
                                   data-field="description">
                        </td>
                        <td>
                            <textarea class="editable-textarea" 
                                      placeholder="Enter notes..."
                                      data-field="notes"></textarea>
                        </td>
                        <td>
                            <input type="number" 
                                   class="editable-input amount-input" 
                                   value="0.00" 
                                   step="0.01"
                                   placeholder="0.00"
                                   data-field="amount"
                                   onchange="updateTotal()">
                        </td>
                        <td>
                            <button type="button" class="btn-save" onclick="saveNewExpense(this)">Save</button>
                            <button type="button" class="btn-delete" onclick="removeNewRow(this)">Remove</button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" style="text-align: right; font-size: 18px;">TOTAL:</td>
                        <td style="font-size: 20px;" id="total-amount">₹{{ number_format($totalExpense ?? 0, 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </form>

        <button type="button" class="btn-add-row" onclick="addNewRow()">+ Add New Row</button>

        <!-- Pagination -->
        @if(isset($expenses) && $expenses->hasPages())
        <div style="margin-top: 16px; display: flex; justify-content: flex-end;">
            {{ $expenses->links() }}
        </div>
        @endif
    </div>
</main>

<script>
    // CSRF Token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

    // Update total amount
    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.amount-input').forEach(input => {
            const value = parseFloat(input.value) || 0;
            total += value;
        });
        document.getElementById('total-amount').textContent = '₹' + total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    // Add new row
    function addNewRow() {
        const template = document.getElementById('new-row-template');
        const newRow = template.cloneNode(true);
        newRow.id = '';
        newRow.style.display = '';
        newRow.removeAttribute('data-expense-id');
        
        // Update row number
        const tbody = document.getElementById('expense-tbody');
        const rowCount = tbody.querySelectorAll('tr:not(#new-row-template)').length + 1;
        newRow.querySelector('.row-number').textContent = rowCount;
        
        tbody.appendChild(newRow);
    }

    // Remove new unsaved row
    function removeNewRow(button) {
        if (confirm('Remove this row?')) {
            button.closest('tr').remove();
            updateRowNumbers();
            updateTotal();
        }
    }

    // Update row numbers after deletion
    function updateRowNumbers() {
        const rows = document.querySelectorAll('#expense-tbody tr:not(#new-row-template)');
        rows.forEach((row, index) => {
            const slNoCell = row.querySelector('td:first-child');
            if (slNoCell && !slNoCell.classList.contains('row-number')) {
                slNoCell.textContent = index + 1;
            } else if (slNoCell) {
                slNoCell.textContent = index + 1;
            }
        });
    }

    // Save existing expense
    async function saveExpense(expenseId) {
        const row = document.querySelector(`tr[data-expense-id="${expenseId}"]`);
        const data = {
            date: row.querySelector('[data-field="date"]').value,
            description: row.querySelector('[data-field="description"]').value,
            notes: row.querySelector('[data-field="notes"]').value,
            amount: row.querySelector('[data-field="amount"]').value,
            _token: csrfToken,
            _method: 'PUT'
        };

        try {
            const response = await fetch(`/expense/${expenseId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                showMessage('Expense updated successfully!', 'success');
                updateTotal();
            } else {
                showMessage('Error updating expense', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showMessage('Error updating expense', 'error');
        }
    }

    // Save new expense
    async function saveNewExpense(button) {
        const row = button.closest('tr');
        const data = {
            date: row.querySelector('[data-field="date"]').value,
            description: row.querySelector('[data-field="description"]').value,
            notes: row.querySelector('[data-field="notes"]').value,
            amount: row.querySelector('[data-field="amount"]').value,
            _token: csrfToken
        };

        // Validation
        if (!data.description || !data.amount) {
            alert('Please enter description and amount');
            return;
        }

        try {
            const response = await fetch('{{ route("expense.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok) {
                showMessage('Expense added successfully!', 'success');
                // Reload page to show new expense
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showMessage(result.message || 'Error adding expense', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showMessage('Error adding expense', 'error');
        }
    }

    // Delete expense
    async function deleteExpense(expenseId) {
        if (!confirm('Are you sure you want to delete this expense?')) {
            return;
        }

        try {
            const response = await fetch(`/expense/${expenseId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            if (response.ok) {
                showMessage('Expense deleted successfully!', 'success');
                document.querySelector(`tr[data-expense-id="${expenseId}"]`).remove();
                updateRowNumbers();
                updateTotal();
            } else {
                showMessage('Error deleting expense', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showMessage('Error deleting expense', 'error');
        }
    }

    // Show message
    function showMessage(message, type) {
        const existingMsg = document.querySelector('.success-message, .error-message');
        if (existingMsg) existingMsg.remove();

        const msgDiv = document.createElement('div');
        msgDiv.className = type === 'success' ? 'success-message' : 'error-message';
        msgDiv.innerHTML = `${type === 'success' ? '✓' : '⚠'} ${message}`;
        
        const section = document.querySelector('.expense-section');
        section.insertBefore(msgDiv, section.querySelector('.filters-container'));

        setTimeout(() => msgDiv.remove(), 3000);
    }

    // Logout functionality
    document.getElementById('logout-btn')?.addEventListener('click', function(e) {
        e.preventDefault();
        if(confirm('Are you sure you want to logout?')) {
            document.getElementById('logout-form').submit();
        }
    });

    // Initialize total on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateTotal();
    });
</script>

@endsection