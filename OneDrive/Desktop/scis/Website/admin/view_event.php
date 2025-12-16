<?php include 'auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Event - OSCA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body class="bg-osca-bg font-sans flex text-gray-800">
    <div id="loading-overlay" class="loading-overlay hidden">
        <div class="spinner"></div>
    </div>

    <?php
    $current_page = 'announcement.php';
    ?>
    <?php include 'sidebar.php'; ?>
    <div class="ml-64 w-full min-h-screen flex flex-col relative">
        <?php include 'header.php'; ?>
        <main class="flex-1 p-8 overflow-y-auto flex items-center justify-center">
            <div id="event-details-container" class="w-full max-w-5xl bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
                <div class="bg-dashboardBlue p-6 relative">
                    <a href="announcement.php" class="absolute top-6 right-6 w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-white hover:bg-white/30 transition cursor-pointer">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </a>
                    <div class="flex items-center gap-4 mb-3">
                        <h1 id="event-title" class="text-3xl font-bold text-white">Loading Event...</h1>
                    </div>
                    <div class="flex items-center gap-4 text-white/80 text-sm">
                        <span id="event-date"><i class="fa-solid fa-calendar-alt mr-2"></i></span>
                        <span id="event-time"><i class="fa-solid fa-clock mr-2"></i></span>
                        <span id="event-location"><i class="fa-solid fa-map-marker-alt mr-2"></i></span>
                    </div>
                </div>

                <div class="p-6">
                    <p id="event-description" class="text-gray-700 mb-6"></p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-6">
                        <div>
                            <p class="font-semibold text-gray-800">Event Type:</p>
                            <p id="event-type" class="text-gray-600"></p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Target Audience:</p>
                            <p id="event-target-audience" class="text-gray-600"></p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Created By:</p>
                            <p id="event-created-by" class="text-gray-600"></p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Published Status:</p>
                            <p id="event-published-status" class="text-gray-600"></p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button id="register-senior-btn" class="bg-green-500 text-white px-6 py-2 rounded-md font-bold hover:bg-green-600 transition shadow-md">
                            <i class="fa-solid fa-user-plus mr-2"></i> Register Senior
                        </button>
                    </div>

                    <h3 class="font-bold text-gray-800 text-lg mt-8 mb-4">Registered Participants (<span id="participant-count">0</span>)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead>
                                <tr class="bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <th class="px-6 py-3 border-b-2 border-gray-200">Senior Name</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-200">OSCA ID</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-200">Registered Date</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-200">Attended</th>
                                    <th class="px-6 py-3 border-b-2 border-gray-200 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="participants-table" class="divide-y divide-gray-200">
                                <!-- Participants will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Register Senior Modal -->
    <div id="register-senior-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-bold mb-4">Register Senior to Event</h3>
            <div class="mb-4">
                <label for="modal-senior-search" class="block text-sm font-medium text-gray-700 mb-1">Search Senior by Name or OSCA ID</label>
                <input type="text" id="modal-senior-search" class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div class="max-h-60 overflow-y-auto border border-gray-300 rounded-md mb-4">
                <ul id="modal-senior-results" class="divide-y divide-gray-200">
                    <!-- Search results here -->
                </ul>
            </div>
            <div id="modal-senior-error" class="text-red-500 text-sm mb-4"></div>
            <div class="flex justify-end gap-3">
                <button type="button" id="close-modal-btn" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md">Cancel</button>
                <button type="button" id="confirm-register-btn" class="px-4 py-2 bg-blue-500 text-white rounded-md hidden">Register</button>
            </div>
        </div>
    </div>

    <script>
        const loadingOverlay = document.getElementById('loading-overlay');
        const eventDetailsContainer = document.getElementById('event-details-container');
        const registerSeniorModal = document.getElementById('register-senior-modal');
        const modalSeniorSearch = document.getElementById('modal-senior-search');
        const modalSeniorResults = document.getElementById('modal-senior-results');
        const modalSeniorError = document.getElementById('modal-senior-error');
        const confirmRegisterBtn = document.getElementById('confirm-register-btn');
        const closeRegisterModalBtn = document.getElementById('close-modal-btn');
        let selectedSeniorId = null;
        let currentEventId = null;

        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            currentEventId = urlParams.get('id');

            if (currentEventId) {
                fetchEventDetails(currentEventId);
            } else {
                eventDetailsContainer.innerHTML = '<p class="p-8 text-center text-red-500">Event ID not provided.</p>';
                loadingOverlay.classList.add('hidden');
            }

            document.getElementById('register-senior-btn').addEventListener('click', function() {
                registerSeniorModal.classList.remove('hidden');
                modalSeniorSearch.value = '';
                modalSeniorResults.innerHTML = '';
                selectedSeniorId = null;
                confirmRegisterBtn.classList.add('hidden');
                modalSeniorError.textContent = '';
            });

            closeRegisterModalBtn.addEventListener('click', function() {
                registerSeniorModal.classList.add('hidden');
            });

            modalSeniorSearch.addEventListener('input', debounce(async function() {
                const searchTerm = this.value;
                if (searchTerm.length > 2) {
                    loadingOverlay.classList.remove('hidden');
                    try {
                        const response = await fetch(`../api/seniors/list.php?search=${searchTerm}&limit=10`);
                        const data = await response.json();
                        modalSeniorResults.innerHTML = '';
                        if (data.success && data.data.length > 0) {
                            data.data.forEach(senior => {
                                const li = document.createElement('li');
                                li.className = 'p-3 hover:bg-gray-100 cursor-pointer';
                                li.textContent = `${senior.first_name} ${senior.last_name} (${senior.osca_id})`;
                                li.dataset.seniorId = senior.id;
                                li.addEventListener('click', () => {
                                    selectedSeniorId = senior.id;
                                    modalSeniorSearch.value = li.textContent;
                                    modalSeniorResults.innerHTML = '';
                                    confirmRegisterBtn.classList.remove('hidden');
                                });
                                modalSeniorResults.appendChild(li);
                            });
                        } else {
                            modalSeniorResults.innerHTML = '<li class="p-3 text-gray-500">No seniors found.</li>';
                        }
                    } catch (error) {
                        showMessage('error', 'Search Error', 'Error searching seniors.');
                        console.error('Error searching seniors:', error);
                    } finally {
                        loadingOverlay.classList.add('hidden');
                    }
                } else {
                    modalSeniorResults.innerHTML = '';
                    selectedSeniorId = null;
                    confirmRegisterBtn.classList.add('hidden');
                }
            }, 300));

            confirmRegisterBtn.addEventListener('click', async function() {
                if (selectedSeniorId && currentEventId) {
                    loadingOverlay.classList.remove('hidden');
                    try {
                        const response = await fetch('../api/announcements/register_senior.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ announcement_id: currentEventId, senior_id: selectedSeniorId })
                        });
                        const data = await response.json();
                        if (data.success) {
                            showMessage('success', 'Success', 'Senior registered successfully!');
                            registerSeniorModal.classList.add('hidden');
                            fetchEventDetails(currentEventId); // Refresh participants list
                        } else {
                            showMessage('error', 'Registration Error', data.message);
                        }
                    } catch (error) {
                        showMessage('error', 'Registration Error', 'An unexpected error occurred.');
                        console.error('Error registering senior:', error);
                    } finally {
                        loadingOverlay.classList.add('hidden');
                    }
                }
            });
        });

        async function fetchEventDetails(id) {
            loadingOverlay.classList.remove('hidden');
            try {
                const response = await fetch(`../api/announcements/details.php?id=${id}`);
                const data = await response.json();
                if (data.success) {
                    const event = data.data;
                    document.getElementById('event-title').textContent = event.title;
                    document.getElementById('event-date').innerHTML = `<i class="fa-solid fa-calendar-alt mr-2"></i>${event.event_date ? new Date(event.event_date).toLocaleDateString() : 'N/A'}`;
                    document.getElementById('event-time').innerHTML = `<i class="fa-solid fa-clock mr-2"></i>${event.event_time ? event.event_time.substring(0, 5) : 'N/A'}`;
                    document.getElementById('event-location').innerHTML = `<i class="fa-solid fa-map-marker-alt mr-2"></i>${event.location || 'N/A'}`;
                    document.getElementById('event-description').textContent = event.description;
                    document.getElementById('event-type').textContent = event.type_name;
                    document.getElementById('event-target-audience').textContent = event.target_audience || 'All';
                    document.getElementById('event-created-by').textContent = event.created_by_name || 'N/A';
                    document.getElementById('event-published-status').textContent = event.is_published ? 'Published' : 'Draft';
                    
                    // Fetch and populate participants
                    await fetchEventParticipants(id);

                } else {
                    showMessage('error', 'Fetch Error', data.message);
                    eventDetailsContainer.innerHTML = `<p class="p-8 text-center text-red-500">${data.message}</p>`; // Fallback to display error in container
                }
            } catch (error) {
                showMessage('error', 'Fetch Error', 'An unexpected error occurred while loading event details.');
                console.error('Error fetching event details:', error);
                eventDetailsContainer.innerHTML = '<p class="p-8 text-center text-red-500">An unexpected error occurred while loading event details.</p>'; // Fallback
            } finally {
                loadingOverlay.classList.add('hidden');
            }
        }

        async function fetchEventParticipants(eventId) {
            loadingOverlay.classList.remove('hidden');
            try {
                // Assuming an API endpoint like api/announcements/participants.php
                const response = await fetch(`../api/announcements/participants.php?announcement_id=${eventId}`);
                const data = await response.json();
                const participantsTable = document.getElementById('participants-table');
                participantsTable.innerHTML = '';
                if (data.success && data.data.length > 0) {
                    document.getElementById('participant-count').textContent = data.data.length;
                    data.data.forEach(p => {
                        const row = `
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">${p.senior_first_name} ${p.senior_last_name}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${p.osca_id}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${new Date(p.registered_date).toLocaleDateString()}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${p.attended ? `<span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Yes</span>` : `<span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">No</span>`}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    ${!p.attended ? `<button class="text-blue-600 hover:text-blue-900 text-sm font-medium" onclick="markAttended(${p.id})">Mark Attended</button>` : ''}
                                </td>
                            </tr>
                        `;
                        participantsTable.innerHTML += row;
                    });
                } else {
                    document.getElementById('participant-count').textContent = '0';
                    participantsTable.innerHTML = `<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No participants registered yet.</td></tr>`;
                }
            } catch (error) {
                showMessage('error', 'Fetch Error', 'An unexpected error occurred while fetching participants.');
                console.error('Error fetching participants:', error);
                participantsTable.innerHTML = `<tr><td colspan="5" class="px-6 py-4 text-center text-red-500">Error loading participants.</td></tr>`; // Fallback
            } finally {
                loadingOverlay.classList.add('hidden');
            }
        }

        async function markAttended(participantId) {
            if (!confirm('Are you sure you want to mark this participant as attended?')) {
                return;
            }
            loadingOverlay.classList.remove('hidden');
            try {
                const response = await fetch('../api/announcements/mark_attended.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ participant_id: participantId })
                });
                const data = await response.json();
                if (data.success) {
                    showMessage('success', 'Success', 'Participant marked as attended!');
                    fetchEventDetails(currentEventId); // Refresh event and participants
                } else {
                    showMessage('error', 'Update Error', data.message);
                }
            } catch (error) {
                showMessage('error', 'Update Error', 'An unexpected error occurred.');
                console.error('Error marking attended:', error);
            } finally {
                loadingOverlay.classList.add('hidden');
            }
        }
</body>
</html>