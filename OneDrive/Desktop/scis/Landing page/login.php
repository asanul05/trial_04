<?php include 'header.php'; ?>

<section class="min-h-screen flex items-center justify-center bg-white py-20">
    <div class="container mx-auto px-6">
        
        <div class="flex flex-col md:flex-row items-center justify-center gap-12 lg:gap-24">
            
            <div class="flex-shrink-0">
                <i class="fas fa-user-circle text-gray-800 text-[12rem] md:text-[20rem]"></i>
            </div>

            <div class="w-full max-w-md">
                
                <h2 class="text-4xl font-bold text-black mb-8">LOG IN</h2>

                <form id="login-form">
                    
                    <div class="mb-6">
                        <label class="block text-black font-bold text-lg mb-2">Username:</label>
                        <input type="text" 
                               id="username"
                               placeholder="Enter Username" 
                               class="w-full border border-black rounded-full py-3 px-6 text-gray-700 focus:outline-none focus:border-brandBlue focus:ring-1 focus:ring-brandBlue text-lg">
                    </div>

                    <div class="mb-2 relative">
                        <label class="block text-black font-bold text-lg mb-2">Password:</label>
                        <div class="relative">
                            <input type="password" 
                                   id="password"
                                   placeholder="Enter Password" 
                                   class="w-full border border-black rounded-full py-3 px-6 pr-12 text-gray-700 focus:outline-none focus:border-brandBlue focus:ring-1 focus:ring-brandBlue text-lg">
                            
                            <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                                <i class="far fa-eye text-xl" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end mb-8">
                        <a href="#" class="text-blue-700 font-bold underline hover:text-blue-900">Forgot Password?</a>
                    </div>

                    <button type="submit" class="block w-full text-center bg-[#000080] text-white font-bold text-xl py-3 rounded hover:bg-blue-900 transition duration-300 shadow-lg">
                    LOG IN
                    </button>

                    <div id="error-message" class="mt-4 text-red-500 text-xs italic"></div>

                    <div class="text-center mt-6">
                        <span class="text-blue-700 font-bold">Don't have an account yet? </span>
                        <a href="register.php" class="text-blue-700 font-bold underline hover:text-blue-900">Sign up here.</a>
                    </div>

                </form>
            </div>

        </div>
    </div>
</section>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }

    document.getElementById('login-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const errorMessage = document.getElementById('error-message');

        fetch('../Website/api/auth/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ username, password })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '../Website/admin/dashboard.php';
            } else {
                errorMessage.textContent = data.message;
            }
        })
        .catch(error => {
            errorMessage.textContent = 'An unexpected error occurred.';
            console.error('Error:', error);
        });
    });
</script>