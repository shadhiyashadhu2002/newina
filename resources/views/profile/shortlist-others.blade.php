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
            background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
            color: #333;
            min-height: 100vh;
            padding: 20px;
        }

        .page-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
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
            font-family: inherit;
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

        /* Results Section */
        .results-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            max-width: 1200px;
            width: 100%;
            animation: fadeInUp 0.8s ease-out;
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
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
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
            align-items: flex-start;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .header-left {
            flex: 1;
        }

        .profile-header h3 {
            color: #ac0742;
            font-size: 18px;
            margin: 0 0 5px 0;
        }

        .profile-code {
            background: #ac0742;
            color: white;
            padding: 4px 8px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }

        .profile-content {
            display: flex;
            gap: 20px;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .profile-details {
            flex: 1;
        }

        .profile-field {
            margin-bottom: 8px;
            font-size: 14px;
            line-height: 1.4;
        }

        .profile-field strong {
            color: #555;
            margin-right: 5px;
            min-width: 90px;
            display: inline-block;
        }

        .photo-section {
            flex-shrink: 0;
            text-align: center;
        }

        .photo-upload-area {
            width: 140px;
            height: 140px;
            border: 2px dashed #ac0742;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(172, 7, 66, 0.05), rgba(172, 7, 66, 0.02));
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .photo-upload-area:hover {
            background: linear-gradient(135deg, rgba(172, 7, 66, 0.1), rgba(172, 7, 66, 0.05));
            border-color: #d63384;
        }

        .photo-upload-area.has-photo {
            border: 3px solid #ac0742;
            background: white;
        }

        .photo-placeholder {
            text-align: center;
            color: #ac0742;
        }

        .photo-placeholder i {
            font-size: 40px;
            display: block;
            margin-bottom: 5px;
        }

        .photo-placeholder span {
            font-size: 12px;
            font-weight: 500;
            display: block;
        }

        .profile-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
        }

        .file-input {
            display: none;
        }

        .profile-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            padding-top: 15px;
            border-top: 1px solid #f0f0f0;
        }

        .btn-small {
            flex: 1;
            min-width: 100px;
            padding: 10px 12px;
            border-radius: 8px;
            text-decoration: none;
            text-align: center;
            font-size: 13px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-view-details {
            background: #6c757d;
            color: white;
        }

        .btn-view-details:hover {
            background: #545b62;
        }

        .btn-save-profile {
            background: #ac0742;
            color: white;
        }

        .btn-save-profile:hover {
            background: #8a0535;
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

            .profile-content {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .photo-section {
                order: -1;
                margin-bottom: 15px;
            }

            .results-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="form-container">
            <h1 class="form-title">Assign Profile from Other Sources</h1>
            
            <form id="assignProfileForm">
                <div class="form-group">
                    <label class="form-label">ProfileId <span class="required">*</span></label>
                    <input type="text" name="profile_id" class="form-input" placeholder="36584" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Other Site Member ID <span class="required">*</span></label>
                    <input type="text" name="other_site_member_id" class="form-input" placeholder="Enter Member ID" required>
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
                </div>

                <div class="form-group">
                    <label class="form-label">Service Date <span class="required">*</span></label>
                    <input type="date" name="service_date" class="form-input" required>
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
                    <a href="#" class="btn btn-back">Back</a>
                </div>
            </form>
        </div>

        <!-- Results Section -->
        <div id="resultsContainer" class="results-container" style="display: none;">
            <h2 class="results-title">Assigned Profiles</h2>
            <p class="results-summary">Profile assigned successfully!</p>
            
            <div id="resultsGrid" class="results-grid"></div>
        </div>
    </div>

    <script>
        const today = new Date().toISOString().split('T')[0];
        document.querySelector('input[name="service_date"]').value = today;

        document.getElementById('assignProfileForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = {
                profile_id: document.querySelector('input[name="profile_id"]').value,
                other_site_member_id: document.querySelector('input[name="other_site_member_id"]').value,
                profile_source: document.querySelector('select[name="profile_source"]').value,
                service_date: document.querySelector('input[name="service_date"]').value,
                name: document.querySelector('input[name="name"]').value || 'Not specified',
                contact_numbers: document.querySelector('input[name="contact_numbers"]').value || 'Not specified',
                remarks: document.querySelector('textarea[name="remarks"]').value || 'No remarks'
            };

            addProfileToResults(formData);
            this.reset();
            document.querySelector('input[name="service_date"]').value = today;
        });

        function addProfileToResults(profileData) {
            const resultsContainer = document.getElementById('resultsContainer');
            const resultsGrid = document.getElementById('resultsGrid');

            const profileCard = document.createElement('div');
            profileCard.className = 'profile-card';
            profileCard.innerHTML = `
                <div class="profile-header">
                    <div class="header-left">
                        <h3>${profileData.name}</h3>
                        <span class="profile-code">ID: ${profileData.profile_id}</span>
                    </div>
                </div>

                <div class="profile-content">
                    <div class="profile-details">
                        <div class="profile-field">
                            <strong>Name:</strong> ${profileData.name}
                        </div>
                        <div class="profile-field">
                            <strong>Member ID:</strong> ${profileData.other_site_member_id}
                        </div>
                        <div class="profile-field">
                            <strong>Profile Source:</strong> ${profileData.profile_source}
                        </div>
                        <div class="profile-field">
                            <strong>Service Date:</strong> ${profileData.service_date}
                        </div>
                        <div class="profile-field">
                            <strong>Contact Numbers:</strong> ${profileData.contact_numbers}
                        </div>
                        <div class="profile-field">
                            <strong>Remarks:</strong> ${profileData.remarks}
                        </div>
                    </div>

                    <div class="photo-section">
                        <div class="photo-upload-area" onclick="document.querySelector('.file-input-${profileData.profile_id}').click()">
                            <div class="photo-placeholder">
                                <span style="font-size: 50px; line-height: 1;">📷</span>
                                <span>Add Photo</span>
                            </div>
                        </div>
                        <input type="file" class="file-input file-input-${profileData.profile_id}" accept="image/*" onchange="handlePhotoUpload(this, '${profileData.profile_id}')">
                    </div>
                </div>

                <div class="profile-actions">
                    <button class="btn-small btn-view-details" onclick="viewProfileDetails('${profileData.profile_id}', ${JSON.stringify(profileData).replace(/'/g, "&apos;")})">View Details</button>
                    <button class="btn-small btn-save-profile" onclick="saveProfile('${profileData.profile_id}')">Save Profile</button>
                </div>
            `;

            resultsGrid.appendChild(profileCard);
            resultsContainer.style.display = 'block';
            resultsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function handlePhotoUpload(input, profileId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const photoArea = input.closest('.photo-section').querySelector('.photo-upload-area');
                    photoArea.classList.add('has-photo');
                    photoArea.innerHTML = `<img src="${e.target.result}" alt="Profile photo" class="profile-photo">`;
                    input.dataset.photoData = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function viewProfileDetails(profileId, profileData) {
            const details = `
Profile ID: ${profileData.profile_id}
Name: ${profileData.name}
Member ID: ${profileData.other_site_member_id}
Profile Source: ${profileData.profile_source}
Service Date: ${profileData.service_date}
Contact Numbers: ${profileData.contact_numbers}
Remarks: ${profileData.remarks}
            `;
            alert(details);
        }

        function saveProfile(profileId) {
            const fileInput = document.querySelector(`.file-input-${profileId}`);
            const photoData = fileInput.dataset.photoData || null;
            
            if (confirm('Are you sure you want to save this profile?')) {
                alert(`Profile ${profileId} saved successfully!${photoData ? '\nPhoto has been uploaded.' : '\nNo photo uploaded.'}`);
                
                // Here you can make an AJAX call to save to database
                // fetch('/save-profile', {
                //     method: 'POST',
                //     headers: {
                //         'Content-Type': 'application/json',
                //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                //     },
                //     body: JSON.stringify({
                //         profile_id: profileId,
                //         photo: photoData
                //     })
                // }).then(response => response.json()).then(data => {
                //     if (data.success) {
                //         alert('Profile saved successfully!');
                //     }
                // });
            }
        }

        function clearForm() {
            document.getElementById('assignProfileForm').reset();
            document.querySelector('input[name="service_date"]').value = today;
        }
    </script>
</body>
</html>