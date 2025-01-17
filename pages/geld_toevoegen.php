<?php

include "../database/data.php";
include "../database/dbcon.php";  

// Check of de gebruiker is ingelogd
if (!isset($_SESSION['ingelogde_gebruiker'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['bijschrijven'])) {
    $geld = floatval($_POST['geld']); 
    
    // Update saldo in database
    $user_id = $_SESSION['ingelogde_gebruiker'];
    $geld_erbij = $geld;
    $nieuw_saldo = $saldo + $geld_erbij;
    
    $query = "UPDATE gebruikers SET saldo = ? WHERE gebruiker_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "di", $nieuw_saldo, $user_id);
    
    if (!mysqli_stmt_execute($stmt)) {
        die("Error updating saldo: " . mysqli_stmt_error($stmt));
    }
    
    mysqli_stmt_close($stmt);

    header("Location: geld_toevoegen.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casino - Geld Bijschrijven</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="../images/logo.png">
</head>
<body class="bg-black text-white min-h-screen font-mono">
    <nav class="bg-gray-900 border-b-2 border-white">
        <div class="container flex flex-wrap items-center justify-between mx-auto p-4">
            <!-- Logo -->
            <a href="home.php" class="flex items-center space-x-3 order-1 ml-6">
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

    <div class="text-center py-8">
        <h1 class="text-3xl font-bold uppercase tracking-wide border-b border-gray-700 inline-block pb-2">Saldo opwaarderen</h1>
    </div>

    <div class="container mx-auto px-4 py-8 text-white">
        
        <div class="max-w-md mx-auto bg-black border border-white rounded-lg p-6">
            
            
            <form action="geld_toevoegen.php" method="post">
                <h3 class="text-xl mb-4"><label for="geld">Hoeveel geld wil je bijschrijven?</label></h3>
                <div class="flex space-x-4">
                    <input 
                        type="number" 
                        step="0.01" 
                        min="0" 
                        id="geld" 
                        name="geld" 
                        required 
                        class="w-full px-4 py-2 bg-[#000000] border border-[#FFFFFF] rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-gray-500"
                        placeholder="Bedrag invoeren"
                    >
                    <input 
                        type="submit" 
                        value="Opwaarderen" 
                        name="bijschrijven"
                        class="bg-black border border-white hover:bg-white text-white hover:text-black px-4 py-2 rounded-lg transition"
                    >
                </div>
            </form>
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