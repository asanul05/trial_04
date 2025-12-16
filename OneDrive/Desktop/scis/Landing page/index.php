<?php include 'header.php'; ?>
<div id="loading-overlay" class="loading-overlay hidden">
    <div class="spinner"></div>
</div>

<section class="container mx-auto px-6 py-10">
    <h2 class="text-4xl font-extrabold text-black mb-8">
        Latest <span class="text-brandBlue border-b-4 border-brandBlue">Information</span>
    </h2>

    <div id="news-section" class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <!-- News content will be loaded here by JavaScript -->
        <p class="lg:col-span-5 text-center text-gray-500">Loading latest information...</p>
    </div>

    <div class="text-center mt-6">
        <a href="news.php" class="text-red-500 underline text-lg hover:text-red-700">Click here to read more news items....</a>
    </div>
</section>

<section class="bg-brandRed py-12">
    <div class="container mx-auto px-6">
        <h2 class="text-4xl font-bold text-white text-center mb-10">Key Benefits</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-3xl p-6 flex flex-col items-center text-center shadow-lg h-64 justify-center hover:scale-105 transition transform">
                <div class="bg-indigo-100 p-3 rounded-lg mb-4"><i class="fas fa-file-alt text-4xl text-indigo-600"></i></div>
                <h3 class="font-bold text-lg mb-2">Easy Registration</h3>
                <p class="text-sm text-gray-600">Easy registration for Senior Citizens</p>
            </div>
            <div class="bg-white rounded-3xl p-6 flex flex-col items-center text-center shadow-lg h-64 justify-center hover:scale-105 transition transform">
                <div class="bg-white border-2 border-black p-2 rounded-lg mb-4 relative">
                    <i class="fas fa-shield-alt absolute -top-2 -right-2 text-black bg-white"></i>
                    <i class="fas fa-dollar-sign text-3xl text-black px-2"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">Track Benefits</h3>
                <p class="text-sm text-gray-600">See what benefits you can get</p>
            </div>
            <div class="bg-white rounded-3xl p-6 flex flex-col items-center text-center shadow-lg h-64 justify-center hover:scale-105 transition transform">
                <div class="bg-black text-white p-3 rounded mb-4 flex items-center"><i class="fas fa-user mr-1"></i> <i class="fas fa-bars"></i></div>
                <h3 class="font-bold text-lg mb-2">Digital ID Access</h3>
                <p class="text-sm text-gray-600">Senior Citizen Digital ID</p>
            </div>
            <div class="bg-white rounded-3xl p-6 flex flex-col items-center text-center shadow-lg h-64 justify-center hover:scale-105 transition transform">
                <div class="mb-4"><i class="fas fa-users text-4xl text-orange-400"></i></div>
                <h3 class="font-bold text-lg mb-2">Community Support</h3>
                <p class="text-sm text-gray-600">Community Forums</p>
            </div>
        </div>
    </div>
</section>

