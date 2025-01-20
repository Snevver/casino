<?php 

include "database/data.php";
include "database/dbcon.php";

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
    <title>Casino - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="images/logo.png">
</head>
<body class="bg-black text-white min-h-screen font-mono" style="font-family: 'Andale Mono', monospace;">
    <nav class="bg-gray-900 border-b-2 border-white">
        <div class="container flex flex-wrap items-center justify-between mx-auto p-4">
            <!-- Logo -->
            <a href="index.php" class="flex items-center space-x-3 order-1 ml-6">
                <img src="images/logo.png" class="h-20" alt="Casino Logo" />
            </a>

            <!-- Navbar Links -->
            <div class="hidden items-center justify-between w-full md:flex md:w-auto order-3 md:order-2 ml-12 pl-12" id="navbar-user">
                <ul class="flex flex-col font-medium p-4 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 md:mt-0 md:border-0 md:bg-gray-900 dark:bg-gray-900 dark:border-gray-700">
                    <li>
                        <a href="pages/roulette.php" class="block py-2 px-3 text-gray-300 hover:text-white rounded md:p-0">Roulette</a>
                    </li>
                    <li>
                        <a href="pages/blackjack.php" class="block py-2 px-3 text-gray-300 hover:text-white rounded md:p-0">Blackjack</a>
                    </li>
                </ul>
            </div>

            <!-- User Info -->
            <div class="flex items-center justify-center space-x-4 order-2 md:order-3">
                <!-- Balance -->
                <span class="text-gray-300">€<?php echo $saldo; ?></span>

                <!-- User Avatar -->
                <a href="pages/profiel.php" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300" id="user-menu-button">
                    <img class="w-16 h-16 rounded-full hover:border hover:border-white hover:p-1" src="images/profiel_foto.png" alt="User Photo">
                </a>

                <!-- menu button -->
                <button id="hamburger-button" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15" />
                    </svg>
                </button>

                <!-- Logout Button Container -->
                <div class="flex items-center justify-center h-full">
                    <a href="logicFiles/logout.php" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-500 transition">Logout</a>
                </div>
            </div>
        </div>
    </nav>


    <!-- Home Content -->
    <div class="container mx-auto mt-12 border border-white rounded-lg border-2">
        <div class="bg-black p-8 rounded-lg shadow-lg text-center">
            <h1 class="text-3xl font-bold mb-4">Welkom op Sven's Casino, 
                <?php 
                    try {
                        echo htmlspecialchars($gebruikersnaam);
                    } catch (Exception $e) {
                        echo 'Error: ', htmlspecialchars($e->getMessage());
                    } 
                ?>!
            </h1>
            <p class="text-gray-400 mb-6">Selecteer een optie om te beginnen:</p>

            <!-- Game Options -->
            <div class="flex justify-center space-x-6">
                <a href="pages/roulette.php" class="bg-black border border-white text-white px-6 py-3 rounded-lg hover:bg-white hover:text-black transition">Roulette</a>
                <a href="pages/blackjack.php" class="bg-black border border-white text-white px-6 py-3 rounded-lg hover:bg-white hover:text-black transition">Blackjack</a>
                <a href="pages/geld_toevoegen.php" class="bg-green-600 text-white px-6 py-3 border border-white rounded-lg hover:bg-green-500 transition">Geld bijschrijven</a>
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
