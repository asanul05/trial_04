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
    <title>Create Announcement - OSCA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-osca-bg font-sans flex text-gray-800">
    <div id="loading-overlay" class="loading-overlay hidden">
        <div class="spinner"></div>
    </div>
    <?php
    $current_page = 'announcement.php';
    ?>
    <?php include 'sidebar.php'; ?>
    <div class="ml-64 w-full min-h-screen flex flex-col">
        <?php include 'header.php'; ?>
        <main class="flex-1 p-8 overflow-y-auto">
            <div class="mb-4">
                <a href="announcement.php" class="text-gray-500 hover:text-dashboardBlue flex items-center gap-2 transition text-sm font-medium">
                    <i class="fa-solid fa-arrow-left"></i> Back to Announcements
                </a>
            </div>
            <div class="bg-white rounded-lg shadow-md p-8 border border-gray-100">
                <h2 id="form-title" class="text-2xl font-bold text-dashboardBlue mb-8">Create an Announcement</h2>
                <form id="announcement-form">
                    <input type="hidden" id="announcement-id" name="id">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <input type="text" id="title" name="title" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-dashboardBlue focus:border-dashboardBlue" required>
                        </div>
                        <div>
                            <label for="type_id" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select id="type_id" name="type_id" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-dashboardBlue focus:border-dashboardBlue" required>
                                <!-- Options will be populated by JS -->
                            </select>
                        </div>
                        <div>
                            <label for="is_published" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="is_published" name="is_published" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-dashboardBlue focus:border-dashboardBlue" required>
                                <option value="1">Published</option>
                                <option value="0">Draft</option>
                            </select>
                        </div>
                        <div>
                            <label for="event_date" class="block text-sm font-medium text-gray-700 mb-1">Event Date</label>
                            <input type="date" id="event_date" name="event_date" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-dashboardBlue focus:border-dashboardBlue">
                        </div>
                        <div>
                            <label for="event_time" class="block text-sm font-medium text-gray-700 mb-1">Event Time</label>
                            <input type="time" id="event_time" name="event_time" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-dashboardBlue focus:border-dashboardBlue">
                        </div>
                        <div class="md:col-span-2">
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                            <input type="text" id="location" name="location" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-dashboardBlue focus:border-dashboardBlue">
                        </div>
                        <div class="md:col-span-2">
                            <label for="target_audience" class="block text-sm font-medium text-gray-700 mb-1">Target Audience</label>
                            <input type="text" id="target_audience" name="target_audience" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-dashboardBlue focus:border-dashboardBlue">
                        </div>
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="description" name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-dashboardBlue focus:border-dashboardBlue" required></textarea>
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="bg-dashboardBlue text-white py-2 px-6 rounded-md font-bold hover:bg-indigo-900 transition shadow-md">Save Announcement</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const announcementId = urlParams.get('id');
            const form = document.getElementById('announcement-form');
            const formTitle = document.getElementById('form-title');
            const loadingOverlay = document.getElementById('loading-overlay');

            // Fetch announcement types for the select dropdown
            // This should be an API endpoint, but for now we hardcode it
            const typeSelect = document.getElementById('type_id');
            const types = { 1: 'Event', 2: 'News', 3: 'Alert', 4: 'Program' };
            for (const [id, name] of Object.entries(types)) {
                typeSelect.innerHTML += `<option value="${id}">${name}</option>`;
            }

            if (announcementId) {
                formTitle.textContent = 'Edit Announcement';
                document.getElementById('announcement-id').value = announcementId;
                
                loadingOverlay.classList.remove('hidden'); // Show spinner
                fetch(`../api/announcements/details.php?id=${announcementId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const announcement = data.data;
                            document.getElementById('title').value = announcement.title;
                            document.getElementById('type_id').value = announcement.type_id;
                            document.getElementById('is_published').value = announcement.is_published;
                            document.getElementById('event_date').value = announcement.event_date;
                            document.getElementById('event_time').value = announcement.event_time;
                            document.getElementById('location').value = announcement.location;
                            document.getElementById('target_audience').value = announcement.target_audience;
                            document.getElementById('description').value = announcement.description;
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
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());

                loadingOverlay.classList.remove('hidden'); // Show spinner

                fetch('../api/announcements/save.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        showMessage('success', 'Success', 'Announcement saved successfully!', 'announcement.php');
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