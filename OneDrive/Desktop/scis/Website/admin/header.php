<?php session_start(); ?>
<header class="bg-white shadow-sm py-4 px-8 flex justify-between items-center sticky top-0 z-40">
    <div>
        <h1 class="text-xl font-bold text-black">Zamboanga City OSCA</h1>
        <p class="text-sm text-gray-600 font-medium">Office of Senior Citizens Affairs</p>
    </div>

    <div class="flex items-center space-x-4 relative">
        <div class="text-right hidden sm:block">
            <p class="text-sm font-bold text-gray-900">
                <?php echo htmlspecialchars($_SESSION['user']['first_name'] ?? 'Guest') . ' ' . htmlspecialchars($_SESSION['user']['last_name'] ?? ''); ?>
            </p>
            <p class="text-xs text-gray-500">
                <?php echo htmlspecialchars($_SESSION['user']['role_name'] ?? 'Role'); ?>
            </p>
        </div>

        <button id="user-menu-button" class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center text-gray-600 hover:bg-gray-50 cursor-pointer transition focus:outline-none">
            <i class="fa-regular fa-user text-xl"></i>
        </button>

        <div id="user-menu-dropdown" class="hidden absolute right-0 top-14 w-48 bg-white rounded-lg shadow-xl border border-gray-100 z-50 overflow-hidden animation-fade-in">
            <a href="profile.php" class="px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-3 transition">
                <div class="w-6 flex justify-center">
                    <i class="fa-regular fa-circle-user text-lg text-gray-600"></i>
                </div>
                <span class="font-medium">Profile</span>
            </a>

            <div class="border-t border-black/10"></div>

            <a href="logout.php" class="px-4 py-3 text-sm text-gray-800 hover:bg-red-50 hover:text-red-600 flex items-center gap-3 transition group">
                <div class="w-6 flex justify-center">
                    <i class="fa-solid fa-power-off text-lg text-red-500 group-hover:text-red-600"></i>
                </div>
                <span class="font-medium">Log Out</span>
            </a>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenuDropdown = document.getElementById('user-menu-dropdown');

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
</script>