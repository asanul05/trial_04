<?php include 'auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Printing - OSCA</title>
    
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
    $current_page = basename($_SERVER['PHP_SELF']); 
    ?>

    <?php include 'sidebar.php'; ?>

    <div class="ml-64 w-full min-h-screen flex flex-col">
        
        <?php include 'header.php'; ?>

        <main class="flex-1 p-8 overflow-y-auto">
            
            <div class="bg-white p-6 rounded-lg shadow-sm mb-6 border border-gray-100">
                                
                <div class="mb-6">
                    <h2 class="text-3xl font-bold text-gray-900">ID Printing</h2>
                    <p class="text-gray-600 mt-1">Print Senior Citizen's ID</p>
                </div>

            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                                
                <h3 class="text-lg font-bold text-black mb-4">Applications for ID Printing</h3>

                <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                    <div class="flex items-center gap-2 w-full md:w-auto">
                        <div class="relative w-full md:w-96">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                            </span>
                            <input type="text" id="search-input" placeholder="Search by name or OSCA ID" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-dashboardBlue text-sm">
                        </div>
                        <button id="search-button" class="p-2.5 border border-gray-300 rounded-md hover:bg-gray-50 text-gray-600 transition" title="Search">
                            <i class="fa-solid fa-search"></i>
                        </button>
                        <button id="refresh-button" class="p-2.5 border border-gray-300 rounded-md hover:bg-gray-50 text-gray-600 transition" title="Refresh List">
                            <i class="fa-solid fa-rotate"></i>
                        </button>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Status:</span>
                        <select id="status-filter" class="p-2 border border-gray-300 rounded text-sm">
                            <option value="">All</option>
                            <option value="Approved">Approved</option>
                            <option value="Verified">Verified</option>
                            <option value="Printed">Printed</option>
                            <option value="Claimed">Claimed</option>
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
                                <th class="pb-4 pl-2">Application ID</th>
                                <th class="pb-4">OSCA ID</th>
                                <th class="pb-4">Senior Name</th>
                                <th class="pb-4">Application Type</th>
                                <th class="pb-4">Status</th>
                                <th class="pb-4">Submission Date</th>
                                <th class="pb-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="id-printing-table" class="text-gray-800 text-sm">
                            <!-- ID printing applications will be inserted here by JavaScript -->
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

        document.addEventListener('DOMContentLoaded', function() {
            fetchIdPrintingList();

            document.getElementById('search-button').addEventListener('click', function() {
                currentSearch = document.getElementById('search-input').value;
                currentPage = 1;
                fetchIdPrintingList();
            });
            document.getElementById('refresh-button').addEventListener('click', function() {
                currentSearch = '';
                document.getElementById('search-input').value = '';
                currentStatusFilter = '';
                document.getElementById('status-filter').value = '';
                currentPage = 1;
                fetchIdPrintingList();
            });

            document.getElementById('limit-select').addEventListener('change', function() {
                currentLimit = this.value;
                currentPage = 1;
                fetchIdPrintingList();
            });
            document.getElementById('status-filter').addEventListener('change', function() {
                currentStatusFilter = this.value;
                currentPage = 1;
                fetchIdPrintingList();
            });

            document.getElementById('prev-page-button').addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    fetchIdPrintingList();
                }
            });

            document.getElementById('next-page-button').addEventListener('click', function() {
                currentPage++;
                fetchIdPrintingList();
            });
        });

        async function fetchIdPrintingList() {
            const loadingOverlay = document.getElementById('loading-overlay');
            loadingOverlay.classList.remove('hidden'); // Show spinner

            let url = `../api/id_printing/list.php?page=${currentPage}&limit=${currentLimit}`;
            if (currentSearch) {
                url += `&search=${currentSearch}`;
            }
            if (currentStatusFilter) {
                url += `&status=${currentStatusFilter}`;
            }

            try {
                const response = await fetch(url);
                const data = await response.json();

                if (data.success) {
                    const tableBody = document.getElementById('id-printing-table');
                    tableBody.innerHTML = '';
                    data.data.forEach(app => {
                        const statusClass = getStatusClass(app.application_status);
                        const row = `
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="py-4 pl-2 font-medium">${app.application_number}</td>
                                <td class="py-4">${app.osca_id}</td>
                                <td class="py-4">${app.first_name} ${app.last_name}</td>
                                <td class="py-4">${app.application_type_name}</td>
                                <td class="py-4"><span class="px-2.5 py-0.5 rounded-full text-xs font-semibold ${statusClass}">${app.application_status}</span></td>
                                <td class="py-4">${new Date(app.submission_date).toLocaleDateString()}</td>
                                <td class="py-4 text-center">
                                    <button class="px-4 py-1.5 border border-gray-300 text-gray-700 rounded hover:bg-gray-100 transition text-xs font-semibold" onclick="printId(${app.application_id})">Print</button>
                                    ${app.application_status === 'Printed' ? `<button class="px-4 py-1.5 border border-green-500 text-green-500 rounded hover:bg-green-50 transition text-xs font-semibold" onclick="markClaimed(${app.application_id})">Mark Claimed</button>` : ''}
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
                showMessage('error', 'Fetch Error', 'An unexpected error occurred while fetching ID printing list.');
                console.error('Error fetching ID printing list:', error);
            } finally {
                loadingOverlay.classList.add('hidden');
            }
        }

        function getStatusClass(status) {
            switch (status) {
                case 'Approved': return 'bg-blue-100 text-blue-800';
                case 'Verified': return 'bg-indigo-100 text-indigo-800';
                case 'Printed': return 'bg-purple-100 text-purple-800';
                case 'Claimed': return 'bg-green-100 text-green-800';
                default: return 'bg-gray-100 text-gray-800';
            }
        }

        function goToPage(page) {
            currentPage = page;
            fetchIdPrintingList();
        }

        function printId(applicationId) {
            showMessage('success', 'Print Action', 'Initiating print for application: ' + applicationId);
            // Implement actual printing logic here, possibly opening a new window with print-ready content
        }

        async function markClaimed(applicationId) {
            if (!confirm('Are you sure you want to mark this ID as Claimed?')) {
                return;
            }

            const loadingOverlay = document.getElementById('loading-overlay');
            loadingOverlay.classList.remove('hidden');

            try {
                const response = await fetch('../api/id_printing/mark_claimed.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ application_id: applicationId })
                });
                const data = await response.json();

                if (data.success) {
                    showMessage('success', 'Success', 'ID marked as claimed successfully!');
                    fetchIdPrintingList(); // Refresh the list
                } else {
                    showMessage('error', 'Update Error', data.message);
                }
            } catch (error) {
                showMessage('error', 'Update Error', 'An unexpected error occurred while marking ID as claimed.');
                console.error('Error marking ID as claimed:', error);
            } finally {
                loadingOverlay.classList.add('hidden');
            }
        }
    </script>
</body>
</html>