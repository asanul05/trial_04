<?php
// Get the current file name (e.g., 'index.php' or 'about.php')
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senior Citizen Information System - Zamboanga Del Sur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .curve-top-left { border-top-left-radius: 100px; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brandBlue: '#1a1f71', 
                        brandRed: '#8b0000',
                        brandGold: '#facc15',
                        brandPink: '#f3dada',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white flex flex-col min-h-screen">

    <header class="bg-brandBlue text-white py-3 px-6 shadow-md">
        <div class="container mx-auto flex flex-col md:flex-row items-center justify-between">
            <div class="flex items-center space-x-3 mb-4 md:mb-0">
                <div class="h-12 w-12 rounded-full bg-yellow-400 border-2 border-white flex items-center justify-center text-brandBlue font-bold text-xs overflow-hidden">
                   <img src="../img/Osca logo.jpg" alt="Logo">
                </div>
                <div class="leading-tight">
                    <p class="text-xs uppercase tracking-wider opacity-80">City of Zamboanga</p>
                    <h1 class="font-bold text-lg leading-none">SENIOR CITIZEN<br>INFORMATION SYSTEM</h1>
                </div>
            </div>

            <nav class="hidden md:flex space-x-6 font-semibold text-sm">
                
                <a href="index.php" class="<?php echo ($current_page == 'index.php') ? 'border-b-2 border-white pb-1' : 'hover:text-gray-300 border-b-2 border-transparent hover:border-gray-400 pb-1'; ?>">
                    HOME
                </a>

                <a href="about.php" class="<?php echo ($current_page == 'about.php') ? 'border-b-2 border-white pb-1' : 'hover:text-gray-300 border-b-2 border-transparent hover:border-gray-400 pb-1'; ?>">
                    ABOUT
                </a>

                <a href="services.php" class="<?php echo ($current_page == 'services.php') ? 'border-b-2 border-white pb-1' : 'hover:text-gray-300 border-b-2 border-transparent hover:border-gray-400 pb-1'; ?>">
                    SERVICES
                </a>

                <a href="news.php" class="<?php echo ($current_page == 'news.php') ? 'border-b-2 border-white pb-1' : 'hover:text-gray-300 border-b-2 border-transparent hover:border-gray-400 pb-1'; ?>">
                    NEWS
                </a>

            </nav>

            <div class="flex items-center space-x-4">
                <form action="#" method="GET" class="relative">
                    <input type="text" name="q" placeholder="Search" class="pl-4 pr-10 py-1 rounded-full text-gray-700 focus:outline-none w-48">
                    <button type="submit" class="absolute right-3 top-1.5 text-gray-500"><i class="fas fa-search"></i></button>
                </form>
                <a href="login.php" class="border-2 border-white rounded-full p-1 w-8 h-8 flex items-center justify-center cursor-pointer hover:bg-white hover:text-brandBlue transition">
                <i class="fas fa-user"></i>
                </a>
            </div>
        </div>
    </header>
    
    <main class="flex-grow">