<?php include 'auth_check.php'; ?><!DOCTYPE html> <html lang="en"> <head>     <meta charset="UTF-8">     <meta name="viewport" content="width=device-width, initial-scale=1.0">     <title>Registration Category - OSCA</title>          <script src="https://cdn.tailwindcss.com"></script>     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">     <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">          <script>         tailwind.config = {             theme: {                 extend: {                     fontFamily: {                         sans: ['Inter', 'sans-serif'],                     },                     colors: {                         dashboardBlue: '#1a008e',                          'osca-text': '#1a008e',                         'osca-bg': '#f3f4f6',                     }                 }             }         }     </script>     <link rel="stylesheet" href="../css/style.css"> </head> <body class="bg-osca-bg font-sans flex text-gray-800">
    <div id="loading-overlay" class="loading-overlay hidden">
        <div class="spinner"></div>
    </div>      <?php     // Sets the active page for sidebar highlighting     $current_page = basename($_SERVER['PHP_SELF']);     ?>     
 <?php include 'sidebar.php'; ?>      <div class="ml-64 w-full min-h-screen flex flex-col">                  <?php include 'header.php'; ?>          <main class="flex-1 p-8 overflow-y-auto">                          <div class="bg-white p-6 rounded-lg shadow-sm mb-6 border border-gray-100">                                  <div class="mb-6">                     <h2 class="text-3xl font-bold text-gray-900">Registration Category</h2>                     <p class="text-gray-600 mt-1">Manage senior citizen ID applications and registrations</p>                 </div>                  <div class="grid grid-cols-1 md:grid-cols-4 gap-4">                     <a href="new_id.php" class="bg-dashboardBlue text-white py-3 px-4 rounded-md font-bold hover:bg-indigo-900 transition shadow-md flex items-center justify-center gap-2">                         <i class="fa-solid fa-plus"></i> New ID                     </a>                     <a href="revalidation_update.php" class="bg-dashboardBlue text-white py-3 px-4 rounded-md font-bold hover:bg-indigo-900 transition shadow-md flex items-center justify-center gap-2">                         Revalidation/Update                     </a>                     <a href="lost_damaged.php" class="bg-dashboardBlue text-white py-3 px-4 rounded-md font-bold hover:bg-indigo-900 transition shadow-md flex items-center justify-center gap-2">                         Lost/Damaged                     </a>                     <button class="bg-dashboardBlue text-white py-3 px-4 rounded-md font-bold hover:bg-indigo-900 transition shadow-md">                         Report                     </button>                 </div>              </div>              <div class="bg-white rounded-lg shadow-md p-6">                                  <h3 class="text-lg font-bold text-black mb-4">Application List</h3>                  <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">                                          <div class="flex items-center gap-2 w-full md:w-auto">                         <div class="relative w-full md:w-80">                             <span class="absolute inset-y-0 left-0 flex items-center pl-3">                                 <i class="fa-solid fa-magnifying-glass text-gray-400"></i>                             </span>                             <input type="text" placeholder="Search by name" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-1 focus:ring-dashboardBlue text-sm">                         </div>                         <button class="p-2 border border-gray-300 rounded hover:bg-gray-50 text-gray-600 transition" title="Refresh">                             <i class="fa-solid fa-rotate"></i>                         </button>                     </div>                      <div class="flex gap-2">                         <a href="senior_citizen_list.php" class="px-4 py-2 border border-gray-400 rounded text-sm font-medium hover:bg-gray-50 text-gray-700 transition">                             Senior Citizen List                         </a>                         <button class="px-4 py-2 border border-gray-400 rounded text-sm font-medium hover:bg-gray-50 text-gray-700 flex items-center gap-2 transition">                             <i class="fa-regular fa-file-excel text-green-600"></i> Export to Excel                         </button>                     </div>                 </div>                  <div class="flex flex-wrap items-center gap-2 mb-6 text-sm">                     <span class="text-gray-800 font-medium mr-1">Quick Filters:</span>                     <button class="bg-dashboardBlue text-white px-4 py-1 rounded-full text-xs font-medium shadow-sm">All</button>                     <button class="bg-gray-200 text-gray-700 px-4 py-1 rounded-full text-xs font-medium hover:bg-gray-300 transition">Claimed</button>                     <button class="bg-gray-200 text-gray-700 px-4 py-1 rounded-full text-xs font-medium hover:bg-gray-300 transition">Verified</button>                     <button class="bg-gray-200 text-gray-700 px-4 py-1 rounded-full text-xs font-medium hover:bg-gray-300 transition">Printed</button>                     <button class="bg-gray-200 text-gray-700 px-4 py-1 rounded-full text-xs font-medium hover:bg-gray-300 transition">Ready for Release</button>                     <button class="bg-gray-200 text-gray-700 px-4 py-1 rounded-full text-xs font-medium hover:bg-gray-300 transition">Drafts</button>                 </div>             
     <div class="overflow-x-auto">                     <table class="w-full text-left border-collapse min-w-[900px]">                     
    <thead>                             <tr class="text-black font-bold text-sm border-b border-gray-200">                                 <th class="pb-4 pl-2">Application ID</th>                                 <th class="pb-4 cursor-pointer hover:text-dashboardBlue">Name <i class="fa-solid fa-sort text-gray-400 ml-1"></i></th>                                 <th class="pb-4">Status</th>                                 <th class="pb-4 cursor-pointer hover:text-dashboardBlue">Type <i class="fa-solid fa-sort text-gray-400 ml-1"></i></th>                                 <th class="pb-4 cursor-pointer hover:text-dashboardBlue">Age <i class="fa-solid fa-sort text-gray-400 ml-1"></i></th>                                 <th class="pb-4">Track Application</th>                                 <th class="pb-4">Actions</th>                             </tr>                         </thead>                                                 <tbody class="text-gray-800 text-sm" id="applications-table-body">
                            <!-- Application rows will be loaded here by JavaScript -->
                            <tr>
                                <td colspan="7" class="text-center py-4 text-gray-500">Loading applications...</td>
                            </tr>
                        </tbody>                     </table>                 </div>                                  <div id="pagination-controls" class="flex justify-between items-center mt-8 text-sm text-gray-600 border-t border-gray-100 pt-6">
                    <div id="pagination-summary">
                        Showing 0 to 0 of 0 Applications
                    </div>
                    <div class="flex items-center gap-2">
                        <button id="prev-page-button" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50 flex items-center gap-2 transition" disabled>
                            <i class="fa-solid fa-angles-left text-xs"></i> Previous
                        </button>
                        <div id="page-numbers" class="flex space-x-1"></div>
                        <button id="next-page-button" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-50 flex items-center gap-2 transition" disabled>
                            Next <i class="fa-solid fa-angles-right text-xs"></i>
                        </button>
                    </div>
                </div>          </main>     </div>

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
        let currentPage = 1;
        let currentLimit = 10; // Default limit, can be changed
        let currentSearch = '';
        let currentStatus = '';
        let currentType = '';

        document.addEventListener('DOMContentLoaded', function() {
            fetchApplications();

            // Event listeners for modal
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

            // Search and filter event listeners (add these to relevant input fields later)
            // For now, let's just make the refresh button work
            document.querySelector('button[title="Refresh"]').addEventListener('click', function() {
                const searchInput = document.querySelector('input[placeholder="Search by name"]');
                if (searchInput) {
                    currentSearch = searchInput.value;
                }
                currentPage = 1;
                fetchApplications();
            });
            document.querySelector('input[placeholder="Search by name"]').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    currentSearch = this.value;
                    currentPage = 1;
                    fetchApplications();
                }
            });


            document.getElementById('prev-page-button').addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    fetchApplications();
                }
            });

            document.getElementById('next-page-button').addEventListener('click', function() {
                currentPage++;
                fetchApplications();
            });
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

        async function fetchApplications() {
            const loadingOverlay = document.getElementById('loading-overlay');
            loadingOverlay.classList.remove('hidden');

            const applicationsTableBody = document.getElementById('applications-table-body');
            applicationsTableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-gray-500">Loading applications...</td></tr>`;

            let url = `../api/applications/list.php?page=${currentPage}&limit=${currentLimit}`;
            if (currentSearch) url += `&search=${encodeURIComponent(currentSearch)}`;
            if (currentStatus) url += `&status=${encodeURIComponent(currentStatus)}`;
            if (currentType) url += `&type=${encodeURIComponent(currentType)}`;

            try {
                const response = await fetch(url);
                
                if (!response.ok) {
                    const errorText = await response.text();
                    showErrorModal('HTTP Error fetching applications: ' + response.status + ' - ' + errorText.substring(0, 100) + '...', false, false);
                    applicationsTableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-red-500">Failed to load applications.</td></tr>`;
                    return;
                }

                const data = await response.json();

                if (data.success) {
                    applicationsTableBody.innerHTML = ''; // Clear loading message

                    if (data.data.length > 0) {
                        data.data.forEach(app => {
                            const row = `
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition h-16">
                                    <td class="pl-2">${app.application_number}</td>
                                    <td>${app.senior_name}</td>
                                    <td>${app.status}</td>
                                    <td>${app.application_type}</td>
                                    <td>${app.age}</td>
                                    <td>
                                        <button class="px-4 py-1.5 border border-dashboardBlue text-dashboardBlue rounded text-xs font-medium hover:bg-indigo-50 transition">
                                            ${app.status === 'Draft' ? 'Edit' : 'View Status'}
                                        </button>
                                    </td>
                                    <td class="text-dashboardBlue font-medium">
                                        <a href="#" class="mr-3 hover:underline">View</a>
                                        <a href="#" class="hover:underline">Delete</a>
                                    </td>
                                </tr>
                            `;
                            applicationsTableBody.innerHTML += row;
                        });
                        updatePaginationControls(data.pagination.total, data.pagination.pages, data.pagination.page, data.pagination.limit);
                    } else {
                        applicationsTableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-gray-500">No applications found.</td></tr>`;
                        updatePaginationControls(0, 0, 0, 0);
                    }
                } else {
                    let isDbConnected = true;
                    let apiMessage = data.message || 'An unknown API error occurred while fetching applications.';
                    if (apiMessage.includes("Database connection error") || apiMessage.includes("SQLSTATE")) {
                        isDbConnected = false;
                    }
                    showErrorModal(apiMessage, isDbConnected, true);
                    applicationsTableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-red-500">Failed to load applications.</td></tr>`;
                    updatePaginationControls(0, 0, 0, 0);
                }
            } catch (error) {
                console.error('Error fetching applications:', error);
                applicationsTableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-red-500">Network or parsing error loading applications.</td></tr>`;
                showErrorModal('Network or parsing error loading applications: ' + error.message, false, false);
                updatePaginationControls(0, 0, 0, 0);
            } finally {
                loadingOverlay.classList.add('hidden');
            }
        }

        function updatePaginationControls(totalItems, totalPages, currentPage, limit) {
            const prevButton = document.getElementById('prev-page-button');
            const nextButton = document.getElementById('next-page-button');
            const pageNumbersContainer = document.getElementById('page-numbers');
            const paginationSummary = document.getElementById('pagination-summary');

            prevButton.disabled = currentPage <= 1;
            nextButton.disabled = currentPage >= totalPages;

            pageNumbersContainer.innerHTML = '';
            const maxPageButtons = 5; // Max number of page buttons to show

            let startPage = Math.max(1, currentPage - Math.floor(maxPageButtons / 2));
            let endPage = Math.min(totalPages, startPage + maxPageButtons - 1);

            if (endPage - startPage + 1 < maxPageButtons) {
                startPage = Math.max(1, endPage - maxPageButtons + 1);
            }

            if (startPage > 1) {
                pageNumbersContainer.innerHTML += `<button class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-100" onclick="goToPage(1)">1</button>`;
                if (startPage > 2) pageNumbersContainer.innerHTML += `<span class="px-2 text-gray-400">...</span>`;
            }

            for (let i = startPage; i <= endPage; i++) {
                const buttonClass = `px-3 py-1 border border-gray-300 rounded text-sm ${i === currentPage ? 'bg-dashboardBlue text-white' : 'hover:bg-gray-100'}`;
                pageNumbersContainer.innerHTML += `<button class="${buttonClass}" onclick="goToPage(${i})">${i}</button>`;
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) pageNumbersContainer.innerHTML += `<span class="px-2 text-gray-400">...</span>`;
                pageNumbersContainer.innerHTML += `<button class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-100" onclick="goToPage(${totalPages})">${totalPages}</button>`;
            }
            
            const startItem = (currentPage - 1) * limit + 1;
            const endItem = Math.min(currentPage * limit, totalItems);
            paginationSummary.textContent = `Showing ${totalItems > 0 ? startItem : 0} to ${endItem} of ${totalItems} Applications`;
        }

        function goToPage(page) {
            currentPage = page;
            fetchApplications();
        }
    </script>
</body>
</html>