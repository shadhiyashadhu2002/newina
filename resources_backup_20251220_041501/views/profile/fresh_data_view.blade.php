<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INA - Fresh Data Followup Log</title>
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

        /* Header Styles */
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

        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-email {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
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
            text-decoration: none;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }

        .main-content {
            padding: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .page-title {
            color: #fff;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 25px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .add-followup-btn {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
            font-size: 14px;
        }

        .add-followup-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
        }

        .add-icon {
            font-size: 16px;
            font-weight: bold;
        }

        /* Table Section */
        .table-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .followup-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .followup-table th {
            background: #f8f9fa;
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #dee2e6;
        }

        .followup-table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
            color: #555;
        }

        .followup-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .followup-table tbody tr:hover {
            background: #e0f2fe;
        }

        .mobile-number {
            font-weight: 500;
            color: #495057;
        }

        .comment-text {
            color: #6c757d;
            max-width: 200px;
            word-wrap: break-word;
        }

        .followup-action {
            color: #6c757d;
        }

        .date-cell {
            color: #6c757d;
            white-space: nowrap;
        }

        .email-cell {
            color: #6c757d;
            font-size: 13px;
            max-width: 180px;
            word-wrap: break-word;
        }

        /* Back Button */
        .back-btn {
            background: linear-gradient(135deg, #f44336, #d32f2f);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 500;
            margin-top: 20px;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(244, 67, 54, 0.3);
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(244, 67, 54, 0.4);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }

        .empty-state p {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .empty-state small {
            font-size: 14px;
            color: #adb5bd;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-header {
                padding: 12px 20px;
                flex-direction: column;
                gap: 15px;
            }

            .header-nav {
                gap: 10px;
                flex-wrap: wrap;
                justify-content: center;
            }

            .header-nav a {
                font-size: 14px;
                padding: 8px 12px;
            }

            .main-content {
                padding: 20px;
            }

            .table-section {
                overflow-x: auto;
            }

            .followup-table {
                min-width: 800px;
            }

            .add-followup-btn {
                width: 100%;
                justify-content: center;
            }

            .back-btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .header-right {
                flex-direction: column;
                gap: 10px;
            }

            .user-email {
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <header class="main-header">
        <a href="#" class="header-brand">INA</a>
        <nav>
            <ul class="header-nav">
                <li><a href="#" data-page="home">Home</a></li>
                <li><a href="#" data-page="profiles">Profiles</a></li>
                <li><a href="#" data-page="sales">Sales <span class="dropdown-arrow">▼</span></a></li>
                <li><a href="#" data-page="helpline">HelpLine</a></li>
                <li><a href="#" class="active" data-page="fresh-data">Fresh Data <span class="dropdown-arrow">▼</span></a></li>
                <li><a href="#" data-page="puyyarla">abc</a></li>
                <li><a href="#" data-page="services">Services <span class="dropdown-arrow">▼</span></a></li>
            </ul>
        </nav>
        <!-- <div class="header-right">
            <span class="user-email">Hello greeshmargadesh1989@gmail.com</span>
            <a href="#" class="logout-btn">Logout</a>
        </div> -->
    </header>

    <main class="main-content">
        <h1 class="page-title">Fresh Data Followup Log</h1>

        <a href="#" class="add-followup-btn">
            <span class="add-icon">+</span>
            Add Next Follow-Up
        </a>

        <div class="table-section">
            <table class="followup-table">
                <thead>
                    <tr>
                        <th>Mobile No</th>
                        <th>Comment</th>
                        <th>Followup Action</th>
                        <th>Next Followup Date</th>
                        <th>Comment Date</th>
                        <th>Comment By</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="mobile-number">{{ $freshData->mobile }}</td>
                        <td class="comment-text">{{ $freshData->remarks ?? '-' }}</td>
                        <td class="followup-action">{{ $freshData->status ?? '-' }}</td>
                        <td class="date-cell">{{ $freshData->next_followup_date ?? '-' }}</td>
                        <td class="date-cell">{{ $freshData->created_at ? $freshData->created_at->format('d-M-Y') : '-' }}</td>
                        <td class="email-cell">{{ $freshData->user->name ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <button class="back-btn" id="backButton">Back to Fresh Data</button>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Navigation functionality
            document.querySelectorAll('.header-nav a').forEach(link => {
                link.addEventListener('click', function(e) {
                    if (this.getAttribute('href') === '#') {
                        e.preventDefault();
                    }
                    
                    document.querySelectorAll('.header-nav a').forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                    
                    const page = this.getAttribute('data-page');
                    console.log('Navigating to:', page);
                    
                    // If Fresh Data link is clicked
                    if (page === 'fresh-data') {
                        // In a real app, this would navigate to the Fresh Data page
                        alert('Navigating to Fresh Data page');
                    }
                });
            });

            // Add Follow-up button functionality
            document.querySelector('.add-followup-btn').addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Opening Add Follow-up form');
                // Add your follow-up form logic here
                alert('Add Follow-up form would open here');
            });

            // Logout functionality
            document.querySelector('.logout-btn').addEventListener('click', function(e) {
                e.preventDefault();
                if(confirm('Are you sure you want to logout?')) {
                    console.log('Logging out...');
                    // Add logout logic here
                    alert('Logging out...');
                }
            });
            
            // Back button functionality - goes to Fresh Data page
            document.getElementById('backButton').addEventListener('click', function() {
                // In a real application, this would navigate to the Fresh Data page
                // For demonstration, we'll show an alert and simulate navigation
                alert('Navigating back to Fresh Data page');
                // window.location.href = 'fresh-data.html'; // Actual navigation in a real app
            });
            
            // Handle browser back button
            window.addEventListener('popstate', function(event) {
                // When browser back button is clicked, navigate to Fresh Data
                alert('Navigating back to Fresh Data page via browser back button');
                // In a real app, you might use:
                // window.location.href = 'fresh-data.html';
            });
        });

        // Add more followup entries dynamically (example function)
        function addFollowupEntry(mobileNo, comment, action, nextDate, commentDate, commentBy) {
            const tbody = document.querySelector('.followup-table tbody');
            const newRow = document.createElement('tr');
            
            newRow.innerHTML = `
                <td class="mobile-number">${mobileNo}</td>
                <td class="comment-text">${comment}</td>
                <td class="followup-action">${action}</td>
                <td class="date-cell">${nextDate}</td>
                <td class="date-cell">${commentDate}</td>
                <td class="email-cell">${commentBy}</td>
            `;
            
            tbody.appendChild(newRow);
        }
    </script>
</body>

</html>