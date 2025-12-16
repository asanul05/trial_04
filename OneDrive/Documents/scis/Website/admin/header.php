<header class="bg-white shadow-sm py-4 px-8 flex justify-between items-center sticky top-0 z-40">          <div>         <h1 class="text-xl font-bold text-black">Zamboanga City OSCA</h1>         <p class="text-sm text-gray-600 font-medium">Office of Senior Citizens Affairs</p>     </div>          <div class="flex items-center space-x-4 relative">                  <div class="text-right hidden sm:block">             <p class="text-sm font-bold text-gray-900">Space1000</p>             <p class="text-xs text-gray-500">Social Worker Coordinator</p>         </div>                  <button id="user-menu-button" class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center text-gray-600 hover:bg-gray-50 cursor-pointer transition focus:outline-none">             <i class="fa-regular fa-user text-xl"></i>         </button>          <div id="user-menu-dropdown" class="hidden absolute right-0 top-14 w-48 bg-white rounded-lg shadow-xl border border-gray-100 z-50 overflow-hidden animation-fade-in">                          <a href="profile.php" class="px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-3 transition">                 <div class="w-6 flex justify-center">                     <i class="fa-regular fa-circle-user text-lg text-gray-600"></i>                  </div>                 <span class="font-medium">Profile</span>             </a>                          <div class="border-t border-black/10"></div>                          <a href="logout.php" class="px-4 py-3 text-sm text-gray-800 hover:bg-red-50 hover:text-red-600 flex items-center gap-3 transition group">                 <div class="w-6 flex justify-center">                     <i class="fa-solid fa-power-off text-lg text-red-500 group-hover:text-red-600"></i>                 </div>                 <span class="font-medium">Log Out</span>             </a>         </div>      </div> </header>  <script>     // JavaScript to handle the Dropdown Toggle     document.addEventListener('DOMContentLoaded', function() {         const userMenuButton = document.getElementById('user-menu-button');         const userMenuDropdown = document.getElementById('user-menu-dropdown');

        if (userMenuButton && userMenuDropdown) {
            userMenuButton.addEventListener('click', function() {
                userMenuDropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!userMenuDropdown.contains(event.target) && !userMenuButton.contains(event.target)) {
                    userMenuDropdown.classList.add('hidden');
                }
            });
        }
    });

    // --- Global Message Overlay Function ---
    function showMessage(type, title, message, redirectUrl = null) {
        const messageOverlay = document.getElementById('message-overlay');
        const messageModal = document.getElementById('message-modal');
        const messageTitle = document.getElementById('message-title');
        const messageText = document.getElementById('message-text');
        const messageButton = document.getElementById('message-button');

        messageTitle.textContent = title;
        messageText.textContent = message;

        // Apply type-specific styling
        messageModal.classList.remove('success', 'error');
        if (type === 'success') {
            messageModal.classList.add('success');
            messageTitle.style.color = '#10b981'; // Tailwind green-500
        } else if (type === 'error') {
            messageModal.classList.add('error');
            messageTitle.style.color = '#ef4444'; // Tailwind red-500
        } else {
            messageTitle.style.color = '#1a008e'; // Default dashboardBlue
        }
        
        messageOverlay.classList.remove('hidden');

        messageButton.onclick = function() {
            messageOverlay.classList.add('hidden');
            if (redirectUrl) {
                window.location.href = redirectUrl;
            }
        };
    }
</script>

<!-- Global Message Overlay HTML -->
<div id="message-overlay" class="message-overlay hidden">
    <div id="message-modal" class="message-modal">
        <h3 id="message-title"></h3>
        <p id="message-text"></p>
        <button id="message-button">OK</button>
    </div>
</div>