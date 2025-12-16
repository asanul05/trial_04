<?php include 'auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Logs - OSCA</title>
    
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
                    <h2 class="text-3xl font-bold text-gray-900">Access Logs</h2>
                    <p class="text-gray-600 mt-1">View user access activity</p>
                </div>

            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                                
                <h3 class="text-lg font-bold text-black mb-4">Access Log Entries</h3>

                <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
                    <div class="flex items-center gap-2 w-full md:w-auto">
                        <div class="relative w-full md:w-80">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                                </span>
                            <input type="text" id="search-input" placeholder="Search by Username, Action, IP Address" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-dashboardBlue text-sm">
                        </div>
                        <button id="search-button" class="p-2 border border-gray-300 rounded hover:bg-gray-50 text-gray-600 transition">
                            <i class="fa-solid fa-search"></i>
                        </button>
                    </div>

                    <div class="flex items-center gap-2">
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
                                <th class="pb-4 pl-2">User</th>
                                <th class="pb-4">Action</th>
                                <th class="pb-4">IP Address</th>
                                <th class="pb-4">User Agent</th>
                                <th class="pb-4">Browser</th>
                                <th class="pb-4">Device</th>
                                <th class="pb-4">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody id="access-logs-table" class="text-gray-800 text-sm">
                            <!-- Access log rows will be inserted here by JavaScript -->
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

        document.addEventListener('DOMContentLoaded', function() {
            fetchAccessLogs();

            document.getElementById('search-button').addEventListener('click', function() {
                currentSearch = document.getElementById('search-input').value;
                currentPage = 1;
                fetchAccessLogs();
            });

            document.getElementById('limit-select').addEventListener('change', function() {
                currentLimit = this.value;
                currentPage = 1;
                fetchAccessLogs();
            });

            document.getElementById('prev-page-button').addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    fetchAccessLogs();
                }
            });

            document.getElementById('next-page-button').addEventListener('click', function() {
                currentPage++; // This will be validated by the API response total pages
                fetchAccessLogs();
            });
        });

        async function fetchAccessLogs() {
            const loadingOverlay = document.getElementById('loading-overlay');
            loadingOverlay.classList.remove('hidden'); // Show spinner

            let url = `../api/access_logs/list.php?page=${currentPage}&limit=${currentLimit}`;
            if (currentSearch) {
                url += `&search=${currentSearch}`;
            }

            try {
                const response = await fetch(url);
                const data = await response.json();

                if (data.success) {
                    const tableBody = document.getElementById('access-logs-table');
                    tableBody.innerHTML = '';
                    data.data.forEach(log => {
                        const row = `
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="py-4 pl-2 font-medium">${log.username || 'N/A'}</td>
                                <td class="py-4">${log.action}</td>
                                <td class="py-4">${log.ip_address}</td>
                                <td class="py-4 text-xs">${log.user_agent ? log.user_agent.substring(0, 50) + '...' : 'N/A'}</td>
                                <td class="py-4">${log.browser}</td>
                                <td class="py-4">${log.device}</td>
                                <td class="py-4">${new Date(log.timestamp).toLocaleString()}</td>
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
                    document.getElementById('next-page_button').disabled = data.pagination.page === data.pagination.pages;

                } else {
                    showMessage('error', 'Fetch Error', data.message);
                }
            } catch (error) {
                showMessage('error', 'Fetch Error', 'An unexpected error occurred while fetching access logs.');
                console.error('Error fetching access logs:', error);
            } finally {
                loadingOverlay.classList.add('hidden'); // Hide spinner
            }
        }

        function goToPage(page) {
            currentPage = page;
            fetchAccessLogs();
        }
    </script>
</body>
</html>