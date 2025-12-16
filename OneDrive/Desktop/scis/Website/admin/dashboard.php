<?php include 'auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zamboanga City OSCA Dashboard</title>
    
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
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="../../css/style.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mainContent = document.getElementById('main-content');
            const dbStatus = document.getElementById('db-status');
            const loadingOverlay = document.getElementById('loading-overlay');

            loadingOverlay.classList.remove('hidden'); // Show spinner

            fetch('../api/health/status.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        dbStatus.style.display = 'none';
                        mainContent.style.display = 'block';
                        fetchDashboardData(); 
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    mainContent.style.display = 'none';
                    dbStatus.style.display = 'block'; // Keep dbStatus visible if it's acting as a container
                    showMessage('error', 'Connection Error!', error.message || 'Could not connect to the database.');
                })
                .finally(() => {
                    loadingOverlay.classList.add('hidden'); // Hide spinner
                });
        });

        function fetchDashboardData() {
            const loadingOverlay = document.getElementById('loading-overlay');
            loadingOverlay.classList.remove('hidden'); // Show spinner for data fetch

            fetch('../api/dashboard/summary.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const summary = data.data;
                        document.getElementById('active-seniors').textContent = summary.active_seniors;
                        document.getElementById('pending-applications').textContent = summary.pending_applications;
                        document.getElementById('claimable-ids').textContent = summary.claimable_ids;
                        document.getElementById('released-ids').textContent = summary.released_ids;

                        const eventsTable = document.getElementById('events-table');
                        eventsTable.innerHTML = ''; // Clear existing rows
                        summary.upcoming_events.forEach(event => {
                            const row = `
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                    <td class="py-4 pl-2 font-medium">${event.title}</td>
                                    <td class="py-4">${event.type_name}</td>
                                    <td class="py-4">${event.event_date ? new Date(event.event_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'N/A'} <span class="ml-2 text-gray-500">${event.event_time ? event.event_time.substring(0, 5) : ''}</span></td>
                                    <td class="py-4">${event.location}</td>
                                    <td class="py-4 text-center">
                                        <button class="px-6 py-1.5 border border-dashboardBlue text-dashboardBlue rounded hover:bg-indigo-50 transition text-xs font-semibold">View</button>
                                    </td>
                                </tr>
                            `;
                            eventsTable.innerHTML += row;
                        });
                    }
                })
                .catch(error => {
                    showMessage('error', 'Fetch Error', 'Failed to load dashboard data.');
                })
                .finally(() => {
                    loadingOverlay.classList.add('hidden'); // Hide spinner
                });
        }
    </script>
</head>
<body class="bg-gray-100 font-sans flex text-gray-800">
    <div id="loading-overlay" class="loading-overlay hidden">
        <div class="spinner"></div>
    </div>

    <?php 
    // This sets the active page for the sidebar logic
    $current_page = basename($_SERVER['PHP_SELF']); 
    ?>

    <?php include 'sidebar.php'; ?>

    <div class="ml-64 w-full min-h-screen flex flex-col">
        
        <?php include 'header.php'; ?>

        <main class="flex-1 p-8 overflow-y-auto">
            <div id="db-status" style="display: none;"></div>
            <div id="main-content" style="display: none;">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    
                    <div class="bg-white rounded-xl shadow-md p-6 flex items-center h-40 relative overflow-hidden group hover:shadow-lg transition">
                        <div class="absolute left-6 top-1/2 transform -translate-y-1/2 h-24 w-2.5 bg-dashboardBlue rounded-full"></div>
                        <div class="ml-8">
                            <h2 id="active-seniors" class="text-5xl font-bold text-osca-text mb-2">...</h2>
                            <p class="text-gray-800 font-medium text-lg leading-tight">Registered Active Senior Citizen<br>as of Today</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6 flex items-center h-40 relative overflow-hidden group hover:shadow-lg transition">
                        <div class="absolute left-6 top-1/2 transform -translate-y-1/2 h-24 w-2.5 bg-dashboardBlue rounded-full"></div>
                        <div class="ml-8">
                            <h2 id="pending-applications" class="text-5xl font-bold text-osca-text mb-2">...</h2>
                            <p class="text-gray-800 font-medium text-lg">Pending Applications</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6 flex items-center h-40 relative overflow-hidden group hover:shadow-lg transition">
                        <div class="absolute left-6 top-1/2 transform -translate-y-1/2 h-24 w-2.5 bg-dashboardBlue rounded-full"></div>
                        <div class="ml-8">
                            <h2 id="claimable-ids" class="text-5xl font-bold text-osca-text mb-2">...</h2>
                            <p class="text-gray-800 font-medium text-lg">ID Claimable as of Today</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6 flex items-center h-40 relative overflow-hidden group hover:shadow-lg transition">
                        <div class="absolute left-6 top-1/2 transform -translate-y-1/2 h-24 w-2.5 bg-dashboardBlue rounded-full"></div>
                        <div class="ml-8">
                            <h2 id="released-ids" class="text-5xl font-bold text-osca-text mb-2">...</h2>
                            <p class="text-gray-800 font-medium text-lg">Released ID's to Seniors</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-black mb-6">Upcoming Events</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-black font-bold text-sm border-b border-gray-200">
                                    <th class="pb-4 pl-2">Event Name</th>
                                    <th class="pb-4">Target Audience <i class="fa-solid fa-sort text-gray-400 ml-1 cursor-pointer hover:text-gray-600"></i></th>
                                    <th class="pb-4">Date Event <i class="fa-solid fa-sort text-gray-400 ml-1 cursor-pointer hover:text-gray-600"></i></th>
                                    <th class="pb-4">Location <i class="fa-solid fa-sort text-gray-400 ml-1 cursor-pointer hover:text-gray-600"></i></th>
                                    <th class="pb-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="events-table" class="text-gray-800 text-sm">
                                <!-- Rows will be inserted here by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>