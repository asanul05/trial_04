<?php include 'auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Complaint - OSCA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body class="inter-body text-gray-700 flex flex-col min-h-screen">
    <div id="loading-overlay" class="loading-overlay hidden">
        <div class="spinner"></div>
    </div>

    <header class="bg-white border-b border-gray-200 py-3 px-4 md:px-8 flex justify-between items-center sticky top-0 z-20">
        <div class="flex items-center gap-4">
            <button onclick="window.history.back()" class="text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-full h-8 w-8 flex items-center justify-center transition">
                <i class="fa-solid fa-chevron-left text-sm"></i>
            </button>
            <div>
                <h1 id="page-title" class="font-bold text-sm md:text-base leading-tight text-black">File New Complaint</h1>
                <p class="text-[11px] md:text-xs text-gray-500 font-medium">Office of Senior Citizens Affairs</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block leading-tight">
                <p class="text-sm font-bold text-black">Space1000</p>
                <p class="text-[10px] text-gray-500 font-semibold uppercase">Social Worker Coordinator</p>
            </div>
            <div class="h-9 w-9 rounded-full border border-gray-300 flex items-center justify-center text-gray-800 text-lg">
                <i class="fa-regular fa-user text-xl"></i>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto p-4 md:p-8 w-full flex-grow fade-in">
        <div class="bg-white border border-gray-300 rounded-lg shadow-sm">
            <form id="complaint-form">
                <input type="hidden" id="complaint-id" name="id">
                <div class="bg-gray-50 border-b border-gray-300 px-6 py-3">
                    <h3 class="font-bold brand-blue-text text-base">Complaint Details</h3>
                </div>
                <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-y-5 gap-x-6">
                    <div>
                        <label for="complainant_id" class="block text-sm font-medium text-gray-700 mb-1">Complainant (Senior Citizen)</label>
                        <select id="complainant_id" name="complainant_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm" required>
                            <!-- Options populated by JS -->
                        </select>
                    </div>
                    <div>
                        <label for="violator_name" class="block text-sm font-medium text-gray-700 mb-1">Violator Name</label>
                        <input type="text" id="violator_name" name="violator_name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm" required>
                    </div>
                    <div>
                        <label for="violator_contact" class="block text-sm font-medium text-gray-700 mb-1">Violator Contact (Optional)</label>
                        <input type="text" id="violator_contact" name="violator_contact" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                    </div>
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Complaint Category</label>
                        <select id="category_id" name="category_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm" required>
                            <!-- Options populated by JS -->
                        </select>
                    </div>
                    <div>
                        <label for="incident_date" class="block text-sm font-medium text-gray-700 mb-1">Incident Date (Optional)</label>
                        <input type="date" id="incident_date" name="incident_date" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                    </div>
                    <div>
                        <label for="incident_location" class="block text-sm font-medium text-gray-700 mb-1">Incident Location (Optional)</label>
                        <input type="text" id="incident_location" name="incident_location" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                    </div>
                    <div>
                        <label for="status_id" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status_id" name="status_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm" required>
                            <!-- Options populated by JS -->
                        </select>
                    </div>
                    <div>
                        <label for="amount_billable" class="block text-sm font-medium text-gray-700 mb-1">Amount Billable (Optional)</label>
                        <input type="number" step="0.01" id="amount_billable" name="amount_billable" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                    </div>
                    <div>
                        <label for="filed_date" class="block text-sm font-medium text-gray-700 mb-1">Filed Date</label>
                        <input type="date" id="filed_date" name="filed_date" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm" required>
                    </div>
                    <div>
                        <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-1">Assigned To (User) (Optional)</label>
                        <select id="assigned_to" name="assigned_to" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                            <!-- Options populated by JS -->
                        </select>
                    </div>
                    <div>
                        <label for="resolved_date" class="block text-sm font-medium text-gray-700 mb-1">Resolved Date (Optional)</label>
                        <input type="date" id="resolved_date" name="resolved_date" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm" required></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label for="resolution_notes" class="block text-sm font-medium text-gray-700 mb-1">Resolution Notes (Optional)</label>
                        <textarea id="resolution_notes" name="resolution_notes" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm"></textarea>
                    </div>
                </div>
                <div id="form-error-message" class="text-red-500 text-sm px-6 py-3"></div>
                <div class="bg-gray-50 border-t border-gray-300 px-6 py-3 flex justify-end">
                    <button type="submit" class="bg-brandBlue text-white px-6 py-2 rounded-md font-semibold text-sm hover:bg-blue-800 transition">Save Complaint</button>
                </div>
            </form>
        </div>
    </main>

    <script>
        const loadingOverlay = document.getElementById('loading-overlay');
        const formErrorMessage = document.getElementById('form-error-message');
        const complaintForm = document.getElementById('complaint-form');
        const complaintIdInput = document.getElementById('complaint-id');
        const pageTitle = document.getElementById('page-title');

        // Dropdown elements
        const complainantSelect = document.getElementById('complainant_id');
        const categorySelect = document.getElementById('category_id');
        const statusSelect = document.getElementById('status_id');
        const assignedToSelect = document.getElementById('assigned_to');

        document.addEventListener('DOMContentLoaded', async function() {
            loadingOverlay.classList.remove('hidden');

            await Promise.all([
                fetchAndPopulateDropdown('../../api/seniors/list.php', complainantSelect, 'Select Complainant', 'id', 'full_name'),
                fetchAndPopulateDropdown('../../api/complaint_categories/list.php', categorySelect, 'Select Category'),
                fetchAndPopulateDropdown('../../api/complaint_statuses/list.php', statusSelect, 'Select Status'),
                fetchAndPopulateDropdown('../../api/users/list.php', assignedToSelect, 'Select User', 'id', 'full_name') // Assuming users list provides full_name
            ]).catch(error => {
                showMessage('error', 'Fetch Error', "Failed to load form data. Please try again.");
                console.error("Error fetching dropdown data:", error);
            });

            const urlParams = new URLSearchParams(window.location.search);
            const complaintId = urlParams.get('id');

            if (complaintId) {
                pageTitle.textContent = 'Edit Complaint';
                complaintIdInput.value = complaintId;
                await fetchComplaintDetails(complaintId);
            } else {
                pageTitle.textContent = 'File New Complaint';
                document.getElementById('filed_date').valueAsDate = new Date(); // Default filed_date to today
            }

            loadingOverlay.classList.add('hidden');
        });

        async function fetchAndPopulateDropdown(apiEndpoint, selectElement, defaultOptionText, idKey = 'id', nameKey = 'name') {
            const response = await fetch(apiEndpoint);
            const data = await response.json();
            if (data.success) {
                selectElement.innerHTML = `<option value="">${defaultOptionText}</option>`;
                data.data.forEach(item => {
                    let optionName = item[nameKey];
                    // Special handling for seniors list if it comes without full_name initially
                    if (apiEndpoint.includes('/seniors/list.php') && !item.full_name) {
                        optionName = `${item.first_name} ${item.middle_name ? item.middle_name + ' ' : ''}${item.last_name} ${item.extension ? item.extension : ''} (${item.osca_id})`;
                    } else if (apiEndpoint.includes('/users/list.php') && !item.full_name) {
                        optionName = `${item.first_name} ${item.last_name} (${item.username})`;
                    }
                    selectElement.innerHTML += `<option value="${item[idKey]}">${optionName}</option>`;
                });
            } else {
                showMessage('error', 'Fetch Error', `Error fetching data from ${apiEndpoint}: ${data.message}`);
                console.error(`Error fetching data from ${apiEndpoint}:`, data.message);
            }
        }

        async function fetchComplaintDetails(id) {
            loadingOverlay.classList.remove('hidden');
            try {
                const response = await fetch(`../api/complaints/details.php?id=${id}`);
                const data = await response.json();
                if (data.success) {
                    const complaint = data.data;
                    document.getElementById('complainant_id').value = complaint.complainant_id;
                    document.getElementById('violator_name').value = complaint.violator_name;
                    document.getElementById('violator_contact').value = complaint.violator_contact;
                    document.getElementById('category_id').value = complaint.category_id;
                    document.getElementById('description').value = complaint.description;
                    document.getElementById('incident_date').value = complaint.incident_date;
                    document.getElementById('incident_location').value = complaint.incident_location;
                    document.getElementById('status_id').value = complaint.status_id;
                    document.getElementById('amount_billable').value = complaint.amount_billable;
                    document.getElementById('filed_date').value = complaint.filed_date;
                    document.getElementById('assigned_to').value = complaint.assigned_to;
                    document.getElementById('resolved_date').value = complaint.resolved_date;
                    document.getElementById('resolution_notes').value = complaint.resolution_notes;
                } else {
                    showMessage('error', 'Fetch Error', data.message);
                }
            } catch (error) {
                showMessage('error', 'Fetch Error', 'An unexpected error occurred while fetching details.');
                console.error('Error:', error);
            } finally {
                loadingOverlay.classList.add('hidden');
            }
        }

        complaintForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            formErrorMessage.textContent = "";

            loadingOverlay.classList.remove('hidden');

            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            // Convert empty strings to null for optional fields
            for (const key in data) {
                if (data[key] === '') {
                    data[key] = null;
                }
            }

            try {
                const response = await fetch('../api/complaints/save.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                if (result.success) {
                    showMessage('success', 'Success', 'Complaint saved successfully!', 'complaints.php');
                } else {
                    showMessage('error', 'Save Error', result.message);
                }
            } catch (error) {
                showMessage('error', 'Save Error', 'An unexpected error occurred.');
                console.error('Error:', error);
            } finally {
                loadingOverlay.classList.add('hidden');
            }
        });
    </script>
</body>
</html>