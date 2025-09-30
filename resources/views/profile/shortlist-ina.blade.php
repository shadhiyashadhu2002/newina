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

                <!-- Caste -->
                <div class="form-group">
                    <label class="form-label">Caste:</label>
                    <select name="caste" class="form-select">
                        <option value="">-- Select Caste --</option>
                        <option value="general">General</option>
                        <option value="obc">OBC</option>
                        <option value="sc">SC</option>
                        <option value="st">ST</option>
                        <option value="other">Other</option>
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
                        <input type="date" name="registered_from" class="form-input" value="2025-09-24">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Registered To:</label>
                    <div class="date-input">
                        <input type="date" name="registered_to" class="form-input" value="2025-09-24">
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
    </div>

    <script>
        function clearForm() {
            document.getElementById('searchProfilesForm').reset();
            
            // Reset date fields to default values
            const today = new Date().toISOString().split('T')[0];
            document.querySelector('input[name="registered_from"]').value = today;
            document.querySelector('input[name="registered_to"]').value = today;
        }

        // Set default date values on page load
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.querySelector('input[name="registered_from"]').value = today;
            document.querySelector('input[name="registered_to"]').value = today;
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
    </script>
</body>
</html>