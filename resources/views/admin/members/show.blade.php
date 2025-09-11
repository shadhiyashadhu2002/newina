<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Details - INA Matrimony</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
            background-color: #f5f7fa;
            color: #333;
        }

        .admin-container {
            min-height: 100vh;
            background-color: #f5f7fa;
        }

        /* Header */
        .admin-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        .clear-cache-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .clear-cache-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        .page-title {
            font-size: 28px;
            font-weight: 300;
            letter-spacing: 1px;
        }

        .admin-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .notification-badge {
            background: #ff4757;
            color: white;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .admin-text {
            color: rgba(255,255,255,0.9);
            font-size: 14px;
        }

        /* Main Layout */
        .main-layout {
            display: flex;
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px;
            gap: 30px;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: linear-gradient(145deg, #2c3e50, #34495e);
            border-radius: 15px;
            padding: 25px;
            height: fit-content;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-text {
            color: white;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            margin-bottom: 8px;
        }

        .nav-link {
            display: block;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            padding: 12px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }

        /* Member Profile */
        .profile-content {
            flex: 1;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        /* Profile Header */
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 20px 20px;
            animation: float 20s infinite linear;
        }

        @keyframes float {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            position: relative;
            z-index: 1;
        }

        .profile-name {
            font-size: 32px;
            font-weight: 300;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .profile-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .action-btn {
            padding: 12px 24px;
            border: 2px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.1);
            color: white;
            border-radius: 25px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }

        .status-badge {
            background: #2ed573;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            position: relative;
            z-index: 1;
        }

        /* Info Sections */
        .info-section {
            border-bottom: 1px solid #f0f0f0;
        }

        .section-header {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
            padding: 20px 40px;
            font-size: 18px;
            font-weight: 500;
            position: relative;
        }

        .section-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .section-content {
            padding: 40px;
        }

        .introduction-text {
            color: #666;
            line-height: 1.8;
            font-size: 16px;
            text-align: center;
            font-style: italic;
        }

        /* Two Column Layout for Fields */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            align-items: start;
        }

        .info-field {
            display: flex;
            flex-direction: column;
            min-height: 80px;
        }

        .field-label {
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .field-value {
            font-size: 16px;
            color: #555;
            padding: 15px 0;
            border-bottom: 2px solid #f0f0f0;
            min-height: 50px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .field-value:hover {
            border-bottom-color: #667eea;
            color: #333;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        /* Table Styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .table-header {
            background: linear-gradient(135deg, #2c3e50, #34495e);
            color: white;
        }

        .table-header th {
            padding: 18px 15px;
            text-align: left;
            font-weight: 500;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-row td {
            padding: 18px 15px;
            border-bottom: 1px solid #e9ecef;
            color: #555;
            font-size: 15px;
        }

        .table-row:hover {
            background: #f1f3f4;
        }

        .status-active {
            background: #2ed573;
            color: white;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .main-layout {
                padding: 20px;
                gap: 20px;
            }
        }

        @media (max-width: 768px) {
            .main-layout {
                flex-direction: column;
                padding: 15px;
            }
            
            .sidebar {
                width: 100%;
                order: 2;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .profile-header {
                padding: 30px 20px;
            }
            
            .section-content {
                padding: 30px 20px;
            }
            
            .profile-actions {
                flex-direction: column;
                align-items: center;
            }
            
            .action-btn {
                width: 200px;
            }

            .header-content {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 22px;
            }
            
            .profile-name {
                font-size: 24px;
            }
            
            .data-table {
                font-size: 12px;
            }
            
            .table-header th,
            .table-row td {
                padding: 12px 8px;
            }
        }
    </style>
</head>
<body>
    <div class="profile-content" style="margin: 40px auto; max-width: 900px;">
                <!-- Profile Header -->
                <div class="profile-header">
                    <div class="profile-avatar">👤</div>
                    <h2 class="profile-name">JOHN SMITH</h2>
                    <div class="profile-actions">
                        <button class="action-btn">Package</button>
                        <button class="action-btn">Block</button>
                    </div>
                    <div class="status-badge">Active Account</div>
                </div>

                <!-- Introduction Section -->
                <div class="info-section">
                    <div class="section-header">Introduction</div>
                    <div class="section-content">
                        <p class="introduction-text">Partner Expectation AGE : 21 TO 23 MARITAL STATUS : UNMARRIED</p>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="info-section">
                    <div class="section-header">Basic Information</div>
                    <div class="section-content">
                        <div class="info-grid">
                            <div class="info-field">
                                <div class="field-label">First Name</div>
                                <div class="field-value">John</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Last Name</div>
                                <div class="field-value">Smith</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Gender</div>
                                <div class="field-value">Male</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Date Of Birth</div>
                                <div class="field-value">1995-08-15</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Email</div>
                                <div class="field-value">john.smith@email.com</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Phone Number</div>
                                <div class="field-value">+91 9876543210</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Marital Status</div>
                                <div class="field-value">Unmarried</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Number Of Children</div>
                                <div class="field-value">0</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">On Behalf</div>
                                <div class="field-value">Self</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Annual Salary</div>
                                <div class="field-value">Rs 200,000 - Rs 500,000</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Present Address -->
                <div class="info-section">
                    <div class="section-header">Present Address</div>
                    <div class="section-content">
                        <div class="info-grid">
                            <div class="info-field">
                                <div class="field-label">City</div>
                                <div class="field-value">Mumbai</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">State</div>
                                <div class="field-value">Maharashtra</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Country</div>
                                <div class="field-value">India</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Postal Code</div>
                                <div class="field-value">400001</div>
                            </div>
                            <div class="info-field full-width">
                                <div class="field-label">Address</div>
                                <div class="field-value">123 Main Street, Bandra West, Mumbai</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Education -->
                <div class="info-section">
                    <div class="section-header">Education</div>
                    <div class="section-content">
                        <table class="data-table">
                            <thead class="table-header">
                                <tr>
                                    <th>Degree</th>
                                    <th>Institution</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="table-row">
                                    <td>B.Tech Computer Science</td>
                                    <td>IIT Mumbai</td>
                                    <td>2013</td>
                                    <td>2017</td>
                                    <td><span class="status-active">Completed</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Career -->
                <div class="info-section">
                    <div class="section-header">Career</div>
                    <div class="section-content">
                        <table class="data-table">
                            <thead class="table-header">
                                <tr>
                                    <th>Designation</th>
                                    <th>Company</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="table-row">
                                    <td>Software Engineer</td>
                                    <td>Tech Solutions Ltd</td>
                                    <td>2018</td>
                                    <td>Present</td>
                                    <td><span class="status-active">Current</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Physical Attributes -->
                <div class="info-section">
                    <div class="section-header">Physical Attributes</div>
                    <div class="section-content">
                        <div class="info-grid">
                            <div class="info-field">
                                <div class="field-label">Height</div>
                                <div class="field-value">5'10"</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Weight</div>
                                <div class="field-value">70 kg</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Eye Color</div>
                                <div class="field-value">Brown</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Hair Color</div>
                                <div class="field-value">Black</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Complexion</div>
                                <div class="field-value">Fair</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Blood Group</div>
                                <div class="field-value">O+</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Body Type</div>
                                <div class="field-value">Average</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Body Art</div>
                                <div class="field-value">None</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Disability</div>
                                <div class="field-value">None</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Spiritual & Social Background -->
                <div class="info-section">
                    <div class="section-header">Spiritual & Social Background</div>
                    <div class="section-content">
                        <div class="info-grid">
                            <div class="info-field">
                                <div class="field-label">Religion</div>
                                <div class="field-value">Hindu</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Caste</div>
                                <div class="field-value">Brahmin</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Sub Caste</div>
                                <div class="field-value">Iyer</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Ethnicity</div>
                                <div class="field-value">South Indian</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Personal Value</div>
                                <div class="field-value">Traditional</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Family Value</div>
                                <div class="field-value">Orthodox</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Community Value</div>
                                <div class="field-value">Conservative</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Family Information -->
                <div class="info-section">
                    <div class="section-header">Family Information</div>
                    <div class="section-content">
                        <div class="info-grid">
                            <div class="info-field">
                                <div class="field-label">Father</div>
                                <div class="field-value">Mr. Robert Smith</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Father Occupation</div>
                                <div class="field-value">Business</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Mother</div>
                                <div class="field-value">Mrs. Sarah Smith</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Mother Occupation</div>
                                <div class="field-value">Teacher</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Sibling</div>
                                <div class="field-value">1 Sister</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">No. of Brothers</div>
                                <div class="field-value">0</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">No. of Sisters</div>
                                <div class="field-value">1</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">About Parents</div>
                                <div class="field-value">Both parents are well-educated and supportive</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">About Siblings</div>
                                <div class="field-value">Sister is married and settled</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">About Relatives</div>
                                <div class="field-value">Close-knit family with good relationships</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Partner Expectation -->
                <div class="info-section">
                    <div class="section-header">Partner Expectation</div>
                    <div class="section-content">
                        <div class="info-grid">
                            <div class="info-field">
                                <div class="field-label">General</div>
                                <div class="field-value">Well-educated and family-oriented</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Residence Country</div>
                                <div class="field-value">India</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Height</div>
                                <div class="field-value">5'4" - 5'8"</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Weight</div>
                                <div class="field-value">50-65 kg</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Marital Status</div>
                                <div class="field-value">Unmarried</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Children Acceptable</div>
                                <div class="field-value">No</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Religion</div>
                                <div class="field-value">Hindu</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Caste</div>
                                <div class="field-value">Brahmin</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Sub Caste</div>
                                <div class="field-value">Any</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Language</div>
                                <div class="field-value">Hindi, English</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Education</div>
                                <div class="field-value">Graduate or above</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Profession</div>
                                <div class="field-value">Any decent profession</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Smoking Acceptable</div>
                                <div class="field-value">No</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Drinking Acceptable</div>
                                <div class="field-value">Occasionally</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Diet</div>
                                <div class="field-value">Vegetarian</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Body Type</div>
                                <div class="field-value">Average to Athletic</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Personal Value</div>
                                <div class="field-value">Traditional</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Manglik</div>
                                <div class="field-value">Doesn't matter</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Preferred Country</div>
                                <div class="field-value">India</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Preferred State</div>
                                <div class="field-value">Maharashtra, Karnataka</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Family Value</div>
                                <div class="field-value">Traditional</div>
                            </div>
                            <div class="info-field">
                                <div class="field-label">Complexion</div>
                                <div class="field-value">Fair to Medium</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>