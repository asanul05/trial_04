<?php include 'auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaints - OSCA</title>
    
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
    // Set current page for sidebar highlighting     
    $current_page = basename($_SERVER['PHP_SELF']);     
    ?>

    <?php include 'sidebar.php'; ?>

    <div class="ml-64 w-full min-h-screen flex flex-col">
        
        <?php include 'header.php'; ?>

        <main class="flex-1 p-8 overflow-y-auto">
            
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6 border border-gray-100">
                                
                <div class="mb-6">
                    <h2 class="text-3xl font-bold text-gray-900">Complaint Management</h2>
                    <p class="text-gray-600 mt-1">File and manage complaints for Senior Citizens</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <a href="add_complaint.php" class="bg-dashboardBlue text-white py-3 px-4 rounded-md font-bold hover:bg-indigo-900 transition shadow-md flex items-center justify-center gap-2">
                        <i class="fa-solid fa-file-circle-plus"></i> File Complaint
                    </a>
                    <button class="bg-dashboardBlue text-white py-3 px-4 rounded-md font-bold hover:bg-indigo-900 transition shadow-md">
                        Complaint Report
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                                
                <h3 class="text-lg font-bold text-black mb-4">Complaint List</h3>

                <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
                    <div class="flex items-center gap-2 w-full md:w-auto">
                        <div class="relative w-full md:w-80">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                            </span>
                            <input type="text" id="search-input" placeholder="Search by Complainant, Violator, or ID" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-dashboardBlue text-sm">
                        </div>
                        <button id="search-button" class="p-2 border border-gray-300 rounded hover:bg-gray-50 text-gray-600 transition">
                            <i class="fa-solid fa-search"></i>
                        </button>
                        <button id="refresh-button" class="p-2 border border-gray-300 rounded hover:bg-gray-50 text-gray-600 transition">
                            <i class="fa-solid fa-rotate"></i>
                        </button>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Status:</span>
                        <select id="status-filter" class="p-2 border border-gray-300 rounded text-sm">
                            <option value="">All</option>
                            <!-- Options populated by JS -->
                        </select>
                        <span class="text-sm text-gray-600">Category:</span>
                        <select id="category-filter" class="p-2 border border-gray-300 rounded text-sm">
                            <option value="">All</option>
                            <!-- Options populated by JS -->
                        </select>
                        <span class="text-sm text-gray-600">Show</span>
                        <select id="limit-select" class="p-2 border border-gray-300 rounded text-sm">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <span class="text-sm text-gray-600">entries</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-black font-bold text-sm border-b border-gray-200">
                                <th class="pb-4 pl-2">Complaint ID</th>
                                <th class="pb-4">Complainant</th>
                                <th class="pb-4">Violator</th>
                                <th class="pb-4">Category</th>
                                <th class="pb-4">Status</th>
                                <th class="pb-4">Filed Date</th>
                                <th class="pb-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="complaints-table" class="text-gray-800 text-sm">
                            <!-- Complaint rows will be inserted here by JavaScript -->
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center mt-4">
                    <div id="pagination-info" class="text-sm text-gray-600">
                        Showing 0 to 0 of 0 entries
                    </div>
                    <div class="flex space-x-2">
                        <button id="prev-page-button" class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-100">Previous</button>
                        <div id="page-buttons" class="flex space-x-2">
                            <!-- Page buttons will be inserted here -->
                        </div>
                        <button id="next-page-button" class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-100">Next</button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        let currentPage = 1;
        let currentLimit = 10;
        let currentSearch = '';
        let currentStatusFilter = '';
        let currentCategoryFilter = '';

        document.addEventListener('DOMContentLoaded', async function() {
            const loadingOverlay = document.getElementById('loading-overlay');
            loadingOverlay.classList.remove('hidden');

            await fetchAndPopulateDropdown('../api/complaint_statuses/list.php', document.getElementById('status-filter'), 'All');
            await fetchAndPopulateDropdown('../api/complaint_categories/list.php', document.getElementById('category-filter'), 'All');
            
            fetchComplaints();

            document.getElementById('search-button').addEventListener('click', function() {
                currentSearch = document.getElementById('search-input').value;
                currentPage = 1;
                fetchComplaints();
            });
            document.getElementById('refresh-button').addEventListener('click', function() {
                currentSearch = '';
                document.getElementById('search-input').value = '';
                currentStatusFilter = '';
                document.getElementById('status-filter').value = '';
                currentCategoryFilter = '';
                document.getElementById('category-filter').value = '';
                currentPage = 1;
                fetchComplaints();
            });

            document.getElementById('limit-select').addEventListener('change', function() {
                currentLimit = this.value;
                currentPage = 1;
                fetchComplaints();
            });
            document.getElementById('status-filter').addEventListener('change', function() {
                currentStatusFilter = this.value;
                currentPage = 1;
                fetchComplaints();
            });
            document.getElementById('category-filter').addEventListener('change', function() {
                currentCategoryFilter = this.value;
                currentPage = 1;
                fetchComplaints();
            });

            document.getElementById('prev-page-button').addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    fetchComplaints();
                }
            });

            document.getElementById('next-page-button').addEventListener('click', function() {
                currentPage++;
                fetchComplaints();
            });

            loadingOverlay.classList.add('hidden'); // Hide spinner after initial load
        });

        async function fetchAndPopulateDropdown(apiEndpoint, selectElement, defaultOptionText) {
            const response = await fetch(apiEndpoint);
            const data = await response.json();
            if (data.success) {
                selectElement.innerHTML = `<option value="">${defaultOptionText}</option>`;
                data.data.forEach(item => {
                    selectElement.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                });
            } else {
                showMessage('error', 'Fetch Error', `Error fetching data from ${apiEndpoint}: ${data.message}`);
                console.error(`Error fetching data from ${apiEndpoint}:`, data.message);
            }
        }

        async function fetchComplaints() {
            const loadingOverlay = document.getElementById('loading-overlay');
            loadingOverlay.classList.remove('hidden'); // Show spinner

            let url = `../api/complaints/list.php?page=${currentPage}&limit=${currentLimit}`;
            if (currentSearch) {
                url += `&search=${currentSearch}`;
            }
            if (currentStatusFilter) {
                url += `&status_id=${currentStatusFilter}`;
            }
            if (currentCategoryFilter) {
                url += `&category_id=${currentCategoryFilter}`;
            }

            try {
                const response = await fetch(url);
                const data = await response.json();

                if (data.success) {
                    const tableBody = document.getElementById('complaints-table');
                    tableBody.innerHTML = '';
                    data.data.forEach(complaint => {
                        const statusColor = complaint.status_color || '#cccccc'; // Default grey
                        const row = `
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="py-4 pl-2 font-medium">${complaint.complaint_number}</td>
                                <td class="py-4">${complaint.complainant_first_name} ${complaint.complainant_last_name}</td>
                                <td class="py-4">${complaint.violator_name}</td>
                                <td class="py-4">${complaint.category_name}</td>
                                <td class="py-4"><span class="px-2.5 py-0.5 rounded-full text-xs font-semibold" style="background-color: ${statusColor}1A; color: ${statusColor};">${complaint.status_name}</span></td>
                                <td class="py-4">${new Date(complaint.filed_date).toLocaleDateString()}</td>
                                <td class="py-4 text-center">
                                    <button class="px-4 py-1.5 border border-gray-300 text-gray-700 rounded hover:bg-gray-100 transition text-xs font-semibold" onclick="editComplaint(${complaint.id})">Edit</button>
                                    <button class="px-4 py-1.5 border border-red-500 text-red-500 rounded hover:bg-red-50 transition text-xs font-semibold" onclick="deleteComplaint(${complaint.id})">Delete</button>
                                </td>
                            </tr>
                        `;
                        tableBody.innerHTML += row;
                    });

                    // Update pagination info
                    const paginationInfo = document.getElementById('pagination-info');
                    const startEntry = (data.pagination.page - 1) * data.pagination.limit + 1;
                    const endEntry = Math.min(data.pagination.page * data.pagination.limit, data.pagination.total);
                    paginationInfo.textContent = `Showing ${startEntry} to ${endEntry} of ${data.pagination.total} entries`;

                    // Update page buttons
                    const pageButtonsContainer = document.getElementById('page-buttons');
                    pageButtonsContainer.innerHTML = '';
                    for (let i = 1; i <= data.pagination.pages; i++) {
                        const button = `<button class="px-3 py-1 border border-gray-300 rounded text-sm ${i === data.pagination.page ? 'bg-dashboardBlue text-white' : 'hover:bg-gray-100'}" onclick="goToPage(${i})">${i}</button>`;
                        pageButtonsContainer.innerHTML += button;
                    }

                    document.getElementById('prev-page-button').disabled = data.pagination.page === 1;
                    document.getElementById('next-page-button').disabled = data.pagination.page === data.pagination.pages;

                } else {
                    showMessage('error', 'Fetch Error', data.message);
                }
            } catch (error) {
                showMessage('error', 'Fetch Error', 'An unexpected error occurred while fetching complaints.');
                console.error('Error fetching complaints:', error);
            } finally {
                loadingOverlay.classList.add('hidden'); // Hide spinner
            }
        }

        function goToPage(page) {
            currentPage = page;
            fetchComplaints();
        }

        function editComplaint(id) {
            window.location.href = `add_complaint.php?id=${id}`;
        }

        function deleteComplaint(id) {
            if (!confirm('Are you sure you want to delete this complaint? This action cannot be undone.')) {
                return;
            }

            const loadingOverlay = document.getElementById('loading-overlay');
            loadingOverlay.classList.remove('hidden');

            fetch(`../api/complaints/delete.php?id=${id}`, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('success', 'Success', 'Complaint deleted successfully!');
                    fetchComplaints(); // Refresh the list
                } else {
                    showMessage('error', 'Deletion Error', data.message);
                }
            })
            .catch(error => showMessage('error', 'Deletion Error', 'An unexpected error occurred while deleting complaint.'))
            .finally(() => {
                loadingOverlay.classList.add('hidden');
            });
        }
</body>
</html>