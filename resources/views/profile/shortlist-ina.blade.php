<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>INA Dashboard - Search Profiles</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            max-width: 1000px;
            width: 100%;
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

        .form-title {
            font-size: 32px;
            font-weight: 700;
            background: linear-gradient(135deg, #4a69bd, #5a4fcf);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #555;
            font-size: 14px;
        }

        .form-input, .form-select {
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            background: white;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-input:focus, .form-select:focus {
            border-color: #4a69bd;
            box-shadow: 0 0 0 3px rgba(74, 105, 189, 0.1);
        }

        .form-select {
            cursor: pointer;
        }

        .date-input {
            position: relative;
        }

        .date-input input[type="date"] {
            appearance: none;
            -webkit-appearance: none;
            color: #666;
        }

        .date-input input[type="date"]::-webkit-calendar-picker-indicator {
            background: transparent;
            bottom: 0;
            color: transparent;
            cursor: pointer;
            height: auto;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            width: auto;
        }

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
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

        .btn-search {
            background: linear-gradient(135deg, #48CAE4, #0096C7);
            color: white;
            box-shadow: 0 5px 15px rgba(72, 202, 228, 0.4);
        }

        .btn-search:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(72, 202, 228, 0.6);
        }

        .btn-clear {
            background: linear-gradient(135deg, #F48C94, #E74C3C);
            color: white;
            box-shadow: 0 5px 15px rgba(244, 140, 148, 0.4);
        }

        .btn-clear:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(244, 140, 148, 0.6);
        }

        .btn-back {
            background: linear-gradient(135deg, #A8E6CF, #7FCDCD);
            color: white;
            box-shadow: 0 5px 15px rgba(168, 230, 207, 0.4);
        }

        .btn-back:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(168, 230, 207, 0.6);
        }

        .profile-count {
            text-align: center;
            color: #666;
            font-size: 18px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 30px 20px;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .button-group {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 200px;
            }
            
            .results-grid {
                grid-template-columns: 1fr;
            }
            
            .profile-content {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            
            .profile-photo-container {
                order: -1;
                margin-bottom: 15px;
            }
            
            .profile-photo, .no-photo {
                width: 100px;
                height: 100px;
            }
        }

        .two-column {
            grid-column: span 2;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 768px) {
            .two-column {
                grid-column: span 1;
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1 class="form-title">Search Profiles (INA Database)</h1>
        
        <!-- Success Message -->
        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        
        <form id="searchProfilesForm" method="POST" action="{{ route('search.profiles') }}">
            @csrf
            
            <div class="form-grid">
                <!-- Age Range -->
                <div class="form-group">
                    <label class="form-label">Age (Min):</label>
                    <input type="number" name="age_min" class="form-input" placeholder="Min age" min="18" max="100">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Age (Max):</label>
                    <input type="number" name="age_max" class="form-input" placeholder="Max age" min="18" max="100">
                </div>

                <!-- Height Range -->
                <div class="form-group">
                    <label class="form-label">Height (cm Min):</label>
                    <input type="number" name="height_min" class="form-input" placeholder="Min height (cm)" min="100" max="250">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Height (cm Max):</label>
                    <input type="number" name="height_max" class="form-input" placeholder="Max height (cm)" min="100" max="250">
                </div>

                <!-- Weight Range -->
                <div class="form-group">
                    <label class="form-label">Weight (kg Min):</label>
                    <input type="number" name="weight_min" class="form-input" placeholder="Min weight (kg)" min="30" max="200">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Weight (kg Max):</label>
                    <input type="number" name="weight_max" class="form-input" placeholder="Max weight (kg)" min="30" max="200">
                </div>

                <!-- Education -->
                <div class="form-group">
                    <label class="form-label">Education:</label>
                    <select name="education" class="form-select">
                        <option value="">-- Select Education --</option>
                        <option value="high_school">High School</option>
                        <option value="bachelor">Bachelor's Degree</option>
                        <option value="master">Master's Degree</option>
                        <option value="doctorate">Doctorate</option>
                        <option value="diploma">Diploma</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- District -->
                <div class="form-group">
                    <label class="form-label">District:</label>
                    <select name="district" class="form-select">
                        <option value="">-- Select District --</option>
                        <option value="ernakulam">Ernakulam</option>
                        <option value="kottayam">Kottayam</option>
                        <option value="thrissur">Thrissur</option>
                        <option value="alappuzha">Alappuzha</option>
                        <option value="kollam">Kollam</option>
                        <option value="thiruvananthapuram">Thiruvananthapuram</option>
                        <option value="palakkad">Palakkad</option>
                        <option value="malappuram">Malappuram</option>
                        <option value="kozhikode">Kozhikode</option>
                        <option value="wayanad">Wayanad</option>
                        <option value="kannur">Kannur</option>
                        <option value="kasaragod">Kasaragod</option>
                        <option value="pathanamthitta">Pathanamthitta</option>
                        <option value="idukki">Idukki</option>
                    </select>
                </div>

                <!-- Religion -->
                <div class="form-group">
                    <label class="form-label">Religion:</label>
                    <select name="caste" class="form-select">
                        <option value="">-- Select Religion --</option>
                        <option value="hindu">Hindu</option>
                        <option value="muslim">Muslim</option>
                        <option value="christian">Christian</option>
                    </select>
                </div>

                <!-- Gender -->
                <div class="form-group">
                    <label class="form-label">Gender:</label>
                    <select name="gender" class="form-select">
                        <option value="">-- Select Gender --</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- Registration Date Range -->
                <div class="form-group">
                    <label class="form-label">Registered From:</label>
                    <div class="date-input">
                        <input type="date" name="registered_from" class="form-input">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Registered To:</label>
                    <div class="date-input">
                        <input type="date" name="registered_to" class="form-input">
                    </div>
                </div>

                <!-- Profile ID -->
                <div class="form-group">
                    <label class="form-label">Profile ID:</label>
                    <input type="text" name="profile_id" class="form-input" placeholder="Enter Profile ID">
                </div>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-search">Search</button>
                <button type="button" class="btn btn-clear" onclick="clearForm()">Clear</button>
                <a href="{{ route('active.service') }}" class="btn btn-back">Back</a>
            </div>
        </form>

        <!-- Search Results Section -->
        @if(session('search_results'))
        <div class="results-container">
            <h2 class="results-title">Search Results</h2>
            <p class="results-summary">{{ session('success') }}</p>
            
            <div class="results-grid">
                @foreach(session('search_results') as $profile)
                <div class="profile-card">
                    <div class="profile-header">
                        <div class="header-left">
                            <h3>{{ $profile['name'] }}</h3>
                            <span class="profile-code">ID: {{ $profile['code'] }}</span>
                        </div>
                        <div class="header-right">
                            <button class="btn-print" onclick="printProfile({{ $profile['user_id'] }}, '{{ $profile['name'] }}', '{{ $profile['code'] }}')">
                                <i class="fas fa-print"></i>
                                Print
                            </button>
                        </div>
                    </div>
                    <div class="profile-content">
                        <div class="profile-details">
                            <div class="profile-field">
                                <strong>Age:</strong> {{ $profile['age'] ?? 'N/A' }} years
                            </div>
                            <div class="profile-field">
                                <strong>Height:</strong> {{ $profile['height'] ?? 'Not specified' }}
                            </div>
                            <div class="profile-field">
                                <strong>Weight:</strong> {{ $profile['weight'] ?? 'Not specified' }}
                            </div>
                            <div class="profile-field">
                                <strong>Religion:</strong> {{ $profile['religion'] ?? 'Not specified' }}
                            </div>
                            <div class="profile-field">
                                <strong>Caste:</strong> {{ $profile['caste'] ?? 'Not specified' }}
                            </div>
                            <div class="profile-field">
                                <strong>Marital Status:</strong> {{ $profile['marital_status'] ?? 'Not specified' }}
                            </div>
                            <div class="profile-field">
                                <strong>Career:</strong> {{ $profile['career'] ?? 'Not specified' }}
                            </div>
                            <div class="profile-field">
                                <strong>Location:</strong> {{ $profile['location'] ?? 'Not specified' }}
                            </div>
                        </div>
                        <div class="profile-photo-container">
                            @if(isset($profile['has_photo']) && $profile['has_photo'] && $profile['photo_url'])
                                <img src="{{ $profile['photo_url'] }}" alt="{{ $profile['name'] }}'s photo" class="profile-photo">
                            @else
                                <div class="no-photo">
                                    <i class="fas fa-user"></i>
                                    <span>No Photo</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="profile-actions">
                        <a href="{{ route('client.details', $profile['user_id']) }}" class="btn-view-profile">View Details</a>
                        <button class="btn-shortlist" onclick="shortlistProfile({{ $profile['user_id'] }})">Add to Shortlist</button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <style>
            .results-container {
                margin-top: 40px;
                background: rgba(255, 255, 255, 0.95);
                border-radius: 20px;
                padding: 30px;
                box-shadow: 0 15px 30px rgba(0,0,0,0.2);
                backdrop-filter: blur(10px);
            }
            
            .results-title {
                font-size: 24px;
                font-weight: 600;
                color: #ac0742;
                margin-bottom: 10px;
                text-align: center;
            }
            
            .results-summary {
                text-align: center;
                color: #28a745;
                font-weight: 500;
                margin-bottom: 25px;
                font-size: 16px;
            }
            
            .results-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
                gap: 20px;
            }
            
            .profile-card {
                background: white;
                border-radius: 15px;
                padding: 20px;
                box-shadow: 0 8px 20px rgba(0,0,0,0.1);
                transition: transform 0.3s ease;
                border: 2px solid transparent;
            }
            
            .profile-card:hover {
                transform: translateY(-5px);
                border-color: #ac0742;
            }
            
            .profile-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 15px;
                padding-bottom: 10px;
                border-bottom: 2px solid #f0f0f0;
            }
            
            .header-left {
                flex: 1;
            }
            
            .header-right {
                flex-shrink: 0;
            }
            
            .profile-content {
                display: flex;
                gap: 20px;
                align-items: flex-start;
            }
            
            .profile-details {
                flex: 1;
            }
            
            .profile-photo-container {
                flex-shrink: 0;
            }
            
            .profile-photo {
                width: 120px;
                height: 120px;
                border-radius: 15px;
                object-fit: cover;
                border: 3px solid #ac0742;
                box-shadow: 0 4px 15px rgba(172, 7, 66, 0.3);
                transition: transform 0.3s ease;
            }
            
            .profile-photo:hover {
                transform: scale(1.05);
            }
            
            .no-photo {
                width: 120px;
                height: 120px;
                border-radius: 15px;
                background: linear-gradient(135deg, #f8f9fa, #e9ecef);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                border: 3px solid #dee2e6;
                color: #6c757d;
                text-align: center;
            }
            
            .no-photo i {
                font-size: 30px;
                margin-bottom: 8px;
            }
            
            .no-photo span {
                font-size: 12px;
                font-weight: 500;
            }
            
            .btn-print {
                background: linear-gradient(135deg, #ac0742, #d63384);
                color: white;
                border: none;
                padding: 8px 12px;
                border-radius: 8px;
                font-size: 12px;
                font-weight: 500;
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 5px;
                transition: all 0.3s ease;
                box-shadow: 0 2px 8px rgba(172, 7, 66, 0.3);
            }
            
            .btn-print:hover {
                background: linear-gradient(135deg, #8a0534, #b02a5b);
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(172, 7, 66, 0.4);
            }
            
            .btn-print i {
                font-size: 14px;
            }
            
            .profile-header h3 {
                color: #ac0742;
                font-size: 18px;
                margin: 0;
            }
            
            .profile-code {
                background: #ac0742;
                color: white;
                padding: 4px 8px;
                border-radius: 8px;
                font-size: 12px;
                font-weight: 500;
            }
            
            .profile-field {
                margin-bottom: 8px;
                font-size: 14px;
            }
            
            .profile-field strong {
                color: #555;
                margin-right: 5px;
            }
            
            .profile-actions {
                display: flex;
                gap: 10px;
                margin-top: 15px;
                padding-top: 15px;
                border-top: 1px solid #f0f0f0;
            }
            
            .btn-view-profile, .btn-shortlist {
                flex: 1;
                padding: 8px 12px;
                border-radius: 8px;
                text-decoration: none;
                text-align: center;
                font-size: 13px;
                font-weight: 500;
                border: none;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            
            .btn-view-profile {
                background: #6c757d;
                color: white;
            }
            
            .btn-view-profile:hover {
                background: #545b62;
                color: white;
            }
            
            .btn-shortlist {
                background: #ac0742;
                color: white;
            }
            
            .btn-shortlist:hover {
                background: #8a0535;
            }
        </style>
        @endif
    </div>

    <script>
        function clearForm() {
            document.getElementById('searchProfilesForm').reset();
            
            // Reset date fields to default values
            document.querySelector('input[name="registered_from"]').value = '';
            document.querySelector('input[name="registered_to"]').value = '';
        }

        // Set default date values on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Leave registration date fields empty by default
        });

        // Form validation
        document.getElementById('searchProfilesForm').addEventListener('submit', function(e) {
            const ageMin = document.querySelector('input[name="age_min"]').value;
            const ageMax = document.querySelector('input[name="age_max"]').value;
            
            if (ageMin && ageMax && parseInt(ageMin) > parseInt(ageMax)) {
                e.preventDefault();
                alert('Minimum age cannot be greater than maximum age');
                return;
            }

            const heightMin = document.querySelector('input[name="height_min"]').value;
            const heightMax = document.querySelector('input[name="height_max"]').value;
            
            if (heightMin && heightMax && parseInt(heightMin) > parseInt(heightMax)) {
                e.preventDefault();
                alert('Minimum height cannot be greater than maximum height');
                return;
            }

            const weightMin = document.querySelector('input[name="weight_min"]').value;
            const weightMax = document.querySelector('input[name="weight_max"]').value;
            
            if (weightMin && weightMax && parseInt(weightMin) > parseInt(weightMax)) {
                e.preventDefault();
                alert('Minimum weight cannot be greater than maximum weight');
                return;
            }

            const regFrom = document.querySelector('input[name="registered_from"]').value;
            const regTo = document.querySelector('input[name="registered_to"]').value;
            
            if (regFrom && regTo && new Date(regFrom) > new Date(regTo)) {
                e.preventDefault();
                alert('Registration "from" date cannot be later than "to" date');
                return;
            }
        });

        // Print profile function
        function printProfile(userId, name, code) {
            // Create a new window for printing
            const printWindow = window.open('', '_blank');
            
            // Get the profile data from the current card
            const profileCard = event.target.closest('.profile-card');
            const profileDetails = profileCard.querySelector('.profile-details').innerHTML;
            const photoContainer = profileCard.querySelector('.profile-photo-container');
            let photoHtml = '';
            
            if (photoContainer) {
                const img = photoContainer.querySelector('img');
                if (img) {
                    photoHtml = `<img src="${img.src}" alt="${name}'s photo" style="width: 150px; height: 150px; border-radius: 15px; object-fit: cover; border: 3px solid #ac0742;">`;
                } else {
                    photoHtml = `<div style="width: 150px; height: 150px; border-radius: 15px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; border: 3px solid #dee2e6; color: #6c757d;"><span>No Photo Available</span></div>`;
                }
            }
            
            // Create the print content
            const printContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Profile - ${name} (${code})</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        .print-header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #ac0742; padding-bottom: 15px; }
                        .print-header h1 { color: #ac0742; margin: 0; }
                        .print-header p { margin: 5px 0; color: #666; }
                        .print-content { display: flex; gap: 30px; align-items: flex-start; }
                        .print-details { flex: 1; }
                        .print-photo { flex-shrink: 0; }
                        .profile-field { margin-bottom: 10px; font-size: 14px; }
                        .profile-field strong { color: #333; margin-right: 10px; }
                        @media print { body { margin: 0; } }
                    </style>
                </head>
                <body>
                    <div class="print-header">
                        <h1>${name}</h1>
                        <p>Profile ID: ${code}</p>
                        <p>Printed on: ${new Date().toLocaleString()}</p>
                    </div>
                    <div class="print-content">
                        <div class="print-details">
                            ${profileDetails}
                        </div>
                        <div class="print-photo">
                            ${photoHtml}
                        </div>
                    </div>
                </body>
                </html>
            `;
            
            // Write content to the new window and print
            printWindow.document.write(printContent);
            printWindow.document.close();
            
            // Wait for the content to load, then print
            printWindow.onload = function() {
                printWindow.print();
                printWindow.close();
            };
        }

        // Shortlist profile function
        function shortlistProfile(userId) {
            if (confirm('Are you sure you want to add this profile to shortlist?')) {
                // Here you can implement AJAX call to add to shortlist
                // For now, just show success message
                alert('Profile added to shortlist successfully!');
                
                // You can make an AJAX call here to save to database
                // fetch('/add-to-shortlist', {
                //     method: 'POST',
                //     headers: {
                //         'Content-Type': 'application/json',
                //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                //     },
                //     body: JSON.stringify({user_id: userId})
                // }).then(response => response.json()).then(data => {
                //     if (data.success) {
                //         alert('Profile added to shortlist successfully!');
                //     }
                // });
            }
        }
    </script>
</body>
</html>