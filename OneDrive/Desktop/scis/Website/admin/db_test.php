<?php include 'auth_check.php'; ?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database & API Test - OSCA Admin</title>
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
                    <h2 class="text-3xl font-bold text-gray-900">Database & API Test Page</h2>
                    <p class="text-gray-600 mt-1">Test the connectivity of the database and various API endpoints.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h3 class="text-xl font-bold mb-4">Database Health Check</h3>
                        <button id="test-db-health" class="bg-dashboardBlue text-white py-2 px-4 rounded-md font-bold hover:bg-indigo-900 transition shadow-md">
                            Test DB Connection
                        </button>
                        <div id="db-health-status" class="mt-4 p-3 rounded-md text-sm hidden"></div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h3 class="text-xl font-bold mb-4">Announcements API Test</h3>
                        <button id="test-announcements-api" class="bg-dashboardBlue text-white py-2 px-4 rounded-md font-bold hover:bg-indigo-900 transition shadow-md">
                            Test Announcements List
                        </button>
                        <div id="announcements-api-status" class="mt-4 p-3 rounded-md text-sm hidden"></div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h3 class="text-xl font-bold mb-4">Applications API Test</h3>
                        <button id="test-applications-api" class="bg-dashboardBlue text-white py-2 px-4 rounded-md font-bold hover:bg-indigo-900 transition shadow-md">
                            Test Applications List
                        </button>
                        <div id="applications-api-status" class="mt-4 p-3 rounded-md text-sm hidden"></div>
                    </div>
                </div>

                <div id="api-response-display" class="mt-8 p-4 bg-gray-100 rounded-lg border border-gray-300 hidden">
                    <h3 class="text-xl font-bold mb-2">API Response:</h3>
                    <pre class="whitespace-pre-wrap text-gray-800" id="api-response-content"></pre>
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
            // Error modal event listeners (reused from other pages)
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

            // Function to show error modal
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

            // Function to hide error modal
            function hideErrorModal() {
                const errorModal = document.getElementById('error-modal');
                errorModal.classList.add('hidden');
            }

            // Function to display API response
            function displayApiResponse(targetElementId, responseData, apiEndpoint) {
                const statusDiv = document.getElementById(targetElementId);
                const responseDisplay = document.getElementById('api-response-display');
                const responseContent = document.getElementById('api-response-content');
                statusDiv.classList.remove('hidden');
                responseDisplay.classList.remove('hidden');
                responseContent.textContent = JSON.stringify(responseData, null, 2);

                if (responseData.success) {
                    statusDiv.classList.remove('bg-red-100', 'text-red-800');
                    statusDiv.classList.add('bg-green-100', 'text-green-800');
                    statusDiv.innerHTML = `<i class="fas fa-check-circle mr-2"></i> ${apiEndpoint} - Success: ${responseData.message}`;
                } else {
                    statusDiv.classList.remove('bg-green-100', 'text-green-800');
                    statusDiv.classList.add('bg-red-100', 'text-red-800');
                    let dbConnected = true;
                    if (responseData.message && responseData.message.includes("Database connection error") || responseData.message.includes("SQLSTATE")) {
                        dbConnected = false;
                    }
                    statusDiv.innerHTML = `<i class="fas fa-times-circle mr-2"></i> ${apiEndpoint} - Error: ${responseData.message}`;
                    showErrorModal(responseData.message, dbConnected, true);
                }
            }

            // Test DB Connection
            document.getElementById('test-db-health').addEventListener('click', async function() {
                const loadingOverlay = document.getElementById('loading-overlay');
                loadingOverlay.classList.remove('hidden');
                const endpoint = '../api/health/status.php';
                try {
                    const response = await fetch(endpoint);
                    const data = await response.json();
                    displayApiResponse('db-health-status', data, 'DB Health Check');
                } catch (error) {
                    const statusDiv = document.getElementById('db-health-status');
                    statusDiv.classList.remove('hidden');
                    statusDiv.classList.remove('bg-green-100', 'text-green-800');
                    statusDiv.classList.add('bg-red-100', 'text-red-800');
                    statusDiv.innerHTML = `<i class="fas fa-times-circle mr-2"></i> DB Health Check - Network Error: ${error.message}`;
                    showErrorModal('Network error during DB Health Check: ' + error.message, false, false);
                } finally {
                    loadingOverlay.classList.add('hidden');
                }
            });

            // Test Announcements API
            document.getElementById('test-announcements-api').addEventListener('click', async function() {
                const loadingOverlay = document.getElementById('loading-overlay');
                loadingOverlay.classList.remove('hidden');
                const endpoint = '../api/announcements/list.php?limit=1';
                try {
                    const response = await fetch(endpoint);
                    const data = await response.json();
                    displayApiResponse('announcements-api-status', data, 'Announcements API');
                } catch (error) {
                    const statusDiv = document.getElementById('announcements-api-status');
                    statusDiv.classList.remove('hidden');
                    statusDiv.classList.remove('bg-green-100', 'text-green-800');
                    statusDiv.classList.add('bg-red-100', 'text-red-800');
                    statusDiv.innerHTML = `<i class="fas fa-times-circle mr-2"></i> Announcements API - Network Error: ${error.message}`;
                    showErrorModal('Network error during Announcements API Test: ' + error.message, false, false);
                } finally {
                    loadingOverlay.classList.add('hidden');
                }
            });

            // Test Applications API
            document.getElementById('test-applications-api').addEventListener('click', async function() {
                const loadingOverlay = document.getElementById('loading-overlay');
                loadingOverlay.classList.remove('hidden');
                const endpoint = '../api/applications/list.php?limit=1';
                try {
                    const response = await fetch(endpoint);
                    const data = await response.json();
                    displayApiResponse('applications-api-status', data, 'Applications API');
                } catch (error) {
                    const statusDiv = document.getElementById('applications-api-status');
                    statusDiv.classList.remove('hidden');
                    statusDiv.classList.remove('bg-green-100', 'text-green-800');
                    statusDiv.classList.add('bg-red-100', 'text-red-800');
                    statusDiv.innerHTML = `<i class="fas fa-times-circle mr-2"></i> Applications API - Network Error: ${error.message}`;
                    showErrorModal('Network error during Applications API Test: ' + error.message, false, false);
                } finally {
                    loadingOverlay.classList.add('hidden');
                }
            });
        });
    </script>
</body>
</html>