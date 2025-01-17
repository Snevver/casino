<?php 

include "../database/data.php";
include "../logicFiles/blackjack_logic.php";

// Zet de inzet in de sessie alleen als er een nieuwe inzet is opgegeven
if (isset($_POST['inzet']) && is_numeric($_POST['inzet']) && $_POST['inzet'] > 0) {
    if ($_POST['inzet'] <= $saldo) {
        $_SESSION['inzet'] = (int)$_POST['inzet'];
    } 
}

// Zet de standaard inzet op 0
if (!isset($_SESSION['inzet'])) {
    $_SESSION['inzet'] = 0;
}

// Start een nieuwe ronde
if (isset($_SESSION['inzet']) && $_SESSION['inzet'] <= $saldo && isset($_POST['start'])) {
    start($saldo, $con);
}

// Hit-knop
if (isset($_POST['hit']) && $_SESSION['spel_klaar'] == false) {
    hit($saldo, $con);
}

// Stand-knop
if (isset($_POST['stand']) && $_SESSION['spel_klaar'] == false) {
    stand($saldo, $con);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casino - Black Jack</title>
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

    <?php error_check($saldo); ?>

    <div class="container-fluid mx-auto py-10 w-100">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold uppercase tracking-wide border-b border-gray-700 inline-block pb-2">Blackjack</h1>
        </div>

        <?php if (!isset($_SESSION['start'])) { ?>
            <div class="flex justify-center">
                <div class="w-96 p-6 bg-black border border-white rounded-lg shadow-lg">
                    <h2 class="text-lg font-semibold mb-4 text-center">Voer je inzet in om te starten</h2>
                    <form method="POST">
                        <div class="mb-4">
                            <input type="number" name="inzet" placeholder="Inzet" min="0.01" step="0.01" 
                                class="w-full px-4 py-2 bg-black border border-white rounded-lg text-white focus:outline-none focus:border-white">
                        </div>
                        <button type="submit" name="start" value="Begin!" 
                                class="w-full py-2 bg-black border border-white text-white font-bold rounded-lg hover:text-black hover:bg-white transition">
                            Start Spel
                        </button>
                    </form>
                </div>
            </div>
        <?php } else { ?>

            <div class="flex flex-col space-y-8 items-center justify-center">
                <h2 class="text-xl font-bold">Inzet: €<?php echo $_SESSION['inzet']; ?></h2>
                <div class="border border-white p-6 rounded-lg w-100 flex justify-center
                <?php 
                if (isset($_SESSION['resultaat'])) {
                    if ($_SESSION['resultaat'] == "Gelijkspel!") {
                    echo 'bg-[#FFA500]';  
                    } elseif ($_SESSION['resultaat'] == "Gewonnen!") {
                    echo 'bg-green-500';  
                    } elseif ($_SESSION['resultaat'] == "Verloren!") {
                    echo 'bg-red-600';    
                    } else {
                    echo 'bg-black';     
                    } 
                } 
                ?>">
                    <div class="grid grid-cols-2 gap-6 border border-white rounded-lg bg-black p-6">
                        <!-- Dealer kaarten -->
                        <div class="flex justify-center items-center flex-col">
                            <h3 class="text-lg font-semibold text-center mb-4 text-white">Dealer kaarten</h3>
                            <div class="flex justify-center items-center gap-2 flex-row flex-wrap">
                                <?php
                                    echo "<img src='../images/speelkaarten/{$_SESSION['dealer_kaarten'][0]}.png' alt='Dealer kaart' class='h-52 max-w-full object-contain'>";
                                    echo $_SESSION['spel_klaar']
                                    ? "<img src='../images/speelkaarten/{$_SESSION['dealer_kaarten'][1]}.png' alt='Dealer kaart' class='h-52 max-w-full object-contain'>"
                                    : "<img src='../images/speelkaarten/mystery.png' alt='Mystery kaart' class='h-52 max-w-full object-contain'>";
                                    for ($i = 2; $i < count($_SESSION['dealer_kaarten']); $i++) {
                                    echo "<img src='../images/speelkaarten/{$_SESSION['dealer_kaarten'][$i]}.png' alt='Dealer kaart' class='h-52 max-w-full object-contain'>";
                                    }
                                ?>
                            </div>
                        </div>
                    

                        <!-- Speler kaarten -->
                        <div class="flex justify-center items-center flex-col">
                            <h3 class="text-lg font-semibold text-center mb-4 text-white">Jouw kaarten</h3>
                            <div class="flex justify-center items-center flex-wrap gap-2">
                                <?php
                                    foreach ($_SESSION['speler_kaarten'] as $player_card) {
                                    echo "<img src='../images/speelkaarten/{$player_card}.png' alt='Speler kaart' class='h-52 max-w-full object-contain'>";
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                                
                <p class="text-center text-lg font-bold">Jouw totaal: <?php echo $_SESSION['speler_totaal']; ?></p>
                <?php if ($_SESSION['spel_klaar']) { ?>
                    <p class="text-center text-lg font-bold mt-4">Dealer totaal: <?php echo $_SESSION['dealer_totaal']; ?></p>
                <?php } ?>
                                
                <!-- Knoppen -->
                <?php if ($_SESSION['spel_klaar']) { ?>
                    <div class="text-center mt-4">
                        <p class="text-lg font-semibold"><?php echo $_SESSION['resultaat']; ?></p>
                        <form method="POST">
                            <div class="mt-4">
                                <input type="number" name="inzet" placeholder="Nieuwe inzet" min="0.01" step="0.01" value="<?php echo $_SESSION['inzet']; ?>" 
                                class="w-40 px-4 py-2 bg-black border border-gray-600 rounded-lg text-white focus:outline-none focus:border-white">
                            </div>
                            <button type="submit" name="start" value="Opnieuw" 
                            class="mt-2 px-6 py-2 bg-black border border-white text-white font-bold rounded-lg hover:bg-white hover:text-black transition">
                            Opnieuw Spelen
                            </button>
                        </form>
                    </div>
                <?php } else { ?>
                    <div class="flex space-x-4">
                        <form method="POST">
                            <button type="submit" name="hit" value="Hit" 
                            class="px-6 py-2 bg-black border border-white text-white font-bold rounded-lg hover:bg-white hover:text-black transition">
                            Hit
                            </button>
                        </form>
                        <form method="POST">
                            <button type="submit" name="stand" value="Stand" 
                            class="px-6 py-2 bg-black border border-white text-white font-bold rounded-lg hover:bg-white hover:text-black transition">
                            Stand
                            </button>
                        </form>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
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



                