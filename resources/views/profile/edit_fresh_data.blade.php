<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INA Dashboard - Edit Fresh Data</title>
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

        /* Header Styles */
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

        /* Form Section */
        .form-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .form-header {
            background: #f8f9fa;
            padding: 20px 30px;
            border-bottom: 1px solid #dee2e6;
        }

        .form-header h2 {
            color: #333;
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }

        .form-content {
            padding: 30px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 25px;
        }

        .form-row.single {
            grid-template-columns: 1fr;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            color: #555;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            background: #fff;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.1);
        }

        .form-group select {
            cursor: pointer;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #4CAF50;
        }

        .checkbox-group label {
            margin: 0;
            cursor: pointer;
            font-size: 14px;
        }

        /* Action Buttons */
        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            padding: 20px 30px;
            background: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }

        .action-btn {
            padding: 12px 30px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-width: 120px;
            justify-content: center;
        }

        .update-btn {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
        }

        .update-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
        }

        .back-btn {
            background: linear-gradient(135deg, #6c757d, #545b62);
            color: white;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
        }

        /* Alert Styles */
        .alert {
            padding: 15px 20px;
            margin-bottom: 20px;
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .form-actions {
                flex-direction: column;
                align-items: center;
            }

            .action-btn {
                width: 200px;
            }

            .main-content {
                padding: 20px;
            }

            .form-content {
                padding: 20px;
            }

            .header-nav {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .main-header {
                padding: 15px 20px;
            }

            .page-title {
                font-size: 24px;
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
                <li><a href="#" class="active" data-page="fresh-data">Fresh Data</a></li>
                <li><a href="#" data-page="abc">abc</a></li>
                <li><a href="#" data-page="services">Services <span class="dropdown-arrow">▼</span></a></li>
            </ul>
        </nav>
        <button class="logout-btn">Logout</button>
    </header>

    <main class="main-content">
        <h1 class="page-title">Edit Fresh Data</h1>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <form method="POST" action="{{ route('update.fresh.data', $freshData->id) }}">
            @csrf
            <!-- Basic Information Section -->
            <div class="form-section">
                <div class="form-header">
                    <h2>Basic Information</h2>
                </div>
                <div class="form-content">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="mobile">Mobile Number</label>
                            <input type="tel" id="mobile" name="mobile" value="{{ old('mobile', $freshData->mobile ?? '') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender">
                                <option value="">Select Gender</option>
                                <option value="male" {{ (old('gender', $freshData->gender ?? '') == 'male') ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ (old('gender', $freshData->gender ?? '') == 'female') ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ (old('gender', $freshData->gender ?? '') == 'other') ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $freshData->name ?? '') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="source">Source</label>
                            <select id="source" name="source" required>
                                <option value="database" {{ (old('source', $freshData->source ?? '') == 'database') ? 'selected' : '' }}>Database</option>
                                <option value="website" {{ (old('source', $freshData->source ?? '') == 'website') ? 'selected' : '' }}>Website</option>
                                <option value="referral" {{ (old('source', $freshData->source ?? '') == 'referral') ? 'selected' : '' }}>Referral</option>
                                <option value="social_media" {{ (old('source', $freshData->source ?? '') == 'social_media') ? 'selected' : '' }}>Social Media</option>
                                <option value="advertisement" {{ (old('source', $freshData->source ?? '') == 'advertisement') ? 'selected' : '' }}>Advertisement</option>
                                <option value="walk_in" {{ (old('source', $freshData->source ?? '') == 'walk_in') ? 'selected' : '' }}>Walk-in</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row single">
                        <div class="form-group">
                            <label for="remarks">Remarks</label>
                            <textarea id="remarks" name="remarks" placeholder="Enter any remarks or additional information...">{{ old('remarks', $freshData->remarks ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" id="profile_created" name="profile_created" {{ old('profile_created', $freshData->profile_created ?? false) ? 'checked' : '' }}>
                        <label for="profile_created">Is Profile Created? (Please Tick)</label>
                    </div>
                </div>
            </div>

            <!-- Additional Information Section -->
            <div class="form-section">
                <div class="form-header">
                    <h2>Additional Information once Profile Created</h2>
                </div>
                <div class="form-content">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="registration_date">Registration Date</label>
                            <input type="date" id="registration_date" name="registration_date" value="{{ old('registration_date', $freshData->registration_date ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="profile_id">Profile Id</label>
                            <input type="text" id="profile_id" name="profile_id" placeholder="Enter Profile ID" value="{{ old('profile_id', $freshData->profile_id ?? '') }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="mobile_number_2">Mobile Number 2</label>
                            <input type="tel" id="mobile_number_2" name="mobile_number_2" placeholder="Enter secondary mobile number" value="{{ old('mobile_number_2', $freshData->mobile_number_2 ?? '') }}">
                        </div>
                        <div class="form-group">
                            <label for="whatsapp_number">WhatsApp Number</label>
                            <input type="tel" id="whatsapp_number" name="whatsapp_number" placeholder="Enter WhatsApp number" value="{{ old('whatsapp_number', $freshData->whatsapp_number ?? '') }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="checkbox-group">
                            <input type="checkbox" id="photo_uploaded" name="photo_uploaded" {{ old('photo_uploaded', $freshData->photo_uploaded ?? false) ? 'checked' : '' }}>
                            <label for="photo_uploaded">Is Photo Uploaded? (Please Tick)</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="welcome_call" name="welcome_call" {{ old('welcome_call', $freshData->welcome_call ?? false) ? 'checked' : '' }}>
                            <label for="welcome_call">Is Welcome Call Done? (Please Tick)</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-section">
                <div class="form-actions">
                    <button type="submit" class="action-btn update-btn">
                        <span>💾</span>
                        Update Data
                    </button>
                    <a href="#" class="action-btn back-btn">
                        <span>←</span>
                        Back to List
                    </a>
                </div>
            </div>
        </form>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // (Removed JS form handler so Laravel can handle redirect after POST)

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
                });
            });

            // Back button functionality
            document.querySelector('.back-btn').addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to go back? Any unsaved changes will be lost.')) {
                    // Navigate back to fresh data list
                    window.history.back();
                }
            });

            // Logout functionality
            document.querySelector('.logout-btn').addEventListener('click', function() {
                if(confirm('Are you sure you want to logout?')) {
                    alert('Logging out...');
                    // Add logout logic here
                }
            });

            // (Removed JS that disables additional fields. All fields are now always editable.)
        });
    </script>
</body>
</html>