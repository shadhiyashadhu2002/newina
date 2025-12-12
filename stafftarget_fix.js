// CORRECT showStatsModal function
function showStatsModal(type) {
    const modal = document.getElementById('statsModal');
    const title = document.getElementById('modalTitle');
    const content = document.getElementById('modalContent');
    
    // Get the prepared data from the table
    const tableRows = document.querySelectorAll('tbody tr');
    let filteredData = [];
    
    tableRows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length > 0) {
            const staffName = cells[0]?.textContent.trim();
            const monthText = cells[1]?.textContent.trim();
            const department = cells[2]?.textContent.trim();
            const targetAmount = cells[3]?.textContent.trim();
            const achievedAmount = cells[4]?.textContent.trim();
            const balanceAmount = cells[5]?.textContent.trim();
            const percentageText = cells[6]?.textContent?.trim() || "0%";
            const status = cells[7]?.textContent?.trim() || "In Progress";
            const percentage = parseFloat((percentageText || "0%").replace("%", "") || 0);
            const achieved = parseFloat(achievedAmount?.replace(/[^0-9.-]/g, "") || 0);
            
            let include = false;
            if (type === 'total') {
                include = true;
            } else if (type === 'achieved' && status?.toLowerCase().includes('achieved')) {
                include = true;
            } else if (type === 'inProgress' && status?.toLowerCase().includes('progress')) {
                include = true;
            } else if (type === 'zeroSales' && achieved === 0 && percentage === 0) {
                include = true;
            }
            
            if (include) {
                filteredData.push({
                    name: staffName,
                    month: monthText,
                    department: department,
                    target: targetAmount,
                    achieved: achievedAmount,
                    balance: balanceAmount,
                    percentage: percentageText,
                    status: status
                });
            }
        }
    });
    
    // Set modal title
    const titles = {
        'total': '📊 Total Staff Members',
        'achieved': '✅ Targets Achieved',
        'inProgress': '⏳ In Progress',
        'zeroSales': '❌ Zero Sales Staff'
    };
    title.textContent = titles[type] || 'Staff Details';
    
    // Generate content
    if (filteredData.length === 0) {
        content.innerHTML = '<p style="text-align: center; color: #666; padding: 20px;">No staff members found in this category.</p>';
    } else {
        let html = '<div style="overflow-x: auto;"><table style="width: 100%; border-collapse: collapse;">';
        html += '<thead><tr style="background: #f5f5f5;">';
        html += '<th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Staff Name</th>';
        html += '<th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Month</th>';
        html += '<th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Department</th>';
        html += '<th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Target</th>';
        html += '<th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Achieved</th>';
        html += '<th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Percentage</th>';
        html += '<th style="padding: 12px; text-align: left; border-bottom: 2px solid #ddd;">Status</th>';
        html += '</tr></thead><tbody>';
        
        filteredData.forEach((item, index) => {
            const bgColor = index % 2 === 0 ? '#fff' : '#f9f9f9';
            html += `<tr style="background: ${bgColor};">`;
            html += `<td style="padding: 12px; border-bottom: 1px solid #eee;">${item.name}</td>`;
            html += `<td style="padding: 12px; border-bottom: 1px solid #eee;">${item.month}</td>`;
            html += `<td style="padding: 12px; border-bottom: 1px solid #eee;">${item.department}</td>`;
            html += `<td style="padding: 12px; border-bottom: 1px solid #eee;">${item.target}</td>`;
            html += `<td style="padding: 12px; border-bottom: 1px solid #eee;">${item.achieved}</td>`;
            html += `<td style="padding: 12px; border-bottom: 1px solid #eee;">${item.percentage}</td>`;
            html += `<td style="padding: 12px; border-bottom: 1px solid #eee;">${item.status}</td>`;
            html += '</tr>';
        });
        html += '</tbody></table></div>';
        html += `<div style="margin-top: 20px; padding: 15px; background: #f0f0f0; border-radius: 8px; text-align: center;">`;
        html += `<strong>Total: ${filteredData.length} staff member(s)</strong>`;
        html += '</div>';
        content.innerHTML = html;
    }
    
    modal.style.display = 'flex';
}

// closeEditModal function
window.closeEditModal = function() {
    document.getElementById('editTargetModal').style.display = 'none';
}

// closeViewModal function
window.closeViewModal = function() {
    document.getElementById('viewTargetModal').style.display = 'none';
}

// submitEditForm function
window.submitEditForm = function() {
    const form = document.getElementById('editTargetForm');
    const formData = new FormData(form);
    const id = document.getElementById('edit_target_id').value;
    
    // Convert FormData to JSON
    const data = {};
    formData.forEach((value, key) => {
        data[key] = value;
    });
    fetch(`/staff-target/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            closeEditModal();
            location.reload();
        } else {
            alert(data.message || 'Failed to update target');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update target');
    });
}

function closeStatsModal() {
    document.getElementById('statsModal').style.display = 'none';
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('statsModal');
    if (event.target === modal) {
        closeStatsModal();
    }
});
