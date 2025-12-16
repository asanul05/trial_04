<?php
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    error_reporting(E_ALL);

    include 'auth_check.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcement - OSCA</title>
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
    <link rel="stylesheet" href="../css/style.css">
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
                     <h2 class="text-3xl font-bold text-gray-900">Announcement</h2>
                     <p class="text-gray-600 mt-1">Create and manage announcement</p>
                 </div>

                  <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                     <a href="add_announcement.php" class="bg-dashboardBlue text-white py-3 px-4 rounded-md font-bold hover:bg-indigo-900 transition shadow-md flex items-center justify-center gap-2">
                         <i class="fas fa-plus-circle"></i>
                         Create New
                     </a>
                 </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-black mb-6">Announcements List</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-black font-bold text-sm border-b border-gray-200">
                                <th class="pb-4 pl-2">Title</th>
                                <th class="pb-4">Type</th>
                                <th class="pb-4">Event Date</th>
                                <th class="pb-4">Status</th>
                                <th class="pb-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="announcements-table" class="text-gray-800 text-sm">
                            <!-- Rows will be inserted here by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div id="error-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-sm w-full mx-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-red-600">Error!</h3>
                <button id="close-error-modal" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
            <div class="text-gray-700 mb-4">
                <p id="error-modal-message"></p>
                <p id="error-modal-db-status" class="text-sm mt-2"></p>
                <p id="error-modal-api-status" class="text-sm"></p>
            </div>
            <div class="flex justify-end">
                <button id="ok-error-modal" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">OK</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchAnnouncements();

            // Modal event listeners
            const errorModal = document.getElementById('error-modal');
            const closeErrorModalBtn = document.getElementById('close-error-modal');
            const okErrorModalBtn = document.getElementById('ok-error-modal');

            if (closeErrorModalBtn) {
                closeErrorModalBtn.addEventListener('click', hideErrorModal);
            }
            if (okErrorModalBtn) {
                okErrorModalBtn.addEventListener('click', hideErrorModal);
            }
            if (errorModal) {
                errorModal.addEventListener('click', function(event) {
                    if (event.target === errorModal) {
                        hideErrorModal();
                    }
                });
            }
        });

        function showErrorModal(message, isDbConnected = null, isApiWorking = null) {
            const errorModal = document.getElementById('error-modal');
            const errorMessageElem = document.getElementById('error-modal-message');
            const dbStatusElem = document.getElementById('error-modal-db-status');
            const apiStatusElem = document.getElementById('error-modal-api-status');

            errorMessageElem.textContent = message;

            if (isDbConnected !== null) {
                dbStatusElem.textContent = 'Database Connected: ' + (isDbConnected ? 'Yes' : 'No');
                dbStatusElem.style.color = isDbConnected ? 'green' : 'red';
            } else {
                dbStatusElem.textContent = '';
            }

            if (isApiWorking !== null) {
                apiStatusElem.textContent = 'API Working: ' + (isApiWorking ? 'Yes' : 'No');
                apiStatusElem.style.color = isApiWorking ? 'green' : 'red';
            } else {
                apiStatusElem.textContent = '';
            }
            
            errorModal.classList.remove('hidden');
        }

        function hideErrorModal() {
            const errorModal = document.getElementById('error-modal');
            errorModal.classList.add('hidden');
        }

        function fetchAnnouncements() {
            const loadingOverlay = document.getElementById('loading-overlay');
            loadingOverlay.classList.remove('hidden'); // Show spinner
            const announcementsTable = document.getElementById('announcements-table');
            announcementsTable.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-gray-500">Loading announcements...</td></tr>`;


            fetch('../api/announcements/list.php')
                .then(response => {
                    if (!response.ok) { // Check for HTTP errors
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const tableBody = document.getElementById('announcements-table');
                        tableBody.innerHTML = '';
                        if (data.data.length > 0) {
                            data.data.forEach(announcement => {
                                const row = `
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                        <td class="py-4 pl-2 font-medium">${announcement.title}</td>
                                        <td class="py-4">${announcement.type_name}</td>
                                        <td class="py-4">${announcement.event_date ? new Date(announcement.event_date).toLocaleDateString() : 'N/A'}</td>
                                        <td class="py-4">${announcement.is_published ? '<span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">Published</span>' : '<span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">Draft</span>'}</td>
                                        <td class="py-4 text-center">
                                            <button class="px-4 py-1.5 border border-gray-300 text-gray-700 rounded hover:bg-gray-100 transition text-xs font-semibold" onclick="editAnnouncement(${announcement.id})">Edit</button>
                                            <button class="px-4 py-1.5 border border-red-500 text-red-500 rounded hover:bg-red-50 transition text-xs font-semibold" onclick="deleteAnnouncement(${announcement.id})">Delete</button>
                                        </td>
                                    </tr>
                                `;
                                tableBody.innerHTML += row;
                            });
                        } else {
                            tableBody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-gray-500">No announcements found.</td></tr>`;
                        }
                    } else {
                        let isDbConnected = true;
                        let apiMessage = data.message || 'An unknown API error occurred while fetching announcements.';
                        if (apiMessage.includes("Database connection error") || apiMessage.includes("SQLSTATE")) {
                            isDbConnected = false;
                        }
                        showErrorModal(apiMessage, isDbConnected, true);
                        announcementsTable.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-red-500">Failed to load announcements.</td></tr>`;
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    announcementsTable.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-red-500">Network or parsing error loading announcements.</td></tr>`;
                    showErrorModal('Network or parsing error loading announcements: ' + error.message, false, false);
                })
                .finally(() => {
                    loadingOverlay.classList.add('hidden'); // Hide spinner
                });
        }

        function editAnnouncement(id) {
            // Redirect to an edit page, or open a modal
            window.location.href = `add_announcement.php?id=${id}`;
        }

        function deleteAnnouncement(id) {
            if (!confirm('Are you sure you want to delete this announcement?')) {
                return;
            }

            const loadingOverlay = document.getElementById('loading-overlay');
            loadingOverlay.classList.remove('hidden'); // Show spinner

            fetch(`../api/announcements/delete.php?id=${id}`, {
                method: 'DELETE'
            })
            .then(response => {
                if (!response.ok) { // Check for HTTP errors
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Assuming a showMessage function exists globally or is implemented here
                    // For now, we'll use showErrorModal for consistency if it's a critical success notification
                    // In a real app, you'd likely have a dedicated success toast/modal
                    showErrorModal('Announcement deleted successfully!', true, true); // Reusing error modal for success ack for now
                    fetchAnnouncements(); // Refresh the list
                } else {
                    let isDbConnected = true;
                    let apiMessage = data.message || 'An unknown API error occurred during deletion.';
                    if (apiMessage.includes("Database connection error") || apiMessage.includes("SQLSTATE")) {
                        isDbConnected = false;
                    }
                    showErrorModal(apiMessage, isDbConnected, true);
                }
            })
            .catch(error => {
                console.error('Deletion Error:', error);
                showErrorModal('Network or parsing error during deletion: ' + error.message, false, false);
            })
            .finally(() => {
                    loadingOverlay.classList.add('hidden'); // Hide spinner
                });
        }
    </script>
</body>
</html>