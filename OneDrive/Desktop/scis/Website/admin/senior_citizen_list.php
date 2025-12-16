<?php include 'auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senior Citizen List - OSCA</title>
    
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
    // Sets the active page for the sidebar
    $current_page = basename($_SERVER['PHP_SELF']); 
    ?>

    <?php include 'sidebar.php'; ?>

    <div class="ml-64 w-full min-h-screen flex flex-col">
        
        <?php include 'header.php'; ?>

        <main class="flex-1 p-8 overflow-y-auto">
            
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6 border border-gray-100">
                                
                <div class="mb-6">
                    <h2 class="text-3xl font-bold text-gray-900">Registration Category</h2>
                    <p class="text-gray-600 mt-1">Manage senior citizen ID applications and registrations</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <a href="new_id.php" class="bg-dashboardBlue text-white py-3 px-4 rounded-md font-bold hover:bg-indigo-900 transition shadow-md flex items-center justify-center gap-2">
                        <i class="fa-solid fa-plus"></i> New ID
                    </a>
                    <a href="revalidation_update.php" class="bg-dashboardBlue text-white py-3 px-4 rounded-md font-bold hover:bg-indigo-900 transition shadow-md flex items-center justify-center gap-2">
                        Revalidation/Update
                    </a>
                    <a href="lost_damaged.php" class="bg-dashboardBlue text-white py-3 px-4 rounded-md font-bold hover:bg-indigo-900 transition shadow-md flex items-center justify-center gap-2">
                        Lost/Damage ID
                    </a>
                    <button class="bg-dashboardBlue text-white py-3 px-4 rounded-md font-bold hover:bg-indigo-900 transition shadow-md">
                        Report
                    </button>
                </div>

            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                                
                <h3 class="text-lg font-bold text-black mb-4">Senior Citizen List</h3>

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
                                <th class="pb-4 pl-2">OSCA ID</th>
                                <th class="pb-4">Name</th>
                                <th class="pb-4">Birthdate</th>
                                <th class="pb-4">Barangay</th>
                                <th class="pb-4">Status</th>
                                <th class="pb-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="seniors-table" class="text-gray-800 text-sm">
                            <!-- Senior rows will be inserted here by JavaScript -->
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
            fetchSeniors();
        });

        function fetchSeniors() {
            const loadingOverlay = document.getElementById('loading-overlay');
            loadingOverlay.classList.remove('hidden'); // Show spinner

            fetch('../api/seniors/list.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const tableBody = document.getElementById('seniors-table');
                        tableBody.innerHTML = '';
                        data.data.forEach(senior => {
                            const row = `
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                    <td class="py-4 pl-2 font-medium">${senior.osca_id}</td>
                                    <td class="py-4">${senior.first_name} ${senior.last_name}</td>
                                    <td class="py-4">${new Date(senior.birthdate).toLocaleDateString()}</td>
                                    <td class="py-4">${senior.barangay_name}</td>
                                    <td class="py-4">${senior.is_active == 1 && senior.is_deceased == 0 ? '<span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">Active</span>' : (senior.is_deceased == 1 ? '<span class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">Deceased</span>' : '<span class="bg-red-100 text-red-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">Inactive</span>')}</td>
                                    <td class="py-4 text-center">
                                        <button class="px-4 py-1.5 border border-gray-300 text-gray-700 rounded hover:bg-gray-100 transition text-xs font-semibold" onclick="editSenior(${senior.id})">Edit</button>
                                        <button class="px-4 py-1.5 border border-red-500 text-red-500 rounded hover:bg-red-50 transition text-xs font-semibold" onclick="deleteSenior(${senior.id})">Delete</button>
                                    </td>
                                </tr>
                            `;
                            tableBody.innerHTML += row;
                        });
                    } else {
                        showMessage('error', 'Fetch Error', data.message);
                    }
                })
                .catch(error => showMessage('error', 'Fetch Error', 'An unexpected error occurred while fetching senior citizens.'))
                .finally(() => {
                    loadingOverlay.classList.add('hidden'); // Hide spinner
                });
        }

        function editSenior(id) {
            // Redirect to an edit page, or open a modal
            // Assuming there's a page like add_senior.php for adding/editing seniors
            window.location.href = `add_senior.php?id=${id}`;
        }

        function deleteSenior(id) {
            if (!confirm('Are you sure you want to mark this senior citizen as deceased? This action cannot be undone.')) {
                return;
            }

            const loadingOverlay = document.getElementById('loading-overlay');
            loadingOverlay.classList.remove('hidden'); // Show spinner

            fetch('../api/seniors/save.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id, update_type: 'deceased', deceased: { date: new Date().toISOString().slice(0, 10) } })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('success', 'Success', 'Senior citizen marked as deceased successfully!');
                    fetchSeniors(); // Refresh the list
                } else {
                    showMessage('error', 'Deletion Error', data.message);
                }
            })
            .catch(error => showMessage('error', 'Deletion Error', 'An unexpected error occurred while marking senior as deceased.'))
            .finally(() => {
                loadingOverlay.classList.add('hidden'); // Hide spinner
            });
        }
    </script>
</body>
</html>