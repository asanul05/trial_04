<?php include 'auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zamboanga City OSCA - Add User</title>
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
                <h1 id="page-title" class="font-bold text-sm md:text-base leading-tight text-black">Add User Account</h1>
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
            <form id="user-form">
                <input type="hidden" id="user-id" name="id">
                <div class="bg-gray-50 border-b border-gray-300 px-6 py-3">
                    <h3 class="font-bold brand-blue-text text-base">User Details</h3>
                </div>
                <div class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-y-5 gap-x-6">
                    <div>
                        <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-1">Employee ID</label>
                        <input type="text" id="employee_id" name="employee_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm" required>
                    </div>
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" id="username" name="username" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm" required>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" id="password" name="password" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                        <p id="password-help" class="mt-1 text-xs text-gray-500 hidden">Leave blank to keep current password</p>
                    </div>
                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                    </div>
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" id="first_name" name="first_name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm" required>
                    </div>
                    <div>
                        <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                        <input type="text" id="middle_name" name="middle_name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" id="last_name" name="last_name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm" required>
                    </div>
                    <div>
                        <label for="extension" class="block text-sm font-medium text-gray-700 mb-1">Extension</label>
                        <input type="text" id="extension" name="extension" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                    </div>
                    <div>
                        <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                        <input type="text" id="position" name="position" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                    </div>
                    <div>
                        <label for="gender_id" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                        <select id="gender_id" name="gender_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                            <!-- Options populated by JS -->
                        </select>
                    </div>
                    <div>
                        <label for="mobile_number" class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
                        <input type="text" id="mobile_number" name="mobile_number" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                    </div>
                    <div>
                        <label for="role_id" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select id="role_id" name="role_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm" required>
                            <!-- Options populated by JS -->
                        </select>
                    </div>
                    <div>
                        <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-1">Branch</label>
                        <select id="branch_id" name="branch_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                            <option value="">Select Branch (if Branch Admin)</option>
                            <!-- Options populated by JS -->
                        </select>
                    </div>
                    <div>
                        <label for="barangay_id" class="block text-sm font-medium text-gray-700 mb-1">Barangay</label>
                        <select id="barangay_id" name="barangay_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                            <option value="">Select Barangay (if Barangay Admin)</option>
                            <!-- Options populated by JS -->
                        </select>
                    </div>
                    <div class="md:col-span-2 flex items-center gap-2">
                        <input type="checkbox" id="is_active" name="is_active" class="checkbox-md" value="1">
                        <label for="is_active" class="text-sm font-medium text-gray-700">Is Active</label>
                    </div>
                </div>
                <div id="error-message" class="text-red-500 text-sm px-6 py-3"></div>
                <div class="bg-gray-50 border-t border-gray-300 px-6 py-3 flex justify-end">
                    <button type="submit" class="bg-brandBlue text-white px-6 py-2 rounded-md font-semibold text-sm hover:bg-blue-800 transition">Save User</button>
                </div>
            </form>
        </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const userId = urlParams.get('id');
            const form = document.getElementById('user-form');
            const pageTitle = document.getElementById('page-title');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirm_password');
            const passwordHelp = document.getElementById('password-help');
            const errorMessageDiv = document.getElementById('error-message');
            const loadingOverlay = document.getElementById('loading-overlay');

            // Populate Gender dropdown (hardcoded for now, should be from API)
            const genderSelect = document.getElementById('gender_id');
            const genders = {1: 'Male', 2: 'Female', 3: 'Other'};
            genderSelect.innerHTML = '<option value="">Select Gender</option>';
            for (const [id, name] of Object.entries(genders)) {
                genderSelect.innerHTML += `<option value="${id}">${name}</option>`;
            }

            // Populate Role dropdown (hardcoded for now, should be from API)
            const roleSelect = document.getElementById('role_id');
            const roles = {1: 'Main Admin', 2: 'Branch Admin', 3: 'Barangay Admin'};
            roleSelect.innerHTML = '<option value="">Select Role</option>';
            for (const [id, name] of Object.entries(roles)) {
                roleSelect.innerHTML += `<option value="${id}">${name}</option>`;
            }

            // Populate Branch dropdown (hardcoded for now, should be from API)
            const branchSelect = document.getElementById('branch_id');
            const branches = {2: 'Field Office 1 - East District', 3: 'Field Office 2 - West District'}; // Example branches
            for (const [id, name] of Object.entries(branches)) {
                branchSelect.innerHTML += `<option value="${id}">${name}</option>`;
            }

            // Populate Barangay dropdown (hardcoded for now, should be from API)
            const barangaySelect = document.getElementById('barangay_id');
            const barangays = {79: 'Tetuan', 68: 'Santa Maria'}; // Example barangays
            for (const [id, name] of Object.entries(barangays)) {
                barangaySelect.innerHTML += `<option value="${id}">${name}</option>`;
            }


            if (userId) {
                pageTitle.textContent = 'Edit User Account';
                document.getElementById('user-id').value = userId;
                passwordInput.removeAttribute('required');
                passwordHelp.classList.remove('hidden');

                loadingOverlay.classList.remove('hidden'); // Show spinner
                fetch(`../api/users/details.php?id=${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const user = data.data;
                            document.getElementById('employee_id').value = user.employee_id;
                            document.getElementById('username').value = user.username;
                            document.getElementById('first_name').value = user.first_name;
                            document.getElementById('middle_name').value = user.middle_name;
                            document.getElementById('last_name').value = user.last_name;
                            document.getElementById('extension').value = user.extension;
                            document.getElementById('position').value = user.position;
                            document.getElementById('gender_id').value = user.gender_id;
                            document.getElementById('mobile_number').value = user.mobile_number;
                            document.getElementById('email').value = user.email;
                            document.getElementById('role_id').value = user.role_id;
                            document.getElementById('branch_id').value = user.branch_id;
                            document.getElementById('barangay_id').value = user.barangay_id;
                            document.getElementById('is_active').checked = user.is_active == 1;
                        } else {
                            showMessage('error', 'Fetch Error', data.message);
                        }
                    })
                    .catch(error => {
                        showMessage('error', 'Fetch Error', 'An unexpected error occurred while fetching details.');
                        console.error('Error:', error);
                    })
                    .finally(() => {
                        loadingOverlay.classList.add('hidden'); // Hide spinner
                    });
            } else {
                // For new users, password is required
                passwordInput.setAttribute('required', 'required');
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                errorMessageDiv.textContent = ''; // Clear previous errors

                if (passwordInput.value !== confirmPasswordInput.value) {
                    showMessage('error', 'Validation Error', 'Passwords do not match.');
                    return;
                }

                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());
                data.is_active = document.getElementById('is_active').checked ? 1 : 0;
                
                // Remove confirm_password as it's not needed by the API
                delete data.confirm_password;

                loadingOverlay.classList.remove('hidden'); // Show spinner

                fetch('../api/users/save.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        showMessage('success', 'Success', 'User account saved successfully!', 'accounts.php');
                    } else {
                        showMessage('error', 'Save Error', result.message);
                    }
                })
                .catch(error => {
                    showMessage('error', 'Save Error', 'An unexpected error occurred during save.');
                    console.error('Error:', error);
                })
                .finally(() => {
                    loadingOverlay.classList.add('hidden'); // Hide spinner
                });
            });
        });
    </script>
</body>
</html>