<section class="container mx-auto px-6 py-16">
    <div class="flex flex-col lg:flex-row items-center justify-between">
        <div class="lg:w-1/2 mb-12 lg:mb-0">
            <h2 class="text-8xl font-black text-black leading-none">100,000+</h2>
            <h3 class="text-3xl font-bold text-brandGold mb-6 uppercase">Registered Senior Citizens</h3>

            <blockquote class="text-brandBlue font-bold text-xl mb-8 max-w-md border-l-4 border-brandBlue pl-4">
                “Let’s build a trusted database of all Filipino senior citizens together! Be part of the community, register now and make sure you’re counted!”
            </blockquote>

            <div class="flex space-x-4 items-center">
                <a href="register.php" class="bg-brandBlue text-white font-bold py-3 px-8 rounded shadow hover:bg-blue-800 transition">Register Now!</a>
                <span class="font-bold text-black">OR</span>
                <a href="login.php" class="bg-brandBlue text-white font-bold py-3 px-8 rounded shadow hover:bg-blue-800 transition">Log in</a>
            </div>
        </div>

        <div class="lg:w-1/2 flex flex-col items-center">
            <h3 class="text-2xl font-bold mb-8">How it Works :</h3>
            <div class="relative w-full max-w-md h-64">
                <div class="absolute top-0 left-0 text-center w-32">
                     <i class="fas fa-user-plus text-4xl mb-2 text-brandBlue"></i>
                     <h4 class="font-bold">Register</h4>
                     <p class="text-xs">Fill out form</p>
                </div>
                <i class="fas fa-arrow-right absolute top-6 left-1/2 transform -translate-x-1/2 text-3xl text-gray-400"></i>
                <div class="absolute top-0 right-0 text-center w-32">
                    <i class="fas fa-user-check text-4xl mb-2 text-brandBlue"></i>
                    <h4 class="font-bold">Verify</h4>
                    <p class="text-xs">Submit documents</p>
               </div>
               <i class="fas fa-arrow-down absolute top-1/2 right-12 text-3xl text-gray-400"></i>
               <div class="absolute bottom-0 right-0 text-center w-32">
                    <i class="fas fa-id-card text-4xl mb-2 text-brandBlue"></i>
                    <h4 class="font-bold">Get ID</h4>
                    <p class="text-xs">Receive digital/ printed SC ID</p>
               </div>
               <i class="fas fa-arrow-left absolute bottom-8 left-1/2 transform -translate-x-1/2 text-3xl text-gray-400"></i>
                <div class="absolute bottom-0 left-0 text-center w-32">
                    <i class="fas fa-universal-access text-4xl mb-2 text-brandBlue"></i>
                    <h4 class="font-bold">Access Services</h4>
                    <p class="text-xs">Enjoy programs and benefits</p>
               </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-brandPink pt-16 pb-24 relative">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-4xl font-bold text-brandBlue uppercase mb-12">Key Features</h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="flex flex-col items-center group">
                <div class="bg-brandBlue rounded-xl p-4 mb-4 shadow-lg w-24 h-24 flex items-center justify-center group-hover:bg-blue-800 transition">
                    <i class="fas fa-map-marked-alt text-white text-4xl"></i>
                </div>
                <h3 class="font-bold text-black text-lg w-3/4">Senior Citizen Heat Map</h3>
            </div>
            <div class="flex flex-col items-center group">
                <div class="bg-brandBlue rounded-xl p-4 mb-4 shadow-lg w-24 h-24 flex items-center justify-center relative group-hover:bg-blue-800 transition">
                    <i class="fas fa-network-wired text-white text-4xl"></i>
                    <span class="absolute top-1 right-1 bg-white text-brandBlue text-xs font-bold px-1 rounded">AI</span>
                </div>
                <h3 class="font-bold text-black text-lg w-3/4">AI Powered</h3>
            </div>
            <div class="flex flex-col items-center group">
                <div class="bg-brandBlue rounded-xl p-4 mb-4 shadow-lg w-24 h-24 flex items-center justify-center group-hover:bg-blue-800 transition">
                    <i class="fas fa-person-cane text-white text-4xl"></i>
                </div>
                <h3 class="font-bold text-black text-lg w-3/4">Senior-Friendly Design</h3>
            </div>
            <div class="flex flex-col items-center group">
                <div class="bg-blue-400 rounded-xl p-4 mb-4 shadow-lg w-24 h-24 flex items-center justify-center group-hover:bg-blue-500 transition">
                    <i class="fas fa-file-contract text-white text-4xl"></i>
                </div>
                <h3 class="font-bold text-black text-lg w-3/4">Registration and Documentation</h3>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

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
        fetchLatestAnnouncements();
        checkApiHealth(); // Call the new API health check

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

    async function checkApiHealth() {
        try {
            const response = await fetch('../Website/api/health/status.php');
            const data = await response.json();
            const dbStatusElem = document.getElementById('error-modal-db-status');
            const apiStatusElem = document.getElementById('error-modal-api-status');

            if (response.ok && data.success) {
                // API is reachable and database connection is successful
                dbStatusElem.textContent = 'Database Connected: Yes';
                dbStatusElem.style.color = 'green';
                apiStatusElem.textContent = 'API Working: Yes';
                apiStatusElem.style.color = 'green';
            } else {
                // API is reachable but reported an error (e.g., DB connection issue)
                let dbConnected = true;
                if (data.message && data.message.includes("Database connection error")) {
                    dbConnected = false;
                }
                showErrorModal('API Health Check: ' + (data.message || 'Unknown error'), dbConnected, true);
            }
        } catch (error) {
            // API is not reachable (network error, server down)
            showErrorModal('API Health Check: Could not reach API endpoint.', false, false);
            console.error('API health check error:', error);
        }
    }

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

    async function fetchLatestAnnouncements() {
        const loadingOverlay = document.getElementById('loading-overlay');
        loadingOverlay.classList.remove('hidden');

        try {
            const response = await fetch('../Website/api/announcements/list.php?limit=4'); // Fetch up to 4 announcements
            const newsSection = document.getElementById('news-section');
            newsSection.innerHTML = ''; // Clear loading message

            if (!response.ok) { // Check for HTTP errors (e.g., 404, 500)
                const errorText = await response.text();
                showErrorModal('HTTP Error: ' + response.status + ' - ' + errorText.substring(0, 100) + '...', false, false);
                return; // Stop further processing
            }

            const data = await response.json();

            if (data.success) {
                // API is working, and data was retrieved successfully
                // Update the news section with fetched data
                if (data.data.length > 0) {
                    // Main Event - first announcement
                    const mainEvent = data.data[0];
                    const mainEventHtml = `
                        <div class="lg:col-span-2 border border-gray-300 p-4 rounded-lg shadow-sm">
                            <img src="https://placehold.co/600x400/e2e8f0/gray?text=Main+Event" alt="News Image" class="w-full h-48 object-cover rounded mb-4">
                            <p class="text-gray-500 text-sm mb-2">${mainEvent.event_date ? new Date(mainEvent.event_date).toLocaleDateString() : new Date(mainEvent.created_at).toLocaleDateString()}</p>
                            <div class="flex justify-between items-start">
                                <h3 class="font-bold text-lg mb-2">${mainEvent.title}</h3>
                                <i class="far fa-bookmark text-gray-400 cursor-pointer hover:text-brandBlue"></i>
                            </div>
                            <p class="text-sm text-gray-600 mb-4">
                                ${mainEvent.description.substring(0, 150)}...
                            </p>
                            <div class="border-t border-gray-200 pt-3 flex items-center text-gray-400 text-xs">
                                <i class="far fa-eye mr-2"></i> ${Math.floor(Math.random() * 1000) + 1} views
                            </div>
                        </div>
                    `;
                    newsSection.innerHTML += mainEventHtml;

                    // Other news items
                    const otherNewsContainer = document.createElement('div');
                    otherNewsContainer.className = 'lg:col-span-3 flex flex-col space-y-4';
                    data.data.slice(1).forEach(news => {
                        const newsHtml = `
                            <div class="flex flex-col sm:flex-row border border-gray-300 p-3 rounded-lg shadow-sm bg-white hover:shadow-md transition">
                                <img src="https://placehold.co/200x150/e2e8f0/gray?text=News" class="w-full sm:w-1/3 h-32 object-cover rounded">
                                <div class="sm:ml-4 mt-2 sm:mt-0 flex-1">
                                    <p class="text-xs text-gray-500">${news.event_date ? new Date(news.event_date).toLocaleDateString() : new Date(news.created_at).toLocaleDateString()}</p>
                                    <h4 class="font-bold text-md">${news.title}</h4>
                                    <p class="text-xs text-gray-600 mt-1">${news.description.substring(0, 100)}...</p>
                                    <a href="news.php?id=${news.id}" class="text-xs text-gray-400 underline mt-2 block hover:text-brandBlue">Read more...</a>
                                </div>
                            </div>
                        `;
                        otherNewsContainer.innerHTML += newsHtml;
                    });
                    newsSection.appendChild(otherNewsContainer);

                } else {
                    newsSection.innerHTML = '<p class="lg:col-span-5 text-center text-gray-500">No latest information available.</p>';
                }
            } else {
                // API returned a success: false (logical error)
                let isDbConnected = true; // Assume true unless message indicates otherwise
                let apiMessage = data.message || 'An unknown API error occurred.';

                if (apiMessage.includes("Database connection error") || apiMessage.includes("SQLSTATE")) {
                    isDbConnected = false;
                }
                showErrorModal(apiMessage, isDbConnected, true); // API responded, but with a logical error
            }
        } catch (error) {
            console.error('Error fetching latest announcements:', error);
            const newsSection = document.getElementById('news-section');
            newsSection.innerHTML = '<p class="lg:col-span-5 text-center text-red-500">Failed to load latest information due to a network or parsing error.</p>';
            showErrorModal('Network or parsing error: ' + error.message, false, false);
        } finally {
            loadingOverlay.classList.add('hidden');
        }
    }
</script>
</body>
</html>