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
            <div class="profile-info" data-profile-id="{{ $service->profile_id ?? $service->id ?? 'UNKNOWN' }}">
                Profile ID: {{ $service->profile_id ?? $service->id ?? 'UNKNOWN' }}
            </div>
        </div>

        <div class="prospects-container">
            <div class="table-container">
                <table class="prospects-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Prospect ID</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Education</th>
                            <th>Occupation</th>
                            <th>Location</th>
                            <th>Contact Date</th>
                            <th>Status</th>
                            <th>Customer Reply</th>
                            <th>Actions</th>
                            <th>Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>07-Mar-2025</td>
                            <td class="prospect-id">PR001</td>
                            <td class="prospect-name">Priya Sharma</td>
                            <td>28</td>
                            <td>MBA</td>
                            <td>Software Engineer</td>
                            <td>Bangalore</td>
                            <td>07-Mar-2025</td>
                            <td><span class="status-badge status-interested">Interested</span></td>
                            <td class="customer-reply">Left voicemail</td>
                            <td><a href="#" class="action-btn">✏️ Edit</a></td>
                            <td class="remark-cell"><button class="remark-btn" data-prospect="PR001" title="Add remark">📝</button></td>
                        </tr>
                        <tr>
                            <td>05-Jul-2025</td>
                            <td class="prospect-id">PR002</td>
                            <td class="prospect-name">Anjali Kumar</td>
                            <td>26</td>
                            <td>B.Tech</td>
                            <td>Data Analyst</td>
                            <td>Chennai</td>
                            <td>05-Jul-2025</td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                            <td class="customer-reply">No answer</td>
                            <td><a href="#" class="action-btn">✏️ Edit</a></td>
                            <td class="remark-cell"><button class="remark-btn" data-prospect="PR002" title="Add remark">📝</button></td>
                        </tr>
                        <tr>
                            <td>05-Jul-2025</td>
                            <td class="prospect-id">PR003</td>
                            <td class="prospect-name">Meera Nair</td>
                            <td>29</td>
                            <td>M.Com</td>
                            <td>Accountant</td>
                            <td>Kochi</td>
                            <td>05-Jul-2025</td>
                            <td><span class="status-badge status-contacted">Contacted</span></td>
                            <td class="customer-reply">Interested - follow up</td>
                            <td><a href="#" class="action-btn">✏️ Edit</a></td>
                            <td class="remark-cell"><button class="remark-btn" data-prospect="PR003" title="Add remark">📝</button></td>
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

        <!-- Remark Modal -->
        <div id="remarkModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:2000;">
            <div style="background:#fff; padding:20px; border-radius:10px; width:90%; max-width:600px; position:relative;">
                <button id="closeRemark" style="position:absolute; right:12px; top:12px; background:transparent; border:none; font-size:18px; cursor:pointer;">✕</button>
                <h3 style="margin-top:0;">Add Remark</h3>
                <p id="remarkProspectId" style="font-weight:600; color:#4a69bd;"></p>
                <textarea id="remarkText" rows="6" style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px; font-size:14px;" placeholder="Type the reason here..."></textarea>
                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:12px;">
                    <button id="saveRemark" class="btn" style="background:#4a69bd; color:#fff;">Save</button>
                    <button id="cancelRemark" class="btn" style="background:#e0e0e0;">Cancel</button>
                </div>
            </div>
        </div>

    <script>
        function refreshProspects() {
            // Reload prospects and shortlists
            loadShortlists();
        }

        // Add animation for table rows
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.prospects-table tbody tr');
            rows.forEach((row, index) => {
                row.style.animationDelay = `${0.3 + (index * 0.1)}s`;
                row.style.animation = 'fadeInUp 0.8s ease-out both';
            });
            // Remark modal logic
            const remarkModal = document.getElementById('remarkModal');
            const remarkText = document.getElementById('remarkText');
            const remarkProspectId = document.getElementById('remarkProspectId');
            const closeRemark = document.getElementById('closeRemark');
            const saveRemark = document.getElementById('saveRemark');
            const cancelRemark = document.getElementById('cancelRemark');

            document.querySelectorAll('.remark-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const pid = this.getAttribute('data-prospect');
                    remarkProspectId.textContent = pid;
                    remarkText.value = this.closest('tr').querySelector('.remark-value') ? this.closest('tr').querySelector('.remark-value').textContent : '';
                    remarkModal.style.display = 'flex';
                });
            });

            function closeRemarkModal() {
                remarkModal.style.display = 'none';
            }
            if (closeRemark) closeRemark.addEventListener('click', closeRemarkModal);
            if (cancelRemark) cancelRemark.addEventListener('click', closeRemarkModal);

            saveRemark.addEventListener('click', function() {
                const pid = remarkProspectId.textContent;
                const text = remarkText.value.trim();
                // Store remark in the row (client-side). In production, you'd POST to server.
                const row = Array.from(document.querySelectorAll('.prospects-table tbody tr')).find(r => r.querySelector('.prospect-id') && r.querySelector('.prospect-id').textContent === pid);
                if (row) {
                    let cell = row.querySelector('.remark-value');
                    if (!cell) {
                        cell = document.createElement('div');
                        cell.className = 'remark-value';
                        row.querySelector('.remark-cell').appendChild(cell);
                    }
                    cell.textContent = text;
                }
                closeRemarkModal();
            });

            // Load shortlists for this profile
            function loadShortlists() {
                // use profile id passed from the server to avoid parsing UI text
                const profileInfoEl = document.querySelector('.profile-info');
                let profileId = profileInfoEl ? (profileInfoEl.dataset.profileId || profileInfoEl.textContent.replace('Profile ID:','').trim()) : '';

                // Fallback: if the server rendered no profile id (UNKNOWN/empty), try using
                // the last URL segment which is the {serviceId} route param. The controller
                // will resolve a numeric service id to the actual profile_id when possible.
                if (!profileId || profileId.toUpperCase() === 'UNKNOWN') {
                    const path = window.location.pathname.replace(/\/$/, '');
                    const parts = path.split('/').filter(Boolean);
                    profileId = parts.length ? parts[parts.length - 1] : profileId;
                    console.warn('[view-prospects] profileId missing; falling back to URL segment ->', profileId);
                } else {
                    console.debug('[view-prospects] resolved profileId ->', profileId);
                }

                fetch('/shortlists/' + encodeURIComponent(profileId), { 
                    credentials: 'same-origin',
                    headers: { 'Accept': 'application/json' }
                })
                    .then(response => {
                        console.debug('[view-prospects] fetch status', response.status, 'content-type:', response.headers.get('content-type'));
                        // If server redirected to login page or returned HTML, content-type won't be JSON
                        const contentType = response.headers.get('content-type') || '';
                        if (!response.ok) {
                            // show friendly message when unauthorized
                            if (response.status === 401 || response.status === 403) {
                                throw new Error('Unauthorized. Please login to view shortlists.');
                            }
                            throw new Error('Server returned status ' + response.status);
                        }

                        if (!contentType.includes('application/json')) {
                            throw new Error('Unexpected non-JSON response - likely redirected to login.');
                        }

                        return response.json();
                    })
                    .then(resp => {
                        if (!resp || !resp.success) {
                            console.warn('Shortlists endpoint returned no success', resp);
                            return;
                        }
                        const data = resp.data || [];
                        const tbody = document.querySelector('.prospects-table tbody');
                        if (!tbody) return;
                        // remove previously injected shortlist rows to avoid duplicates
                        document.querySelectorAll('.prospects-table tbody tr.shortlist-row').forEach(r => r.remove());

                        if (data.length === 0) {
                            // show a friendly row when nothing is found
                            const emptyRow = document.createElement('tr');
                            emptyRow.classList.add('shortlist-row');
                            emptyRow.innerHTML = `<td colspan="12" style="text-align:center; padding:30px; color:#666;">No shortlisted prospects found for this profile.</td>`;
                            tbody.insertBefore(emptyRow, tbody.firstChild);
                        }

                        data.forEach(item => {
                            const tr = document.createElement('tr');
                            tr.classList.add('shortlist-row');
                            tr.innerHTML = `
                                <td>${new Date(item.created_at).toLocaleDateString()}</td>
                                <td class="prospect-id">${item.prospect_id || item.id}</td>
                                <td class="prospect-name">${item.prospect_name || item.prospect_id || 'Unknown'}</td>
                                <td>${item.prospect_age || ''}</td>
                                <td>${item.prospect_education || ''}</td>
                                <td>${item.prospect_occupation || ''}</td>
                                <td>${item.prospect_location || ''}</td>
                                <td>${item.contact_date ? new Date(item.contact_date).toLocaleDateString() : ''}</td>
                                <td><span class="status-badge status-pending">${item.status || 'new'}</span></td>
                                <td class="customer-reply">${item.customer_reply || ''}</td>
                                <td><a href="#" class="action-btn" onclick="editShortlist(${item.id})">✏️ Edit</a></td>
                                <td class="remark-cell"><button class="remark-btn" data-prospect="${item.prospect_id || item.id}" title="Add remark">📝</button></td>
                            `;
                            // prepend so newest appear on top
                            tbody.insertBefore(tr, tbody.firstChild);
                        });
                    }).catch(err => console.warn('Could not load shortlists', err));
            }

            // initial load
            loadShortlists();
        });
    </script>
</body>
</html>