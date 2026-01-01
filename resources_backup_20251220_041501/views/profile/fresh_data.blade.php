<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>INA Dashboard - Fresh Data</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
            color: #333;
            min-height: 100vh;
        }
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
        .header-brand { color: white; font-size: 24px; font-weight: 700; text-decoration: none; }
        .header-nav { display: flex; list-style: none; gap: 25px; align-items: center; }
        .header-nav a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            padding: 10px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .header-nav a:hover { color: white; background: rgba(255, 255, 255, 0.1); }
        .header-nav a.active { color: white; background: rgba(255, 255, 255, 0.2); font-weight: 600; }
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
        .main-content { padding: 30px; }
        .page-title { color: #fff; font-size: 28px; font-weight: 600; margin-bottom: 25px; }
        .alert { padding: 12px 20px; margin: 20px 30px; border-radius: 8px; font-weight: 500; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .profiles-section { padding: 30px; }
        .profiles-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header-buttons { display: flex; gap: 10px; }
        .add-profile-btn {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
            font-size: 14px;
            text-decoration: none;
        }
        .import-btn {
            background: linear-gradient(135deg, #f44336, #d32f2f);
            box-shadow: 0 4px 15px rgba(244, 67, 54, 0.3);
        }
        .add-icon { font-size: 16px; font-weight: bold; }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
        }
        .modal.active { display: flex; align-items: center; justify-content: center; }
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.3s ease;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-50px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        .modal-title { font-size: 22px; font-weight: 600; color: #333; }
        .close-btn {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #999;
            line-height: 1;
        }
        .modal-body { margin-bottom: 20px; }
        .upload-area {
            border: 2px dashed #ddd;
            border-radius: 10px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: #f9f9f9;
        }
        .upload-area:hover { border-color: #f44336; background: #fff5f5; }
        .upload-icon { font-size: 48px; margin-bottom: 10px; }
        .upload-text { font-size: 16px; color: #666; margin-bottom: 5px; }
        .upload-hint { font-size: 13px; color: #999; }
        .file-input { display: none; }
        .selected-file {
            display: none;
            margin-top: 15px;
            padding: 12px;
            background: #e8f5e9;
            border-radius: 8px;
            align-items: center;
            gap: 10px;
        }
        .selected-file.active { display: flex; }
        .file-icon { font-size: 24px; }
        .file-info { flex: 1; }
        .file-name { font-weight: 600; color: #333; margin-bottom: 3px; }
        .file-size { font-size: 12px; color: #666; }
        .remove-file-btn {
            background: #f44336;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
        }
        .template-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #f44336;
            text-decoration: none;
            font-weight: 500;
        }
        .modal-footer { display: flex; gap: 10px; justify-content: flex-end; }
        .btn { padding: 10px 20px; border: none; border-radius: 8px; cursor: pointer; font-weight: 500; }
        .btn-cancel { background: #f0f0f0; color: #666; }
        .btn-import {
            background: linear-gradient(135deg, #f44336, #d32f2f);
            color: white;
            box-shadow: 0 4px 15px rgba(244, 67, 54, 0.3);
        }
        .btn-import:disabled { background: #ccc; cursor: not-allowed; box-shadow: none; }
        .progress-container { display: none; margin-top: 15px; }
        .progress-container.active { display: block; }
        .progress-bar { height: 6px; background: #f0f0f0; border-radius: 10px; overflow: hidden; }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #4CAF50, #45a049);
            width: 0%;
            transition: width 0.3s;
        }
        .progress-text { text-align: center; margin-top: 8px; font-size: 13px; color: #666; }

        .data-table-section { background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); overflow: hidden; }
        .profiles-table { width: 100%; border-collapse: collapse; background: white; }
        .profiles-table th {
            background: #f8f9fa;
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #dee2e6;
        }
        .profiles-table td { padding: 12px; border-bottom: 1px solid #dee2e6; color: #555; }
        .profiles-table tbody tr:nth-child(even) { background: #f8f9fa; }
        .profiles-table tbody tr:hover { background: #e0f2fe; }
        .actions { display: flex; gap: 5px; justify-content: center; }
        .action-btn {
            padding: 8px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            min-width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }
        .edit-btn { background: #FFC107; color: #fff; }
        .view-btn { background: #17A2B8; color: white; }
        .delete-btn { background: #DC3545; color: white; }
        .history { background: #6c757d; color: white; }

    /* Assign Modal */
    .assign-modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(5px);
    }
    .assign-modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .assign-modal-content {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    }
    .assign-modal-header {
        padding: 25px;
        color: white;
    }
    .assign-modal-header h2 {
        margin: 0;
        font-size: 24px;
    }
    .assign-modal-body {
        padding: 30px;
        background: white;
    }
    .assign-form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
    }
    .assign-form-group select {
        width: 100%;
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
    }
    .assign-modal-footer {
        padding: 20px 30px;
        background: white;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        border-radius: 0 0 15px 15px;
    }
    .assign-cancel-btn, .assign-submit-btn {
        padding: 10px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
    }
    .assign-cancel-btn {
        background: #f0f0f0;
        color: #666;
    }
    .assign-submit-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    </style>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <header class="main-header">
        <a href="#" class="header-brand">INA</a>
        <nav>
            <ul class="header-nav">
                <li><a href="{{ route('dashboard') }}">Home</a></li>
                <li><a href="{{ route('profile.hellow') }}">Profiles</a></li>
                <li><a href="{{ route('fresh.data') }}" class="active">Fresh Data</a></li>
                <li><a href="{{ route('services.page') }}">Services</a></li>
            </ul>
        </nav>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
        <button class="logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</button>
    </header>

    <main class="main-content">
        <h1 class="page-title">Fresh Data</h1>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="profiles-section">
            <div class="profiles-header">
                <div class="header-buttons">
                    @if(isset($source) && $source === 'database')
                        <a href="{{ route('fresh.data.index') }}" class="add-profile-btn">
                            <span class="add-icon">←</span> Back to Fresh Data
                        </a>
                        <a href="{{ route('fresh.data.export_users') }}" class="add-profile-btn import-btn">
                            <span class="add-icon">⬇️</span> Export Users
                        </a>
                        <button class="add-profile-btn assign-btn" onclick="openBulkAssignModal()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <span class="add-icon">👤</span> Assign To
                        </button>
                    @else
                        <a href="{{ route('add.fresh.data') }}" class="add-profile-btn">
                            <span class="add-icon">+</span> Add New Data
                        </a>
                        <button class="add-profile-btn import-btn" onclick="openImportModal()">
                            <span class="add-icon">📊</span> Import from Excel
                        </button>
                        <button class="add-profile-btn assign-btn" onclick="openBulkAssignModal()" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <span class="add-icon">👤</span> Assign To
                        </button>
                    @endif
                </div>
            </div>

            <div class="data-table-section">
                <table class="profiles-table">
                    <thead>
                        <tr>
                            <th style="width: 40px;"><input type="checkbox" id="selectAll" onclick="toggleSelectAll()"></th>
                            @if(isset($source) && $source === 'database')
                                <th>IMID</th>
                                <th>Name</th>
                                <th>Mobile Number</th>
                                <th>Gender</th>
                                <th>Source</th>
                                <th>Actions</th>
                            @else
                                <th>Name</th>
                                <th>Mobile Number</th>
                                <th>Gender</th>
                                <th>Source</th>
                                <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($source) && $source === 'database')
                            @forelse($databaseUsers as $u)
                            <tr>
                                <td><input type="checkbox" class="record-checkbox" value="{{ $u->id }}"></td>
                                <td>{{ $u->code ?? 'IM' . date('Ym') . 'I' . $u->id }}</td>
                                <td>{{ trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? '')) ?: ($u->name ?? '-') }}</td>
                                <td>{{ $u->phone ?? '-' }}</td>
                                <td>{{ $u->gender ?? '-' }}</td>
                                <td>database</td>
                                <td class="actions">
                                    <button class="action-btn history" title="Copy Mobile" onclick="copyToClipboard('{{ $u->phone ?? '' }}')">📋</button>
                                    <button class="action-btn edit-btn" title="Edit User" onclick="openEditModal({{ $u->id }}, 'database')">✏️</button>
                                    <a href="{{ route('edit.fresh.data', $u->id) }}" class="action-btn view-btn" title="View User">👁️</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align:center; padding: 40px;">No users found.</td>
                            </tr>
                            @endforelse

                            {{-- Pagination for users --}}
                            <tr>
                                <td colspan="6" style="text-align:center; padding: 14px; border-top: none;">
                                    {{ $databaseUsers->links() }}
                                </td>
                            </tr>
                        @else
                            @forelse($freshData as $data)
                            <tr>
                                <td><input type="checkbox" class="record-checkbox" value="{{ $data->id }}"></td>
                                <td>{{ $data->name }}</td>
                                <td>{{ $data->mobile }}</td>
                                <td>{{ $data->gender ?? '-' }}</td>
                                <td>{{ $data->source }}</td>
                                <td class="actions">
                                    <button class="action-btn history" onclick="copyToClipboard('{{ $data->mobile }}')" title="Copy Mobile">📋</button>
                                    <button class="action-btn edit-btn" title="Edit" onclick="openEditModal({{ $data->id }}, 'fresh_data')">✏️</button>
                                    <a href="{{ route('fresh.data.view', $data->id) }}" class="action-btn view-btn" title="View">👁️</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align:center; padding: 40px;">No fresh data found.</td>
                            </tr>
                            @endforelse
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Import Modal -->
    <div id="importModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Import from Excel</h2>
                <button class="close-btn" onclick="closeImportModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="importForm">
                    <div class="upload-area" onclick="document.getElementById('fileInput').click()">
                        <div class="upload-icon">📁</div>
                        <div class="upload-text">Click to upload</div>
                        <div class="upload-hint">Excel files (.xlsx, .xls, .csv) - Max 10MB</div>
                    </div>
                    <input type="file" id="fileInput" class="file-input" accept=".xlsx,.xls,.csv" onchange="handleFileSelect(event)">

                    <div id="selectedFile" class="selected-file">
                        <div class="file-icon">📄</div>
                        <div class="file-info">
                            <div class="file-name" id="fileName"></div>
                            <div class="file-size" id="fileSize"></div>
                        </div>
                        <button type="button" class="remove-file-btn" onclick="removeFile()">Remove</button>
                    </div>

                    <a href="{{ route('fresh.data.template') }}" class="template-link">📥 Download Sample Template</a>

                    <div id="progressContainer" class="progress-container">
                        <div class="progress-bar">
                            <div class="progress-fill" id="progressFill"></div>
                        </div>
                        <div class="progress-text" id="progressText">Uploading...</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-cancel" onclick="closeImportModal()">Cancel</button>
                <button class="btn btn-import" id="importBtn" onclick="submitImport()" disabled>Import</button>
            </div>
        </div>
    </div>

    <!-- Assign Modal -->
    <div id="assignModal" class="assign-modal">
        <div class="assign-modal-content">
            <div class="assign-modal-header">
                <h2>Assign Fresh Data</h2>
                <p id="assignCount" style="margin: 10px 0 0 0; opacity: 0.9;"></p>
            </div>
            <div class="assign-modal-body">
                <form id="assignForm">
                    <div class="assign-form-group">
                        <label>Assign To *</label>
                        <select id="assignedTo" required>
                            <option value="">-- Select Executive --</option>
                            <optgroup label="Service Team">
                                @foreach($serviceExecutives as $executive)
                                    <option value="{{ $executive->id }}">{{ $executive->name }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Sales Team">
                                @if(isset($salesExecutives) && $salesExecutives->count() > 0)
                                    @foreach($salesExecutives as $executive)
                                        <option value="{{ $executive->id }}">{{ $executive->name }}</option>
                                    @endforeach
                                @else
                                    <option value="">No sales executives found</option>
                                @endif
                            </optgroup>
                        </select>
                    </div>
                </form>
            </div>
            <div class="assign-modal-footer">
                <button type="button" class="assign-cancel-btn" onclick="closeAssignModal()">Cancel</button>
                <button type="button" class="assign-submit-btn" onclick="submitAssignment()">Assign</button>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Copy to clipboard function
        function copyToClipboard(text) {
            if (!text || text === '-') {
                alert('No phone number to copy');
                return;
            }
            
            navigator.clipboard.writeText(text).then(function() {
                alert('Phone number copied: ' + text);
            }).catch(function(err) {
                // Fallback for older browsers
                const tempInput = document.createElement('input');
                tempInput.value = text;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);
                alert('Phone number copied: ' + text);
            });
        }

        function openImportModal() {
            document.getElementById('importModal').classList.add('active');
        }

        function closeImportModal() {
            document.getElementById('importModal').classList.remove('active');
            document.getElementById('importForm').reset();
            document.getElementById('selectedFile').classList.remove('active');
            document.getElementById('progressContainer').classList.remove('active');
            document.getElementById('importBtn').disabled = true;
        }

        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                document.getElementById('fileName').textContent = file.name;
                document.getElementById('fileSize').textContent = formatFileSize(file.size);
                document.getElementById('selectedFile').classList.add('active');
                document.getElementById('importBtn').disabled = false;
            }
        }

        function removeFile() {
            document.getElementById('fileInput').value = '';
            document.getElementById('selectedFile').classList.remove('active');
            document.getElementById('importBtn').disabled = true;
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        async function submitImport() {
            const file = document.getElementById('fileInput').files[0];
            if (!file) { alert('Please select a file'); return; }

            const importBtn = document.getElementById('importBtn');
            const progressContainer = document.getElementById('progressContainer');
            const progressFill = document.getElementById('progressFill');
            const progressText = document.getElementById('progressText');

            importBtn.disabled = true;
            progressContainer.classList.add('active');
            progressFill.style.width = '30%';
            progressText.textContent = 'Uploading...';

            const formData = new FormData();
            formData.append('excel_file', file);
            formData.append('source', 'Other');

            try {
                const response = await fetch('{{ route("fresh.data.import") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: formData
                });

                progressFill.style.width = '70%';
                progressText.textContent = 'Processing...';

                const result = await response.json();

                progressFill.style.width = '100%';
                progressText.textContent = 'Complete!';

                if (result.success) {
                    setTimeout(() => {
                        alert(result.message);
                        window.location.reload();
                    }, 500);
                } else {
                    alert('Error: ' + result.message);
                    closeImportModal();
                }
            } catch (error) {
                alert('Import failed: ' + error.message);
                closeImportModal();
            }
        }

        // Select All functionality
        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.record-checkbox');
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
        }

        // Open assign modal
        function openBulkAssignModal() {
            const selected = document.querySelectorAll('.record-checkbox:checked');
            if (selected.length === 0) {
                alert('Please select at least one record');
                return;
            }
            document.getElementById('assignCount').textContent = selected.length + ' record(s) selected';
            document.getElementById('assignModal').classList.add('active');
        }

        // Close assign modal
        function closeAssignModal() {
            document.getElementById('assignModal').classList.remove('active');
            document.getElementById('assignForm').reset();
        }

        // Submit assignment
        async function submitAssignment() {
            const assignedTo = document.getElementById('assignedTo').value;
            if (!assignedTo) {
                alert('Please select an executive');
                return;
            }

            const selected = Array.from(document.querySelectorAll('.record-checkbox:checked'))
                .map(cb => cb.value);

            try {
                const response = await fetch('{{ route("fresh.data.bulk.assign") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        record_ids: selected,
                        assigned_to: assignedTo
                    })
                });

                const result = await response.json();
                if (result.success) {
                    alert(result.message);
                    closeAssignModal();
                    window.location.reload();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                alert('Failed: ' + error.message);
            }
        }

        // Close modals on outside click
        window.addEventListener('click', (e) => {
            if (e.target.id === 'assignModal') closeAssignModal();
            if (e.target.id === 'importModal') closeImportModal();
        });
    </script>
</body>

<!-- Edit Follow-up Modal -->
<div class="modal fade" id="editFollowupModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Edit Profile Follow-up</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editFollowupForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_user_id">
                    <input type="hidden" id="edit_source">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Customer Name</label>
                                <input type="text" class="form-control" id="edit_customer_name" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Profile ID</label>
                                <input type="text" class="form-control" id="edit_profile_id" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Current Date</label>
                                <input type="text" class="form-control" id="edit_current_date" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status <span class="text-danger">*</span></label>
                                <select class="form-control" id="edit_status" required>
                                    <option value="">Select Status</option>
                                    <option value="RNR">RNR</option>
                                    <option value="Other State">Other State</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Another Call">Another Call</option>
                                    <option value="Busy">Busy</option>
                                    <option value="Call Back">Call Back</option>
                                    <option value="CNC">CNC</option>
                                    <option value="Created">Created</option>
                                    <option value="Interested">Interested</option>
                                    <option value="Marriage Fixed">Marriage Fixed</option>
                                    <option value="NI">NI</option>
                                    <option value="Switch Off">Switch Off</option>
                                    <option value="Wrong Number">Wrong Number</option>
                                    <option value="Duplicate">Duplicate</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Next Follow-up Date</label>
                        <input type="date" class="form-control" id="edit_next_follow_up_date">
                    </div>

                    <div class="form-group">
                        <label>Remarks</label>
                        <textarea class="form-control" id="edit_remarks" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


