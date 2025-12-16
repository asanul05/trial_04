<?php include 'auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts Settings - OSCA</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        dashboardBlue: '#1a008e', 
                        'osca-text': '#1a008e',
                        'osca-bg': '#f3f4f6',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body class="bg-osca-bg font-sans flex text-gray-800">
    <div id="loading-overlay" class="loading-overlay hidden">
        <div class="spinner"></div>
    </div>

    <?php 
    // Sets the active page for sidebar highlighting
    $current_page = basename($_SERVER['PHP_SELF']); 
    ?>

    <?php include 'sidebar.php'; ?>

    <div class="ml-64 w-full min-h-screen flex flex-col">
        
        <?php include 'header.php'; ?>

        <main class="flex-1 p-8 overflow-y-auto">
            
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6 border border-gray-100">
                                
                <div class="mb-6">
                    <h2 class="text-3xl font-bold text-gray-900">Accounts Settings</h2>
                    <p class="text-gray-600 mt-1">Manage Account Settings</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <a href="add_user.php" class="bg-dashboardBlue text-white py-3 px-4 rounded-md font-bold hover:bg-indigo-900 transition shadow-md flex items-center justify-center gap-2">
                        <i class="fas fa-plus-circle"></i>
                        Add Account
                    </a>
                </div>

            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                                
                <h3 class="text-lg font-bold text-black mb-4">User List</h3>

                <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
                                        
                    <div class="flex items-center gap-2 w-full md:w-auto">
                        <div class="relative w-full md:w-80">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                            </span>
                            <input type="text" placeholder="Search By Name" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-dashboardBlue text-sm">
                        </div>
                        <button class="p-2 border border-gray-300 rounded hover:bg-gray-50 text-gray-600 transition">
                            <i class="fa-solid fa-filter"></i>
                        </button>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Show</span>
                        <select class="p-2 border border-gray-300 rounded text-sm">
                            <option>10</option>
                            <option>25</option>
                            <option>50</option>
                        </select>
                        <span class="text-sm text-gray-600">entries</span>
                    </div>

                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-black font-bold text-sm border-b border-gray-200">
                                <th class="pb-4 pl-2">Employee ID</th>
                                <th class="pb-4">Name</th>
                                <th class="pb-4">Role</th>
                                <th class="pb-4">Status</th>
                                <th class="pb-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="users-table" class="text-gray-800 text-sm">
                            <!-- User rows will be inserted here by JavaScript -->
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center mt-4">
                    <div class="text-sm text-gray-600">
                        Showing 1 to 10 of 50 entries
                    </div>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-100">Previous</button>
                        <button class="px-3 py-1 border border-gray-300 rounded text-sm bg-dashboardBlue text-white">1</button>
                        <button class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-100">2</button>
                        <button class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-100">Next</button>
                    </div>
                </div>

            </div>

        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchUsers();
        });

        function fetchUsers() {
            const loadingOverlay = document.getElementById('loading-overlay');
            loadingOverlay.classList.remove('hidden'); // Show spinner

            fetch('../api/users/list.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const tableBody = document.getElementById('users-table');
                        tableBody.innerHTML = '';
                        data.data.forEach(user => {
                            const row = `
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                    <td class="py-4 pl-2 font-medium">${user.employee_id}</td>
                                    <td class="py-4">${user.first_name} ${user.last_name}</td>
                                    <td class="py-4">${user.role_name}</td>
                                    <td class="py-4">${user.is_active == 1 ? '<span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">Active</span>' : '<span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">Inactive</span>'}</td>
                                    <td class="py-4 text-center">
                                        <button class="px-4 py-1.5 border border-gray-300 text-gray-700 rounded hover:bg-gray-100 transition text-xs font-semibold" onclick="editUser(${user.id})">Edit</button>
                                        <button class="px-4 py-1.5 border border-red-500 text-red-500 rounded hover:bg-red-50 transition text-xs font-semibold" onclick="deleteUser(${user.id})">Delete</button>
                                    </td>
                                </tr>
                            `;
                            tableBody.innerHTML += row;
                        });
                    } else {
                        showMessage('error', 'Fetch Error', data.message);
                    }
                })
                .catch(error => showMessage('error', 'Fetch Error', 'An unexpected error occurred while fetching users.'))
                .finally(() => {
                    loadingOverlay.classList.add('hidden'); // Hide spinner
                });
        }

        function editUser(id) {
            window.location.href = `add_user.php?id=${id}`;
        }

        function deleteUser(id) {
            if (!confirm('Are you sure you want to delete this user?')) {
                return;
            }

            const loadingOverlay = document.getElementById('loading-overlay');
            loadingOverlay.classList.remove('hidden'); // Show spinner

            fetch(`../api/users/delete.php?id=${id}`, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('success', 'Success', 'User deleted successfully!');
                    fetchUsers(); // Refresh the list
                } else {
                    showMessage('error', 'Deletion Error', data.message);
                }
            })
            .catch(error => showMessage('error', 'Deletion Error', 'An unexpected error occurred during deletion.'))
            .finally(() => {
                loadingOverlay.classList.add('hidden'); // Hide spinner
            });
        }
    </script>
</body>
</html>