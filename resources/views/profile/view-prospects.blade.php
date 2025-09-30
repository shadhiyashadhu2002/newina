<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>INA Dashboard - View Prospects</title>
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
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            margin-bottom: 30px;
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            background: linear-gradient(135deg, #4a69bd, #5a4fcf);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
            text-align: center;
        }

        .page-subtitle {
            color: #666;
            text-align: center;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .profile-info {
            background: linear-gradient(135deg, #48CAE4, #0096C7);
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            text-align: center;
            font-weight: 600;
        }

        .prospects-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .table-container {
            overflow-x: auto;
            margin-top: 20px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .prospects-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 15px;
            overflow: hidden;
        }

        .prospects-table thead {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        }

        .prospects-table th {
            padding: 20px 15px;
            text-align: left;
            font-weight: 600;
            color: #555;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #dee2e6;
        }

        .prospects-table td {
            padding: 20px 15px;
            border-bottom: 1px solid #f1f3f4;
            font-size: 14px;
            color: #333;
        }

        .prospects-table tbody tr {
            transition: all 0.3s ease;
        }

        .prospects-table tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff, #f0f4ff);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .prospect-id {
            font-weight: 700;
            color: #4a69bd;
            font-size: 16px;
        }

        .prospect-name {
            font-weight: 600;
            color: #333;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-interested {
            background: linear-gradient(135deg, #A8E6CF, #7FCDCD);
            color: white;
        }

        .status-pending {
            background: linear-gradient(135deg, #FFE066, #FF9F43);
            color: white;
        }

        .status-contacted {
            background: linear-gradient(135deg, #48CAE4, #0096C7);
            color: white;
        }

        .status-rejected {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
        }

        .action-btn {
            background: linear-gradient(135deg, #ffd700, #ffb700);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.4);
            text-decoration: none;
            color: white;
        }

        .no-prospects {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .no-prospects-icon {
            font-size: 48px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 120px;
        }

        .btn-back {
            background: linear-gradient(135deg, #A8E6CF, #7FCDCD);
            color: white;
            box-shadow: 0 5px 15px rgba(168, 230, 207, 0.4);
        }

        .btn-back:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(168, 230, 207, 0.6);
            text-decoration: none;
            color: white;
        }

        .btn-refresh {
            background: linear-gradient(135deg, #48CAE4, #0096C7);
            color: white;
            box-shadow: 0 5px 15px rgba(72, 202, 228, 0.4);
        }

        .btn-refresh:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(72, 202, 228, 0.6);
        }

        @media (max-width: 768px) {
            .header, .prospects-container {
                padding: 20px;
            }
            
            .prospects-table th,
            .prospects-table td {
                padding: 15px 10px;
                font-size: 12px;
            }
            
            .button-group {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="page-title">View Prospects</h1>
            <p class="page-subtitle">View and manage prospects for the selected profile</p>
            <div class="profile-info">
                Profile ID: PYPL22027
            </div>
        </div>

        <div class="prospects-container">
            <div class="table-container">
                <table class="prospects-table">
                    <thead>
                        <tr>
                            <th>Prospect ID</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Education</th>
                            <th>Occupation</th>
                            <th>Location</th>
                            <th>Contact Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="prospect-id">PR001</td>
                            <td class="prospect-name">Priya Sharma</td>
                            <td>28</td>
                            <td>MBA</td>
                            <td>Software Engineer</td>
                            <td>Bangalore</td>
                            <td>07-Mar-2025</td>
                            <td><span class="status-badge status-interested">Interested</span></td>
                            <td><a href="#" class="action-btn">✏️ Edit</a></td>
                        </tr>
                        <tr>
                            <td class="prospect-id">PR002</td>
                            <td class="prospect-name">Anjali Kumar</td>
                            <td>26</td>
                            <td>B.Tech</td>
                            <td>Data Analyst</td>
                            <td>Chennai</td>
                            <td>05-Jul-2025</td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                            <td><a href="#" class="action-btn">✏️ Edit</a></td>
                        </tr>
                        <tr>
                            <td class="prospect-id">PR003</td>
                            <td class="prospect-name">Meera Nair</td>
                            <td>29</td>
                            <td>M.Com</td>
                            <td>Accountant</td>
                            <td>Kochi</td>
                            <td>05-Jul-2025</td>
                            <td><span class="status-badge status-contacted">Contacted</span></td>
                            <td><a href="#" class="action-btn">✏️ Edit</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="button-group">
                <button class="btn btn-refresh" onclick="refreshProspects()">🔄 Refresh</button>
                <a href="#" class="btn btn-back">← Back</a>
            </div>
        </div>
    </div>

    <script>
        function refreshProspects() {
            location.reload();
        }

        // Add animation for table rows
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.prospects-table tbody tr');
            rows.forEach((row, index) => {
                row.style.animationDelay = `${0.3 + (index * 0.1)}s`;
                row.style.animation = 'fadeInUp 0.8s ease-out both';
            });
        });
    </script>
</body>
</html>