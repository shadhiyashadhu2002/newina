<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INA Dashboard - Fresh Data</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
            min-height: 100vh;
        }

     
        .main-header {
            background: linear-gradient(135deg, #4a69bd, #5a4fcf);
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

        .main-content {
            padding: 30px;
        }

        .page-title {
            color: #fff;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 25px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>

<body>
    <header class="main-header">
        <a href="#" class="header-brand">INA</a>
        <nav>
            <ul class="header-nav">
                <li><a href="#" data-page="home">Home</a></li>
                <li><a href="{{ route('profile.hellow') }}" data-page="profiles">Profiles</a></li>
                <li><a href="#" data-page="sales">Sales <span class="dropdown-arrow">▼</span></a></li>
                <li><a href="#" data-page="helpline">HelpLine</a></li>
                <li><a href="{{ url('/fresh-data') }}" class="active" data-page="fresh-data">Fresh Data <span class="dropdown-arrow">▼</span></a></li>
                <li><a href="#" data-page="abc">abc</a></li>
                <li><a href="#" data-page="services">Services <span class="dropdown-arrow">▼</span></a></li>
            </ul>
        </nav>
        <button class="logout-btn">Logout</button>
    </header>
    <main class="main-content">
        <h1 class="page-title">Fresh Data</h1>


        <div class="profiles-section">
            <div class="profiles-header">
                <div class="header-buttons">
                    <a href="{{ route('add.fresh.data') }}" class="add-profile-btn" style="text-decoration:none;display:inline-block;">
                        <span class="add-icon">+</span>
                        Add New Data
                    </a>
                    <button class="add-profile-btn assign-btn">
                        <span class="add-icon">👥</span>
                        Assign Selected
                    </button>
                    <button class="add-profile-btn import-btn">
                        <span class="add-icon">📊</span>
                        Import from Excel
                    </button>
                </div>
            </div>

            <div class="filter-tabs filter-tabs-right">
                <button class="tab-btn" data-filter="not-assigned">Not Assigned</button>
                <button class="tab-btn" data-filter="zero-followup">ZERO Follow-Ups</button>
                <button class="tab-btn" data-filter="followup-today">Followup Today</button>
                <button class="tab-btn" data-filter="followup-due">Followup Due</button>
                <button class="tab-btn active" data-filter="all">All</button>
            </div>

            <div class="table-controls">
                <div class="show-entries">
                    <label>Show</label>
                    <select name="per_page" onchange="handlePerPageChange(this.value)">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span>entries</span>
                </div>
                <div class="search-box">
                    <label>Search:</label>
                    <input type="text" id="searchInput" placeholder="Search..." onkeyup="handleSearch()">
                </div>
            </div>

            <div class="data-table-section">
                <table class="data-table profiles-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th class="sortable" data-sort="name">Name <span class="sort-arrow">▲</span></th>
                            <th class="sortable" data-sort="mobile">Mobile Number</th>
                            <th class="sortable" data-sort="gender">Gender</th>
                            <th class="sortable" data-sort="profile_id">Profile ID</th>
                            <th class="sortable" data-sort="followup_date">Followup Date</th>
                            <th>Assigned To</th>
                            <th>Status</th>
                            <th>Comments</th>
                            <th>Source</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(session('success'))
                            <tr>
                                <td colspan="10">
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                </td>
                            </tr>
                        @endif
                        @forelse($freshData as $data)
                            <tr>
                                <td><input type="checkbox" class="row-checkbox"></td>
                                <td>{{ $data->name }}</td>
                                <td>{{ $data->mobile }}</td>
                                <td>{{ $data->gender ?? '-' }}</td>
                                <td>{{ $data->profile_id ?? '-' }}</td>
                                <td>{{ $data->followup_date ?? '-' }}</td>
                                <td>{{ $data->user ? $data->user->name : '-' }}</td>
                                <td>{{ $data->status ?? '-' }}</td>
                                <td>{{ $data->comments ?? $data->remarks ?? '-' }}</td>
                                <td>{{ $data->source }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" style="text-align:center;">No fresh data found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="table-footer">
                    <div class="table-info">
                        Showing 0 to 0 of 0 entries
                    </div>

                    <div class="pagination">
                        <button class="pagination-btn" disabled>Previous</button>
                        <button class="pagination-btn" disabled>Next</button>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <style>
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


            .user-email {
                color: rgba(255, 255, 255, 0.9);
                font-size: 14px;
                margin-right: 15px;
            }


            .profiles-section {
                padding: 30px;
            }

            .profiles-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 30px;
            }

            .header-buttons {
                display: flex;
                gap: 10px;
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
                padding: 12px 20px;
                border-radius: 10px;
                cursor: pointer;
                font-weight: 500;
                display: flex;
                align-items: center;
                gap: 8px;
                transition: all 0.3s;
                box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
                font-size: 14px;
            }

            .add-profile-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
            }

            .assign-btn {
                background: linear-gradient(135deg, #2196F3, #1976D2);
                box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
            }

            .assign-btn:hover {
                box-shadow: 0 6px 20px rgba(33, 150, 243, 0.4);
            }

            .import-btn {
                background: linear-gradient(135deg, #f44336, #d32f2f);
                box-shadow: 0 4px 15px rgba(244, 67, 54, 0.3);
            }

            .import-btn:hover {
                box-shadow: 0 6px 20px rgba(244, 67, 54, 0.4);
            }

            .add-icon {
                font-size: 16px;
                font-weight: bold;
            }


            .filter-tabs {
                display: flex;
                gap: 10px;
                margin-bottom: 25px;
                flex-wrap: wrap;
            }

            .filter-tabs-right {
                justify-content: flex-end;
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


            .data-table-section {
                background: white;
                border-radius: 10px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }

            .profiles-table {
                width: 100%;
                border-collapse: collapse;
                background: white;
            }

            .profiles-table th {
                background: #f8f9fa;
                padding: 15px 12px;
                text-align: left;
                font-weight: 600;
                color: #333;
                border-bottom: 2px solid #dee2e6;
                position: relative;
                user-select: none;
            }

            .profiles-table td {
                padding: 12px;
                border-bottom: 1px solid #dee2e6;
                color: #555;
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

            .pagination {
                display: flex;
                gap: 5px;
            }

            .pagination-btn {
                background: #f8f9fa;
                border: 1px solid #dee2e6;
                color: #6c757d;
                padding: 8px 12px;
                border-radius: 4px;
                cursor: not-allowed;
                font-size: 14px;
            }

            .pagination-btn:not(:disabled) {
                cursor: pointer;
                color: #495057;
            }

            .pagination-btn:not(:disabled):hover {
                background: #e9ecef;
            }


            @media (max-width: 1200px) {
                .filter-tabs {
                    justify-content: center;
                }

                .header-buttons {
                    flex-wrap: wrap;
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
                    min-width: 1000px;
                }

                .table-footer {
                    flex-direction: column;
                    gap: 15px;
                    text-align: center;
                }

                .header-buttons {
                    justify-content: center;
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

                .header-buttons {
                    flex-direction: column;
                    width: 100%;
                }

                .add-profile-btn {
                    justify-content: center;
                }
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const selectAllCheckbox = document.getElementById('selectAll');
                const rowCheckboxes = document.querySelectorAll('.row-checkbox');

                if (selectAllCheckbox) {
                    selectAllCheckbox.addEventListener('change', function() {
                        rowCheckboxes.forEach(checkbox => {
                            checkbox.checked = this.checked;
                        });
                    });
                }


                const tabBtns = document.querySelectorAll('.tab-btn');
                tabBtns.forEach(btn => {
                    btn.addEventListener('click', function() {
                        tabBtns.forEach(tab => tab.classList.remove('active'));
                        this.classList.add('active');
                        const filter = this.dataset.filter;
                        console.log('Filtering by:', filter);
                    });
                });


                const sortableHeaders = document.querySelectorAll('.sortable');
                let currentSort = {
                    column: null,
                    direction: 'asc'
                };

                sortableHeaders.forEach(header => {
                    header.addEventListener('click', function() {
                        const column = this.dataset.sort;
                        const arrow = this.querySelector('.sort-arrow');


                        sortableHeaders.forEach(h => {
                            const a = h.querySelector('.sort-arrow');
                            if (a) a.textContent = '▲';
                        });


                        if (currentSort.column === column) {
                            currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
                        } else {
                            currentSort.direction = 'asc';
                        }

                        currentSort.column = column;
                        if (arrow) {
                            arrow.textContent = currentSort.direction === 'asc' ? '▲' : '▼';
                        }


                        console.log('Sorting by:', column, currentSort.direction);
                    });
                });


                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    setTimeout(() => {
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 300);
                    }, 5000);
                });
            });


            function handleSearch() {
                const searchTerm = document.getElementById('searchInput').value.toLowerCase();
                const tableRows = document.querySelectorAll('.profiles-table tbody tr');

                tableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }


            function handlePerPageChange(value) {
                const url = new URL(window.location);
                url.searchParams.set('per_page', value);
                window.location = url;
            }
        </script>