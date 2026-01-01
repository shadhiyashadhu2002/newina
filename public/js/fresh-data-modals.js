// Fresh Data Modal Handler
document.addEventListener('DOMContentLoaded', function() {
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    
    if (!csrfToken) {
        console.warn('CSRF token not found!');
    }

    // Add to window for global access
    window.openEditModal = function(userId, source) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editFollowupForm');
        
        if (!modal || !form) {
            console.error('Modal or form not found');
            return;
        }

        // Show loading state
        modal.style.display = 'flex';
        
        // Fetch user data
        fetch(`/fresh-data/${userId}/get-data?source=${source}`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Populate modal fields
            document.getElementById('modalCustomerName').textContent = data.name || 'N/A';
            document.getElementById('modalProfileId').textContent = data.profile_id || data.code || 'N/A';
            document.getElementById('modalStatus').value = data.status || '';
            document.getElementById('modalNextFollowup').value = data.next_follow_up_date || '';
            document.getElementById('modalRemarks').value = '';
            
            // Store data in form dataset
            form.dataset.userId = userId;
            form.dataset.source = source;
        })
        .catch(error => {
            console.error('Error loading user data:', error);
            alert('Error loading user data: ' + error.message);
            closeEditModal();
        });
    };

    window.closeEditModal = function() {
        const modal = document.getElementById('editModal');
        if (modal) {
            modal.style.display = 'none';
        }
    };

    window.saveFollowup = function() {
        const form = document.getElementById('editFollowupForm');
        if (!form) {
            alert('Form not found');
            return;
        }

        const userId = form.dataset.userId;
        const source = form.dataset.source;
        
        const formData = {
            source: source,
            status: document.getElementById('modalStatus').value,
            next_follow_up_date: document.getElementById('modalNextFollowup').value,
            remarks: document.getElementById('modalRemarks').value
        };

        fetch(`/fresh-data/${userId}/update-followup`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(formData)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Follow-up updated successfully!');
                closeEditModal();
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error saving follow-up:', error);
            alert('Error saving follow-up: ' + error.message);
        });
    };

    // Close modal when clicking outside
    const editModal = document.getElementById('editModal');
    if (editModal) {
        editModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    }
});
