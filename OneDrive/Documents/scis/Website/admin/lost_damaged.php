<?php include 'auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zamboanga City OSCA - Lost/Damage</title>
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
                <h1 id="page-title" class="font-bold text-sm md:text-base leading-tight text-black">Lost/Damaged ID Replacement</h1>
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
        <div class="flex flex-col md:flex-row gap-6 mb-8 items-start">
            <div class="flex-grow">
                <h2 class="text-2xl font-bold brand-blue-text">Lost/ Damage</h2>
                <p class="text-sm text-gray-600">Replace Lost or Damaged Senior Citizen ID</p>
            </div>
            <div class="flex items-center gap-4 w-full md:w-auto">
                <label class="font-bold text-black text-sm md:text-base hidden md:block" for="application_type_id">Reason for replacement:</label>
                <div class="relative">
                    <select id="application_type_id" name="application_type_id" class="appearance-none border border-gray-300 rounded-md px-4 py-2 w-56 text-gray-700 bg-white hover:border-gray-400 focus:outline-none focus:ring-1 focus:ring-blue-500 pr-8">
                        <!-- Options will be populated by JS -->
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-300 rounded-lg shadow-sm mb-6">
            <div class="bg-gray-50 border-b border-gray-300 px-6 py-3">
                <h3 class="font-bold brand-blue-text text-base">Senior Citizen Details</h3>
            </div>
            <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-y-5 gap-x-6">
                <div>
                    <label for="osca_id" class="block text-sm font-medium text-gray-700 mb-1">OSCA ID</label>
                    <input type="text" id="osca_id" name="osca_id" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm bg-gray-100" readonly>
                </div>
                <div>
                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" id="full_name" name="full_name" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm bg-gray-100" readonly>
                </div>
                <div>
                    <label for="birthdate" class="block text-sm font-medium text-gray-700 mb-1">Birthdate</label>
                    <input type="date" id="birthdate" name="birthdate" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm bg-gray-100" readonly>
                </div>
                <div>
                    <label for="barangay" class="block text-sm font-medium text-gray-700 mb-1">Barangay</label>
                    <input type="text" id="barangay" name="barangay" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm bg-gray-100" readonly>
                </div>
            </div>
        </div>

        <form id="lost-damaged-form">
            <input type="hidden" id="senior-id" name="senior_id">
            <input type="hidden" id="selected-application-type-id" name="application_type_id">

            <div class="bg-white border border-gray-300 rounded-lg shadow-sm mb-6">
                <div class="bg-gray-50 border-b border-gray-300 px-6 py-3">
                    <h3 class="font-bold brand-blue-text text-base">Application Details</h3>
                </div>
                <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-y-5 gap-x-6">
                    <div>
                        <label for="application_notes" class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                        <textarea id="application_notes" name="notes" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm"></textarea>
                    </div>
                </div>
            </div>
            <div id="form-error-message" class="text-red-500 text-sm mt-4"></div>
            <div class="bg-gray-50 border-t border-gray-300 px-6 py-3 flex justify-end">
                <button type="submit" class="bg-brandBlue text-white px-6 py-2 rounded-md font-semibold text-sm hover:bg-blue-800 transition">Submit Application</button>
            </div>
        </form>
    </main>

    <script>
        const loadingOverlay = document.getElementById('loading-overlay');
        const formErrorMessage = document.getElementById('form-error-message');
        const seniorIdInput = document.getElementById('senior-id');
        const applicationTypeSelect = document.getElementById('application_type_id');
        const lostDamagedForm = document.getElementById('lost-damaged-form');

        document.addEventListener('DOMContentLoaded', async function() {
            loadingOverlay.classList.remove('hidden');

            // Populate Application Type dropdown
            // Hardcoded based on SQL dump for Lost ID (3) and Damaged ID (4)
            applicationTypeSelect.innerHTML = `<option value="">Select Reason</option>`;
            applicationTypeSelect.innerHTML += `<option value="3">Lost ID</option>`;
            applicationTypeSelect.innerHTML += `<option value="4">Damaged ID</option>`;

            const urlParams = new URLSearchParams(window.location.search);
            const seniorId = urlParams.get('id');

            if (seniorId) {
                seniorIdInput.value = seniorId;
                await fetchSeniorDetails(seniorId);
            } else {
                showMessage('error', 'Error', "Senior ID is required for Lost/Damaged ID application.");
                loadingOverlay.classList.add('hidden');
                return;
            }

            // Update hidden input when select changes
            applicationTypeSelect.addEventListener('change', function() {
                document.getElementById('selected-application-type-id').value = this.value;
            });

            loadingOverlay.classList.add('hidden');
        });

        async function fetchSeniorDetails(id) {
            loadingOverlay.classList.remove('hidden');
            try {
                const response = await fetch(`../api/seniors/details.php?id=${id}`);
                const data = await response.json();
                if (data.success) {
                    const senior = data.data;
                    document.getElementById('osca_id').value = senior.osca_id;
                    document.getElementById('full_name').value = `${senior.first_name} ${senior.middle_name ? senior.middle_name + ' ' : ''}${senior.last_name} ${senior.extension ? senior.extension : ''}`;
                    document.getElementById('birthdate').value = senior.birthdate;
                    // Fetch barangay name from API or use the senior.barangay_name if available in details API
                    document.getElementById('barangay').value = senior.barangay_name || 'N/A'; 

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

        lostDamagedForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            formErrorMessage.textContent = "";

            if (!seniorIdInput.value || !applicationTypeSelect.value) {
                showMessage('error', 'Validation Error', "Please select a reason and ensure Senior ID is provided.");
                return;
            }

            loadingOverlay.classList.remove('hidden');

            const formData = new FormData(this);
            const data = {
                senior_id: formData.get('senior_id'),
                application_type_id: formData.get('application_type_id'), // This will be the value from the hidden input
                notes: formData.get('notes')
            };

            try {
                const response = await fetch('../api/applications/save.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                if (result.success) {
                    showMessage('success', 'Success', 'Application submitted successfully!', 'senior_citizen_list.php');
                } else {
                    showMessage('error', 'Submission Error', result.message);
                }
            } catch (error) {
                showMessage('error', 'Submission Error', 'An unexpected error occurred.');
                console.error('Error:', error);
            } finally {
                loadingOverlay.classList.add('hidden');
            }
        });
    </script>
</body>
</html>