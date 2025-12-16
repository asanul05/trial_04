<?php include 'auth_check.php'; ?>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchAnnouncements();
        });

        function fetchAnnouncements() {
            const loadingOverlay = document.getElementById('loading-overlay');
            loadingOverlay.classList.remove('hidden'); // Show spinner

            fetch('../api/announcements/list.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const tableBody = document.getElementById('announcements-table');
                        tableBody.innerHTML = '';
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
                    }
                })
                .catch(error => showMessage('error', 'Fetch Error', 'Failed to load announcements.'))
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
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('success', 'Success', 'Announcement deleted successfully!');
                    fetchAnnouncements(); // Refresh the list
                } else {
                    showMessage('error', 'Deletion Error', data.message);
                }
            })
            .catch(error => showMessage('error', 'Deletion Error', 'An unexpected error occurred during deletion.'))
            .finally(() => {
                    loadingOverlay.classList.add('hidden'); // Hide spinner
                });
</body>
</html>