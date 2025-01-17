<?php

include "../database/data.php";
include "../logicFiles/roulette_logic.php";

if (!isset($_SESSION['rouletteNummer'])) {
    $_SESSION['rouletteNummer'] = 0;  
}

if (!isset($_SESSION['inzet'])) {
    $_SESSION['inzet'] = 0;
}

// Wanneer er een keuze wordt gemaakt
if (isset($_POST['keuze'])) {
    keuze($saldo, $con);
}

?> 

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casino - Roulette</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="../images/logo.png">
</head>
<body class="flex flex-col bg-black text-white min-h-screen font-mono">
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

    <div class="text-center py-8">
        <h1 class="text-3xl font-bold uppercase tracking-wide border-b border-gray-700 inline-block pb-2">Roulette</h1>
    </div>

    <div class="flex justify-center align-center p-5">
        <div class="p-5 border border-white rounded-lg w-60 text-center">
            <h3>Inzet: <?php echo $_SESSION['inzet'] . " euro"; ?></h3>
        </div>
    </div>

    <div class="flex flex-col justify-center align-center pb-6">
        <div class="flex flex-col justify-center items-center pb-8">
            <div class="flex flex-col justify-center items-center border border-white p-4 rounded-md
            <?php 
            if (isset($_SESSION['resultaat'])) {
                echo 'bg-' . ($_SESSION['resultaat'] == "Gewonnen" ? 'green-500' : 'red-600');
            } else {
                echo 'bg-[#000000]';
            } ?>">
                <div class="flex flex-col justify-center items-center border border-white p-4 rounded-md bg-[#000000]">
                    <form method="POST" action="roulette.php">
                        <table class="table-auto pb-4">  
                            <tr>
                                <td rowspan="3" class='border border-white text-center h-20 w-20 bg-green-500'><button class="<?php echo ($_SESSION['rouletteNummer'] == 0) ? 'bg-yellow-500' : 'bg-green-500'; ?> w-full h-full" type="submit" name="keuze" value="0">0</button></td>
                                <?php 
                                for ($i = 3; $i <= 36; $i += 3) {
                                    $class = ($_SESSION['rouletteNummer'] == $i) 
                                        ? 'bg-yellow-500' 
                                        : (in_array($i, $lijst_rode_nummers) ? 'bg-red-600 hover:bg-red-500' : 'bg-gray-900 hover:bg-gray-600 hover:bg-gray-600');
                                    echo "<td class='border border-white text-center h-20 w-20 $class'><button type='submit' name='keuze' value='$i' class='w-full h-full'>$i</button></td>";
                                }
                                ?>
                                <td class="border border-white text-center bg-gray-700 hover:bg-gray-800"><button type='submit' name='keuze' value='2tegen1_1' class='w-full h-full p-5'>2to1</button></td>
                            </tr>

                            <tr>
                                <?php 
                                for ($i = 2; $i <= 35; $i += 3) {
                                    $class = ($_SESSION['rouletteNummer'] == $i) 
                                        ? 'bg-yellow-500' 
                                        : (in_array($i, $lijst_rode_nummers) ? 'bg-red-600 hover:bg-red-500' : 'bg-gray-900 hover:bg-gray-600');
                                    echo "<td class='border border-white text-center h-20 w-20 $class'><button type='submit' name='keuze' value='$i' class='w-full h-full'>$i</button></td>";
                                }
                                ?>
                                <td class="border border-white text-center bg-gray-700 hover:bg-gray-800"><button type='submit' name='keuze' value='2tegen1_2' class='w-full h-full p-5'>2to1</button></td>
                            </tr>

                            <tr>
                                <?php 
                                for ($i = 1; $i <= 34; $i += 3) {
                                    $class = ($_SESSION['rouletteNummer'] == $i) 
                                        ? 'bg-yellow-500' 
                                        : (in_array($i, $lijst_rode_nummers) ? 'bg-red-600 hover:bg-red-500' : 'bg-gray-900 hover:bg-gray-600');
                                        echo "<td class='border border-white text-center h-20 w-20 $class'><button type='submit' name='keuze' value='$i' class='w-full h-full'>$i</button></td>";
                                }
                                ?>
                                <td class="border border-white text-center bg-gray-700 hover:bg-gray-800"><button type='submit' name='keuze' value='2tegen1_3' class='w-full h-full p-5'>2to1</button></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="4" class="border border-white text-center h-20 bg-gray-700 hover:bg-gray-800"><button type="submit" name="keuze" value="eerste12" class="w-full h-20">1st12</button></td>
                                <td colspan="4" class="border border-white text-center h-20 bg-gray-700 hover:bg-gray-800"><button type="submit" name="keuze" value="tweede12" class="w-full h-20">2nd12</button></td>
                                <td colspan="4" class="border border-white text-center h-20 bg-gray-700 hover:bg-gray-800"><button type="submit" name="keuze" value="derde12" class="w-full h-20">3th12</button></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="2" class="border border-white text-center h-20 bg-gray-700 hover:bg-gray-800"><button type="submit" name="keuze" value="1tot18" class="w-full h-20">1to18</button></td>
                                <td colspan="2" class="border border-white text-center h-20 bg-gray-700 hover:bg-gray-800"><button type="submit" name="keuze" value="EVEN" class="w-full h-20">EVEN</button></td>
                                <td colspan="2" class="border border-white text-center h-20 bg-gray-700 hover:bg-gray-800 bg-red-600 hover:bg-red-500"><button type="submit" name="keuze" value="ROOD" class="w-full h-20"></button></td>
                                <td colspan="2" class="border border-white text-center h-20 bg-gray-900 hover:bg-gray-600 bg-black-500"><button type="submit" name="keuze" value="ZWART" class="w-full h-20"></button></td>
                                <td colspan="2" class="border border-white text-center h-20 bg-gray-700 hover:bg-gray-800"><button type="submit" name="keuze" value="ONEVEN" class="w-full h-20">ODD</button></td>
                                <td colspan="2" class="border border-white text-center h-20 bg-gray-700 hover:bg-gray-800"><button type="submit" name="keuze" value="19tot36" >19to36</button></td>
                                <td></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>
        <div class="container-fluid flex justify-center w-100">
            <div class="flex flex-col xl:flex-row justify-center border border-white w-full xl:w-3/5">
                <div class="flex flex-col border border-t-0 border-l-0 border-b-0 w-full xl:w-1/2">
                    <div class="border border-t-0 border-l-0 border-r-0">
                        <h2 class="text-center pb-2 pt-2">Kies je inzet:</h2>
                    </div>
                    <div class="flex align-center justify-center p-4">
                        <form method="POST">
                            <table class="table-auto">
                                <tr>
                                    <td class="p-2">
                                        <button type="submit" name="inzet" value="0.01"><img alt="muntje" src="../images/muntjes/casino_1.png" class="h-20 w-20 object-contain"></button>
                                    </td>
                                    <td class="p-2">
                                        <button type="submit" name="inzet" value="0.05"><img alt="muntje" src="../images/muntjes/casino_5.png" class="h-20 w-20 object-contain"></button>
                                    </td>
                                    <td class="p-2">
                                        <button type="submit" name="inzet" value="0.10"><img alt="muntje" src="../images/muntjes/casino_10.png" class="h-20 w-20 object-contain"></button>
                                    </td>
                                    <td class="p-2">
                                        <button type="submit" name="inzet" value="0.25"><img alt="muntje" src="../images/muntjes/casino_25.png" class="h-20 w-20 object-contain"></button>
                                    </td>
                                    <td class="p-2">
                                        <button type="submit" name="inzet" value="1.00"><img alt="muntje" src="../images/muntjes/casino_100.png" class="h-20 w-20 object-contain"></button>
                                    </td>
                                    <td class="p-2">
                                        <button type="submit" name="inzet" value="5.00"><img alt="muntje" src="../images/muntjes/casino_500.png" class="h-20 w-20 object-contain"></button>
                                    </td>
                                    <td class="p-2">
                                        <button type="submit" name="inzet" value="10.00"><img alt="muntje" src="../images/muntjes/casino_1000.png" class="h-20 w-20 object-contain"></button>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>              
                <div class="flex flex-col w-full xl:w-1/2">
                    <div class="border border-t-0 border-l-0 border-r-0">
                        <h2 class="text-center pb-2 pt-2">Vul je inzet in:</h2>
                    </div>
                    <div class="p-4 text-center flex flex-col items-center">
                        <form method="POST">
                            <div class="w-full px-4 py-2 bg-[#000000] border border-[#FFFFFF] rounded-lg text-white">
                                <input type="number" name="aangepaste_inzet" placeholder="Aangepaste inzet" min="0.01" step="0.01" class="h-10 bg-[#000000] w-full">
                            </div>
                            <div class="w-full bg-[#000000] border border-[#FFFFFF] rounded-lg text-white mt-3 hover:bg-[#FFFFFF] hover:text-black hover:rounded-lg transition">
                                <button type="submit" name="aangepaste_inzet_verzenden" value="1" class="h-full w-full px-4 py-2">Selecteer</button>
                            </div>
                        </form>
                    </div>
                </div>
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