<?php include 'header.php'; ?>
<div id="loading-overlay" class="loading-overlay hidden">
    <div class="spinner"></div>
</div>

<section class="bg-gradient-to-b from-blue-900 to-brandBlue text-white py-20 relative overflow-hidden">
    <div class="container mx-auto px-6 relative z-10 text-center md:text-left">
        <h1 class="text-5xl md:text-6xl font-extrabold uppercase leading-tight">
            Latest News<br>and Updates
        </h1>
    </div>
</section>

<section class="bg-gray-50 py-12 min-h-screen">
    <div class="container mx-auto px-6">
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-12 gap-4">
            
            <div class="relative w-full md:w-2/3">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400 text-lg"></i>
                </div>
                <input type="text" id="search-input"
                       class="w-full pl-12 pr-4 py-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-brandBlue shadow-sm text-gray-700 text-lg" 
                       placeholder="Search articles or blogs...">
            </div>

            <button id="search-button" class="w-full md:w-auto bg-brandBlue text-white font-bold py-4 px-8 rounded-lg shadow hover:bg-blue-800 transition flex items-center justify-center space-x-3">
                <span class="text-lg">Search</span>
                <i class="fas fa-search"></i>
            </button>
        </div>

        <div id="news-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <p class="col-span-full text-center text-gray-500">Loading news...</p>
        </div>
        
        <div id="pagination-controls" class="flex justify-center items-center mt-8 space-x-2">
            <button id="prev-page-button" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">Previous</button>
            <div id="page-numbers" class="flex space-x-1"></div>
            <button id="next-page-button" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">Next</button>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<script>
    let currentPage = 1;
    let currentLimit = 6;
    let currentSearch = '';

    document.addEventListener('DOMContentLoaded', function() {
        fetchNews();

        document.getElementById('search-button').addEventListener('click', function() {
            currentSearch = document.getElementById('search-input').value;
            currentPage = 1;
            fetchNews();
        });
        document.getElementById('search-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                currentSearch = this.value;
                currentPage = 1;
                fetchNews();
            }
        });

        document.getElementById('prev-page-button').addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                fetchNews();
            }
        });

        document.getElementById('next-page-button').addEventListener('click', function() {
            currentPage++;
            fetchNews();
        });
    });

    async function fetchNews() {
        const loadingOverlay = document.getElementById('loading-overlay');
        loadingOverlay.classList.remove('hidden');

        let url = `../Website/api/announcements/list.php?page=${currentPage}&limit=${currentLimit}&search=${currentSearch}`;

        try {
            const response = await fetch(url);
            const data = await response.json();
            const newsContainer = document.getElementById('news-container');
            newsContainer.innerHTML = '';

            if (data.success && data.data.length > 0) {
                data.data.forEach(news => {
                    const newsCard = `
                        <div class="bg-white border border-gray-300 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition duration-300 flex flex-col h-full">
                            <div class="relative h-64 bg-gray-200">
                                <img src="https://placehold.co/600x400/e2e8f0/gray?text=News" alt="News Event" class="w-full h-full object-cover">
                            </div>
                            <div class="p-6 flex-grow flex flex-col">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-sm text-gray-800 font-medium">${news.event_date ? new Date(news.event_date).toLocaleDateString() : new Date(news.created_at).toLocaleDateString()}</span>
                                    <i class="far fa-bookmark text-gray-500 cursor-pointer hover:text-brandBlue text-lg"></i>
                                </div>
                                <h3 class="text-lg font-bold text-black mb-3 leading-tight">
                                    ${news.title}
                                </h3>
                                <p class="text-sm text-gray-600 leading-relaxed mb-6 flex-grow">
                                    ${news.description.substring(0, 150)}...
                                </p>
                                <div class="border-t border-gray-200 pt-4 mt-auto">
                                    <div class="flex items-center text-gray-400 text-sm">
                                        <i class="far fa-eye mr-2"></i>
                                        <span>${Math.floor(Math.random() * 1000) + 1} views</span>
                                    </div>
                                    <a href="news.php?id=${news.id}" class="text-xs text-brandBlue underline mt-2 block hover:text-blue-800">Read more...</a>
                                </div>
                            </div>
                        </div>
                    `;
                    newsContainer.innerHTML += newsCard;
                });

                updatePaginationControls(data.pagination.total, data.pagination.pages);

            } else {
                newsContainer.innerHTML = '<p class="col-span-full text-center text-gray-500">No news found.</p>';
                updatePaginationControls(0, 0);
            }
        } catch (error) {
            console.error('Error fetching news:', error);
            const newsContainer = document.getElementById('news-container');
            newsContainer.innerHTML = '<p class="col-span-full text-center text-red-500">Failed to load news.</p>';
        } finally {
            loadingOverlay.classList.add('hidden');
        }
    }

    function updatePaginationControls(totalItems, totalPages) {
        document.getElementById('prev-page-button').disabled = currentPage === 1;
        document.getElementById('next-page-button').disabled = currentPage === totalPages;

        const pageNumbersContainer = document.getElementById('page-numbers');
        pageNumbersContainer.innerHTML = '';
        for (let i = 1; i <= totalPages; i++) {
            const pageButton = `<button class="px-3 py-1 border border-gray-300 rounded text-sm ${i === currentPage ? 'bg-brandBlue text-white' : 'hover:bg-gray-100'}" onclick="goToPage(${i})">${i}</button>`;
            pageNumbersContainer.innerHTML += pageButton;
        }
    }

    function goToPage(page) {
        currentPage = page;
        fetchNews();
    }
</script>