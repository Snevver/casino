<?php 

include "../database/data.php";

if (!isset($_SESSION['ingelogde_gebruiker'])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casino - profiel</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="../images/logo.png">
</head>
<body class="bg-black text-white min-h-screen font-mono">
    <nav class="bg-gray-900 border-b-2 border-white">
        <div class="container flex flex-wrap items-center justify-between mx-auto p-4">
            <!-- Logo -->
            <a href="../index.php" class="flex items-center space-x-3 order-1 ml-6">
                <img src="../images/logo.png" class="h-20" alt="Casino Logo" />
            </a>

            <!-- Navbar Links -->
            <div class="hidden items-center justify-between w-full md:flex md:w-auto order-3 md:order-2 ml-12 pl-12" id="navbar-user">
                <ul class="flex flex-col font-medium p-4 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 md:mt-0 md:border-0 md:bg-gray-900 dark:bg-gray-900 dark:border-gray-700">
                    <li>
                        <a href="roulette.php" class="block py-2 px-3 text-gray-300 hover:text-white rounded md:p-0">Roulette</a>
                    </li>
                    <li>
                        <a href="blackjack.php" class="block py-2 px-3 text-gray-300 hover:text-white rounded md:p-0">Blackjack</a>
                    </li>
                </ul>
            </div>

            <!-- User Info -->
            <div class="flex items-center justify-center space-x-4 order-2 md:order-3">
                <!-- Balance -->
                <span class="text-gray-300">€<?php echo $saldo; ?></span>

                <!-- User Avatar -->
                <a href="profiel.php" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300" id="user-menu-button">
                    <img class="w-16 h-16 rounded-full hover:border hover:border-white hover:p-1" src="../images/profiel_foto.png" alt="User Photo">
                </a>

                <!-- menu button -->
                <button id="hamburger-button" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
                    </svg>
                </button>

                <!-- Logout Button Container -->
                <div class="flex items-center justify-center h-full">
                    <a href="../logicFiles/logout.php" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-500 transition">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    
    <div class="container mx-auto px-4 py-8 text-white">
        <h1 class="text-3xl font-bold uppercase tracking-wide border-b border-gray-700 inline-block pb-2 mb-8">Profiel gegevens:</h1>

        <!-- Gebruiker gegevens -->
        <div class="bg-black border border-white rounded-lg p-6 space-y-4">
            <div class="border-b border-gray-700 pb-2">
                <h2 class="text-lg font-semibold text-gray-300">Gebruikersnaam:</h2>
                <h3 class="text-xl"><?=$gebruikersnaam;?></h3>
            </div>
            <div class="border-b border-gray-700 pb-2">
                <h2 class="text-lg font-semibold text-gray-300">Email:</h2>
                <h3 class="text-xl"><?=$email;?></h3>
            </div>
            <div class="border-b border-gray-700 pb-2">
                <h2 class="text-lg font-semibold text-gray-300">Geboorte datum (yyyy/mm/dd):</h2>
                <h3 class="text-xl"><?=$geboortedatum;?></h3>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-300">Saldo:</h2>
                <h3 class="text-xl text-green-500">€<?=$saldo;?></h3>
            </div>
        </div>
    </div>
    
    <script>
        // Toggle navbar visibility on smaller screens
        document.getElementById('hamburger-button').addEventListener('click', function () {
            const navbar = document.getElementById('navbar-user');
            navbar.classList.toggle('hidden');
        });

        // Optional: Add toggle for user dropdown
        document.getElementById('user-menu-button').addEventListener('click', function () {
            const dropdown = document.getElementById('user-dropdown');
            dropdown.classList.toggle('hidden');
        });
    </script>
</body>
</html>