<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>INA Dashboard - Assign Profile from Other Sources</title>
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
            max-width: 600px;
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
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, #4a69bd, #5a4fcf);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #555;
            font-size: 14px;
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            background: white;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: #4a69bd;
            box-shadow: 0 0 0 3px rgba(74, 105, 189, 0.1);
        }

        .form-select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 20px;
            padding-right: 50px;
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .date-input input[type="date"] {
            color: #666;
        }

        .date-input input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            opacity: 0.6;
        }

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 30px;
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

        .btn-submit {
            background: linear-gradient(135deg, #48CAE4, #0096C7);
            color: white;
            box-shadow: 0 5px 15px rgba(72, 202, 228, 0.4);
        }

        .btn-submit:hover {
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
            text-decoration: none;
            color: white;
        }

        .required {
            color: #e74c3c;
        }

        .error-message {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        .success-message {
            background: linear-gradient(135deg, #A8E6CF, #7FCDCD);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            display: none;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 30px 20px;
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
    <div class="form-container">
        <h1 class="form-title">Assign Profile from Other Sources</h1>
        
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

        <form id="assignProfileForm" method="POST" action="{{ route('assign.profile') }}">
            @csrf
            
            <div class="form-group">
                <label class="form-label">ProfileId <span class="required">*</span></label>
                <input type="text" name="profile_id" class="form-input" placeholder="36584" required>
                <div class="error-message" id="profileIdError">Profile ID is required</div>
            </div>

            <div class="form-group">
                <label class="form-label">Other Site Member ID <span class="required">*</span></label>
                <input type="text" name="other_site_member_id" class="form-input" placeholder="Enter Member ID" required>
                <div class="error-message" id="memberIdError">Member ID is required</div>
            </div>

            <div class="form-group">
                <label class="form-label">Profile Source <span class="required">*</span></label>
                <select name="profile_source" class="form-select" required>
                    <option value="">-- Select Profile Source --</option>
                    <option value="PUNYAHLA">PUNYAHLA</option>
                    <option value="BHARATMATRIMONY">BHARATMATRIMONY</option>
                    <option value="JEEVANSATHI">JEEVANSATHI</option>
                    <option value="SHAADI">SHAADI</option>
                    <option value="MATRIMONY">MATRIMONY</option>
                    <option value="KERALAMATRIMONY">KERALAMATRIMONY</option>
                    <option value="OTHER">OTHER</option>
                </select>
                <div class="error-message" id="sourceError">Profile source is required</div>
            </div>

            <div class="form-group">
                <label class="form-label">Service Date <span class="required">*</span></label>
                <div class="date-input">
                    <input type="date" name="service_date" class="form-input" required>
                </div>
                <div class="error-message" id="dateError">Service date is required</div>
            </div>

            <div class="form-group">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-input" placeholder="Enter full name">
            </div>

            <div class="form-group">
                <label class="form-label">Contact Numbers</label>
                <input type="text" name="contact_numbers" class="form-input" placeholder="Enter contact numbers (comma separated)">
            </div>

            <div class="form-group">
                <label class="form-label">Remarks</label>
                <textarea name="remarks" class="form-textarea" placeholder="Enter any additional remarks or notes..."></textarea>
            </div>

            <div class="button-group">
                <button type="submit" class="btn btn-submit">Submit</button>
                <button type="button" class="btn btn-clear" onclick="clearForm()">Clear</button>
                <a href="{{ route('active.service') }}" class="btn btn-back">Back</a>
            </div>
        </form>
    </div>

    <script>
        function clearForm() {
            document.getElementById('assignProfileForm').reset();
            hideAllErrors();
            hideSuccessMessage();
        }

        function hideAllErrors() {
            const errorMessages = document.querySelectorAll('.error-message');
            errorMessages.forEach(error => error.style.display = 'none');
        }

        function showError(elementId, message) {
            const errorElement = document.getElementById(elementId);
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.style.display = 'block';
            }
        }

        function hideSuccessMessage() {
            document.getElementById('successMessage').style.display = 'none';
        }

        function showSuccessMessage() {
            document.getElementById('successMessage').style.display = 'block';
            setTimeout(() => {
                hideSuccessMessage();
            }, 5000);
        }

        // Set today's date as default
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.querySelector('input[name="service_date"]').value = today;
        });

        // Form validation
        document.getElementById('assignProfileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            hideAllErrors();

            let isValid = true;

            // Validate Profile ID
            const profileId = document.querySelector('input[name="profile_id"]').value.trim();
            if (!profileId) {
                showError('profileIdError', 'Profile ID is required');
                isValid = false;
            }

            // Validate Member ID
            const memberId = document.querySelector('input[name="other_site_member_id"]').value.trim();
            if (!memberId) {
                showError('memberIdError', 'Other Site Member ID is required');
                isValid = false;
            }

            // Validate Profile Source
            const profileSource = document.querySelector('select[name="profile_source"]').value;
            if (!profileSource) {
                showError('sourceError', 'Profile source is required');
                isValid = false;
            }

            // Validate Service Date
            const serviceDate = document.querySelector('input[name="service_date"]').value;
            if (!serviceDate) {
                showError('dateError', 'Service date is required');
                isValid = false;
            }

            // Validate contact numbers format (if provided)
            const contactNumbers = document.querySelector('input[name="contact_numbers"]').value.trim();
            if (contactNumbers) {
                const phoneRegex = /^[\d\s,+-]+$/;
                if (!phoneRegex.test(contactNumbers)) {
                    showError('contactError', 'Please enter valid contact numbers');
                    isValid = false;
                }
            }

            if (isValid) {
                // Simulate form submission (replace with actual submission)
                showSuccessMessage();
                
                // Uncomment the line below for actual form submission
                // this.submit();
            }
        });

        // Real-time validation
        document.querySelector('input[name="profile_id"]').addEventListener('blur', function() {
            if (!this.value.trim()) {
                showError('profileIdError', 'Profile ID is required');
            } else {
                document.getElementById('profileIdError').style.display = 'none';
            }
        });

        document.querySelector('input[name="other_site_member_id"]').addEventListener('blur', function() {
            if (!this.value.trim()) {
                showError('memberIdError', 'Other Site Member ID is required');
            } else {
                document.getElementById('memberIdError').style.display = 'none';
            }
        });

        document.querySelector('select[name="profile_source"]').addEventListener('change', function() {
            if (!this.value) {
                showError('sourceError', 'Profile source is required');
            } else {
                document.getElementById('sourceError').style.display = 'none';
            }
        });

        document.querySelector('input[name="service_date"]').addEventListener('change', function() {
            if (!this.value) {
                showError('dateError', 'Service date is required');
            } else {
                document.getElementById('dateError').style.display = 'none';
            }
        });
    </script>
</body>
</html>