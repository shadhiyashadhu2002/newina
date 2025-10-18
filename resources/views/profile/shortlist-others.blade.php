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

        /* When profile id is taken/auto-filled give it a dimmed appearance but keep it editable */
        .form-input.taken {
            background: #e9ecef;
            color: #333;
            opacity: 0.85;
            box-shadow: inset 0 1px 0 rgba(0,0,0,0.02);
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
            <!-- Informational banner: where to find shortlisted profiles -->
            <div id="shortlist-info-banner" style="background:#fff3cd;color:#856404;padding:12px;border-radius:8px;margin:12px 0;border:1px solid #ffeeba;">
                Profiles added to shortlist will appear on the <strong>View Prospects</strong> page.
                <a id="shortlist-info-link" href="#" style="color:#856404;text-decoration:underline;margin-left:8px;">Open View Prospects</a>
            </div>
            
            <form id="assignProfileForm">
                <div class="form-group">
                    <label class="form-label">ProfileId <span class="required">*</span></label>
                    {{-- Prefill with service->profile_id or service->id when present so assigned profiles map to the requested service --}}
                    <input type="text" name="profile_id" class="form-input" placeholder="36584" required readonly onkeydown="return false;" onpaste="return false;" style="background:#e9ecef;" value="{{ $service->profile_id ?? $service->id ?? '' }}">
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

        // Auto-generate profile id if empty, visually mark it as taken (dimmed) but keep it editable
        (function autoFillProfileId() {
            const pidInput = document.querySelector('input[name="profile_id"]');
            if (!pidInput) return;
            function markTaken() {
                pidInput.classList.add('taken');
                pidInput.setAttribute('data-auto-generated', '1');
            }
            function clearTaken() {
                pidInput.classList.remove('taken');
                pidInput.removeAttribute('data-auto-generated');
            }

            if (!pidInput.value || pidInput.value.trim() === '') {
                fetch('/generate-profile-id', { credentials: 'same-origin' })
                    .then(resp => resp.json())
                    .then(data => {
                        if (data && data.profile_id) {
                            pidInput.value = data.profile_id;
                            markTaken();
                        }
                    }).catch(err => console.warn('Could not generate profile id:', err));
            } else {
                // Already has a value — visually mark as taken
                markTaken();
            }
        })();

        document.getElementById('assignProfileForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const pidInput = document.querySelector('input[name="profile_id"]');
            const pidVal = pidInput ? pidInput.value : '';

            // Build FormData and POST to server so the profile is persisted immediately
            const form = new FormData();
            form.append('profile_id', pidVal);
            form.append('section', 'others');
            form.append('other_site_member_id', document.querySelector('input[name="other_site_member_id"]').value);
            form.append('profile_source', document.querySelector('select[name="profile_source"]').value);
            form.append('start_date', document.querySelector('input[name="service_date"]').value);
            const nm = document.querySelector('input[name="name"]').value;
            if (nm) form.append('member_name', nm);
            const cn = document.querySelector('input[name="contact_numbers"]').value;
            if (cn) form.append('contact_numbers', cn);
            const rm = document.querySelector('textarea[name="remarks"]').value;
            if (rm) form.append('remarks', rm);

            fetch('/save-service', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: form
            }).then(r => r.json())
            .then(resp => {
                if (!resp) throw new Error('No response from server');
                if (resp.success) {
                    // server should return profile_id (may be generated)
                    const serverPid = resp.profile_id || resp.data && resp.data.profile_id || pidVal;
                    const profileData = {
                        profile_id: serverPid,
                        other_site_member_id: form.get('other_site_member_id'),
                        profile_source: form.get('profile_source'),
                        name: form.get('member_name') || 'Not specified',
                        service_date: form.get('start_date') || '',
                        contact_numbers: form.get('contact_numbers') || 'Not specified',
                        remarks: form.get('remarks') || 'No remarks'
                    };

                    addProfileToResults(profileData);

                    // reset the form and get a new profile id for the next entry
                    this.reset();
                    document.querySelector('input[name="service_date"]').value = today;
                    fetch('/generate-profile-id', { credentials: 'same-origin' })
                        .then(r => r.json())
                        .then(data => {
                            if (data && data.profile_id) {
                                if (pidInput) {
                                    pidInput.value = data.profile_id;
                                    pidInput.classList.add('taken');
                                    pidInput.setAttribute('data-auto-generated','1');
                                }
                            }
                        }).catch(err => console.warn('Could not refresh profile id:', err));
                } else {
                    alert('Failed to save profile: ' + (resp.message || 'Unknown error'));
                }
            }).catch(err => {
                console.error('Save failed', err);
                alert('Error saving profile');
            });
        });

        function addProfileToResults(profileData) {
            const resultsContainer = document.getElementById('resultsContainer');
            const resultsGrid = document.getElementById('resultsGrid');

            // If a card for this profile_id already exists, don't add a duplicate
            if (profileData.profile_id) {
                const existing = document.querySelector(`.profile-card[data-profile-id='${profileData.profile_id}']`);
                if (existing) {
                    // bring existing into view and return
                    existing.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return;
                }
            }

            const profileCard = document.createElement('div');
            profileCard.className = 'profile-card';
            if (profileData.profile_id) profileCard.setAttribute('data-profile-id', profileData.profile_id);
            profileCard.innerHTML = `
                <div class="profile-header">
                    <div class="header-left">
                        <h3>${profileData.name}</h3>
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
                            ${profileData.photo_url ? `<img src="${profileData.photo_url}" alt="Profile photo" class="profile-photo">` : `<div class="photo-placeholder"><span style="font-size: 50px; line-height: 1;">📷</span><span>Add Photo</span></div>` }
                        </div>
                        <input type="file" class="file-input file-input-${profileData.profile_id}" accept="image/*" onchange="handlePhotoUpload(this, '${profileData.profile_id}')">
                    </div>
                </div>

                <div class="profile-actions">
                    <button class="btn-small btn-view-details" onclick="viewProfileDetails('${profileData.profile_id}', ${JSON.stringify(profileData).replace(/'/g, "&apos;")})">View Details</button>
                    <button class="btn-small btn-save-profile" onclick="saveProfile('${profileData.profile_id}')">Save Profile</button>
                    <button class="btn-small btn-shortlist" onclick="shortlistAssigned(this)">📌 Shortlist</button>
                </div>
            `;

            // store structured data on the card element for later use by shortlistAssigned
            profileCard.dataset.profileId = profileData.profile_id || '';
            profileCard.dataset.memberName = profileData.name || '';
            profileCard.dataset.otherSiteMemberId = profileData.other_site_member_id || '';
            profileCard.dataset.profileSource = profileData.profile_source || '';
            profileCard.dataset.contactNumbers = profileData.contact_numbers || '';

            resultsGrid.appendChild(profileCard);
            resultsContainer.style.display = 'block';
            resultsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function handlePhotoUpload(input, profileId) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                // show immediate preview using FileReader
                const reader = new FileReader();
                reader.onload = function(e) {
                    const photoArea = input.closest('.photo-section').querySelector('.photo-upload-area');
                    if (photoArea) {
                        photoArea.classList.add('has-photo');
                        photoArea.innerHTML = `<img src="${e.target.result}" alt="Profile photo" class="profile-photo">`;
                    }
                };
                reader.readAsDataURL(file);

                // Immediately upload the file to the server so it's persisted
                const uploadForm = new FormData();
                uploadForm.append('photo', file, file.name);

                fetch('/upload-photo', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: uploadForm
                }).then(r => r.json()).then(resp => {
                    if (resp && resp.success) {
                        // store upload id on the input for later linking when saving profile
                        input._uploadId = resp.upload_id;
                        input._uploadedUrl = resp.url;
                        // Also set a dataset attribute so it survives DOM manipulations
                        try { input.dataset.uploadId = resp.upload_id; } catch(e) { /* ignore */ }
                        // replace preview with the saved URL (in case server normalized/optimized)
                        const photoArea = input.closest('.photo-section').querySelector('.photo-upload-area');
                        if (photoArea && resp.url) {
                            photoArea.classList.add('has-photo');
                            photoArea.innerHTML = `<img src="${resp.url}" alt="Profile photo" class="profile-photo">`;
                        }
                    } else {
                        console.warn('Upload failed', resp && resp.message);
                    }
                }).catch(err => {
                    console.error('Upload error', err);
                });
            }
        }

        // View details (without showing profile id)
        function viewProfileDetails(profileId, profileData) {
            const details = `
Name: ${profileData.name}
Member ID: ${profileData.other_site_member_id}
Profile Source: ${profileData.profile_source}
Service Date: ${profileData.service_date}
Contact Numbers: ${profileData.contact_numbers}
Remarks: ${profileData.remarks}
            `;
            alert(details);
        }

        // Save profile to backend (calls saveSection endpoint)
        function saveProfile(profileId) {
            const fileInput = document.querySelector(`.file-input-${profileId}`);
            const photoData = fileInput ? (fileInput.dataset.photoData || null) : null;

            if (!confirm('Are you sure you want to save this profile to the system?')) return;

            // Find the profile card and extract displayed fields
            const card = fileInput ? fileInput.closest('.profile-card') : null;
            const memberName = card ? (card.querySelector('.profile-field strong') ? card.querySelector('.profile-field').innerText.replace('Name:', '').trim() : '') : '';
            const otherSiteMemberId = card ? (card.querySelector('.profile-field:nth-of-type(2)') ? card.querySelector('.profile-field:nth-of-type(2)').innerText.replace('Member ID:', '').trim() : '') : '';
            const profileSource = card ? (card.querySelectorAll('.profile-field')[2] ? card.querySelectorAll('.profile-field')[2].innerText.replace('Profile Source:', '').trim() : '') : '';
            const serviceDate = card ? (card.querySelectorAll('.profile-field')[3] ? card.querySelectorAll('.profile-field')[3].innerText.replace('Service Date:', '').trim() : '') : '';
            const contactNumbers = card ? (card.querySelectorAll('.profile-field')[4] ? card.querySelectorAll('.profile-field')[4].innerText.replace('Contact Numbers:', '').trim() : '') : '';
            const remarks = card ? (card.querySelectorAll('.profile-field')[5] ? card.querySelectorAll('.profile-field')[5].innerText.replace('Remarks:', '').trim() : '') : '';

            const form = new FormData();
            form.append('profile_id', profileId);
            form.append('section', 'others');
            if (memberName) form.append('member_name', memberName);
            if (otherSiteMemberId) form.append('other_site_member_id', otherSiteMemberId);
            if (profileSource) form.append('profile_source', profileSource);
            if (serviceDate) form.append('start_date', serviceDate);
            if (contactNumbers) form.append('contact_numbers', contactNumbers);
            if (remarks) form.append('remarks', remarks);
            // If the file input has a selected File object, append it to FormData
            if (fileInput && fileInput._selectedFile) {
                form.append('photo', fileInput._selectedFile, fileInput._selectedFile.name);
            }
            // If we already uploaded immediately, include the upload id to link instead of re-uploading
            const uploadId = fileInput && (fileInput._uploadId || fileInput.dataset && fileInput.dataset.uploadId) ? (fileInput._uploadId || fileInput.dataset.uploadId) : null;
            if (uploadId) {
                form.append('photo_id', uploadId);
            }

            fetch('/save-service', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: form
            }).then(res => res.json())
            .then(resp => {
                if (resp && resp.success) {
                    alert('Profile saved successfully');

                    // if server generated/normalized profile_id, update the card to use the returned id
                    const newPid = resp.profile_id || (resp.data && resp.data.profile_id) || profileId;
                    if (newPid && newPid !== profileId) {
                        updateCardProfileId(profileId, newPid);
                    }
                    // Also add to shortlist automatically for 'others' source
                    try {
                        fetch('/add-to-shortlist', {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                profile_id: newPid,
                                prospect_id: otherSiteMemberId || null,
                                source: 'others',
                                prospect_name: memberName || null,
                                prospect_contact: contactNumbers || null
                            })
                        }).then(r2 => r2.json()).then(sresp => {
                            if (sresp && sresp.success) {
                                const dest = (sresp.shortlist && sresp.shortlist.profile_id) || newPid || profileId || (resp && resp.profile_id) || '';
                                showShortlistNotice(dest);
                                // After adding to shortlist, navigate to view-prospects so the user sees it immediately
                                if (dest) {
                                    window.location.href = '/view-prospects/' + encodeURIComponent(dest);
                                }
                            }
                        }).catch(e => console.warn('Could not add to shortlist', e));
                    } catch(e) {
                        console.warn('Auto-shortlist skipped', e);
                    }
                } else {
                    alert('Failed to save profile: ' + (resp.message || 'Unknown error'));
                }
            }).catch(err => {
                console.error(err);
                alert('Error saving profile');
            });
        }

        // Update DOM card references when server returns a different profile id
        function updateCardProfileId(oldId, newId) {
            try {
                // Update file-input class
                const oldFile = document.querySelector('.file-input-' + oldId);
                if (oldFile) {
                    oldFile.classList.remove('file-input-' + oldId);
                    oldFile.classList.add('file-input-' + newId);
                }

                // Update photo upload onclick area (closest .photo-upload-area)
                const photoAreas = document.querySelectorAll('.photo-upload-area');
                photoAreas.forEach(area => {
                    const onclick = area.getAttribute('onclick') || '';
                    if (onclick.includes(oldId)) {
                        area.setAttribute('onclick', `document.querySelector('.file-input-${newId}').click()`);
                    }
                });

                // Update save/view buttons which reference the oldId
                const buttons = document.querySelectorAll('.profile-card');
                buttons.forEach(card => {
                    // find view and save buttons inside this card and update their onclicks
                    const viewBtn = card.querySelector('.btn-view-details');
                    if (viewBtn) {
                        const onclick = viewBtn.getAttribute('onclick') || '';
                        if (onclick.includes(oldId)) {
                            // replace occurrences of the old id in the onclick string
                            viewBtn.setAttribute('onclick', onclick.replace(new RegExp(oldId, 'g'), newId));
                        }
                    }
                    const saveBtn = card.querySelector('.btn-save-profile');
                    if (saveBtn) {
                        const onclick = saveBtn.getAttribute('onclick') || '';
                        if (onclick.includes(oldId)) {
                            saveBtn.setAttribute('onclick', onclick.replace(new RegExp(oldId, 'g'), newId));
                        }
                    }
                });
                // Update the profile-card element's data attributes so later actions pick up the new id
                const cardEl = document.querySelector(`.profile-card[data-profile-id='${oldId}']`);
                if (cardEl) {
                    cardEl.dataset.profileId = newId;
                    cardEl.setAttribute('data-profile-id', newId);

                    // Update photo area onclick inside this specific card if present
                    const photoArea = cardEl.querySelector('.photo-upload-area');
                    if (photoArea) {
                        photoArea.setAttribute('onclick', `document.querySelector('.file-input-${newId}').click()`);
                    }
                }
            } catch (err) {
                console.warn('Could not update card profile id', err);
            }
        }

        // Load saved assigned profiles for current user and render them
        function loadAssignedProfiles() {
            fetch('/assigned-profiles', { credentials: 'same-origin' })
                .then(res => res.json())
                .then(resp => {
                    if (!resp) return;
                    const list = resp.data && Array.isArray(resp.data) ? resp.data : (Array.isArray(resp) ? resp : []);
                    list.forEach(svc => {
                        addProfileToResults({
                            profile_id: svc.profile_id,
                            other_site_member_id: svc.other_site_member_id,
                            profile_source: svc.profile_source,
                            name: svc.member_name || svc.service_name || 'Not specified',
                            service_date: svc.start_date || '',
                            contact_numbers: svc.contact_numbers || 'Not specified',
                            remarks: svc.remarks || 'No remarks',
                            photo_url: svc.photo_url || null,
                            has_photo: svc.has_photo || false
                        });
                    });
                }).catch(err => console.error('Failed to load assigned profiles', err));
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadAssignedProfiles();
        });

        function clearForm() {
            document.getElementById('assignProfileForm').reset();
            document.querySelector('input[name="service_date"]').value = today;
        }

        // Shortlist an assigned profile card (manual action)
        function shortlistAssigned(buttonEl) {
            try {
                const card = buttonEl.closest('.profile-card');
                if (!card) return alert('Profile card not found');

                const profileId = card.dataset.profileId || card.getAttribute('data-profile-id') || document.querySelector('input[name="profile_id"]').value;
                const prospectId = card.dataset.otherSiteMemberId || '';
                const prospectName = card.dataset.memberName || '';
                const source = card.dataset.profileSource || 'others';
                const contact = card.dataset.contactNumbers || '';

                if (!confirm('Add this assigned profile to shortlist?')) return;

                fetch('/add-to-shortlist', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        profile_id: profileId,
                        prospect_id: prospectId || null,
                        prospect_name: prospectName || null,
                        prospect_contact: contact || null,
                        source: source
                    })
                }).then(r => r.json()).then(resp => {
                    if (resp && resp.success) {
                        const pid = (resp.shortlist && resp.shortlist.profile_id) || profileId || (card && card.dataset && card.dataset.profileId) || '';
                        showShortlistNotice(pid);
                        if (pid) {
                            window.location.href = '/view-prospects/' + encodeURIComponent(pid);
                        }
                    } else {
                        alert('Failed to add to shortlist: ' + (resp && resp.message ? resp.message : 'Unknown'));
                    }
                }).catch(err => {
                    console.error('Shortlist error', err);
                    alert('Error adding to shortlist');
                });
            } catch (e) {
                console.error(e);
                alert('Unexpected error');
            }
        }

        // show a transient banner with link to view-prospects
        function showShortlistNotice(profileId) {
            try {
                const banner = document.getElementById('shortlist-info-banner');
                const link = document.getElementById('shortlist-info-link');
                if (banner && link) {
                    link.href = '/view-prospects/' + encodeURIComponent(profileId || '');
                    banner.style.display = 'block';
                    const origBg = banner.style.background;
                    banner.style.background = '#d4edda';
                    banner.style.color = '#155724';
                    setTimeout(() => {
                        banner.style.background = origBg;
                        banner.style.color = '';
                    }, 5000);
                } else if (profileId) {
                    window.location.href = '/view-prospects/' + encodeURIComponent(profileId);
                }
            } catch (e) {
                console.warn('Could not show shortlist notice', e);
                if (profileId) window.location.href = '/view-prospects/' + encodeURIComponent(profileId);
            }
        }
    </script>
</body>
</html>