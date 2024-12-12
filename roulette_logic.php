<?php

$lijst_rode_nummers = [1, 3, 5, 7, 9, 12, 14, 16, 18, 19, 21, 25, 27, 30, 32, 34, 35, 36];
$lijst_zwarte_nummers = [2, 4, 6, 8, 10, 11, 13, 15, 17, 20, 22, 23, 24, 26, 28, 29, 31, 33];

function roulette_rng() {
    return rand(0, 36);
}

function bereken_winst_of_verlies($keuze, $rouletteNummer, $inzet) {
    $lijst_voor_2tegen1_1 = [3, 6, 9, 12, 15, 18, 21, 24, 27, 30, 33, 36];
    $lijst_voor_2tegen1_2 = [2, 5, 8, 11, 14, 17, 20, 23, 26, 29, 32, 35];
    $lijst_voor_2tegen1_3 = [1, 4, 7, 10, 13, 16, 19, 22, 25, 28, 31, 34];
    $lijst_rode_nummers = [1, 3, 5, 7, 9, 12, 14, 16, 18, 19, 21, 25, 27, 30, 32, 34, 35, 36];
    $lijst_zwarte_nummers = [2, 4, 6, 8, 10, 11, 13, 15, 17, 20, 22, 23, 24, 26, 28, 29, 31, 33];

    if (is_numeric($keuze)) {
        if ($rouletteNummer == $keuze) {
            $_SESSION['resultaat'] = "Gewonnen";
            return 35 * $inzet;

        } else {
            $_SESSION['resultaat'] = "Verloren";
            return -$inzet;
        }
    }

    switch ($keuze) {
        case "2tegen1_1":
            if (in_array($rouletteNummer, $lijst_voor_2tegen1_1)) {
                $_SESSION['resultaat'] = "Gewonnen";
                return 2 * $inzet;
            } else {
                $_SESSION['resultaat'] = "Verloren";
                return -$inzet;
            }
        case "2tegen1_2":
            if (in_array($rouletteNummer, $lijst_voor_2tegen1_2)) {
                $_SESSION['resultaat'] = "Gewonnen";
                return 2 * $inzet;
            } else {
                $_SESSION['resultaat'] = "Verloren";
                return -$inzet;
            }
        case "2tegen1_3":
            if (in_array($rouletteNummer, $lijst_voor_2tegen1_3)) {
                $_SESSION['resultaat'] = "Gewonnen";
                return 2 * $inzet;
            } else {
                $_SESSION['resultaat'] = "Verloren";
                return -$inzet;
            }
        case "eerste12":
            if (in_array($rouletteNummer, range(1, 12))) {
                $_SESSION['resultaat'] = "Gewonnen";
                return 2 * $inzet;
            } else {
                $_SESSION['resultaat'] = "Verloren";
                return -$inzet;
            }
        case "tweede12":
            if (in_array($rouletteNummer, range(13, 24))) {
                $_SESSION['resultaat'] = "Gewonnen";
                return 2 * $inzet;
            } else {
                $_SESSION['resultaat'] = "Verloren";
                return -$inzet;
            }
        case "derde12":
            if (in_array($rouletteNummer, range(25, 36))) {
                $_SESSION['resultaat'] = "Gewonnen";
                return 2 * $inzet;
            } else {
                $_SESSION['resultaat'] = "Verloren";
                return -$inzet;
            }
        case "EVEN":
            if ($rouletteNummer % 2 == 0) {
                $_SESSION['resultaat'] = "Gewonnen";
                return $inzet;  
            } else {
                $_SESSION['resultaat'] = "Verloren";
                return -$inzet;
            }
        case "ONEVEN":
            if ($rouletteNummer % 2 !== 0) {
                $_SESSION['resultaat'] = "Gewonnen";
                return $inzet;  
            } else {
                $_SESSION['resultaat'] = "Verloren";
                return -$inzet;
            }
        case "1tot18":
            if (in_array($rouletteNummer, range(1, 18))) {
                $_SESSION['resultaat'] = "Gewonnen";
                return $inzet;
            } else {
                $_SESSION['resultaat'] = "Verloren";
                return -$inzet;
            }
        case "19tot36":
            if (in_array($rouletteNummer, range(19, 36))) {
                $_SESSION['resultaat'] = "Gewonnen";
                return $inzet;
            } else {
                $_SESSION['resultaat'] = "Verloren";
                return -$inzet;
            }
        case "ROOD":
            if (in_array($rouletteNummer, $lijst_rode_nummers)) {
                $_SESSION['resultaat'] = "Gewonnen";
                return $inzet;
            } else {
                $_SESSION['resultaat'] = "Verloren";
                return -$inzet;
            }
        case "ZWART":
            if (in_array($rouletteNummer, $lijst_zwarte_nummers)) {
                $_SESSION['resultaat'] = "Gewonnen";
                return $inzet;
            } else {
                $_SESSION['resultaat'] = "Verloren";
                return -$inzet;
            }
        default:
            throw new Exception("Invalid keuze");
    }
}

function keuze($saldo, $con) {
        // Geef de keuze een variabele
        $keuze = $_POST['keuze'];

        // Krijg een random nummer opo de tafel
        $_SESSION['rouletteNummer'] = roulette_rng();
        
        // Bereken nieuwe saldo
        $nieuw_saldo = $saldo;
        $nieuw_saldo += bereken_winst_of_verlies($keuze, $_SESSION['rouletteNummer'], $_SESSION['inzet']);
        $user_id = $_SESSION['ingelogde_gebruiker'];
        $query = "UPDATE gebruikers SET saldo = ? WHERE gebruiker_id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "di", $nieuw_saldo, $user_id);
        
        if (!mysqli_stmt_execute($stmt)) {
            die("Error updating saldo: " . mysqli_stmt_error($stmt));
        }
        
        mysqli_stmt_close($stmt);
    
        header("Location:roulette.php");
}

function error_check($saldo) {
    if ($saldo <= 0) {
        echo "<p class='bg-red-700 text-white p-4 rounded-lg mb-6'>Je balans is op, <a href='geld_toevoegen.php' class='underline'>schrijf meer geld bij.</a></p>";
    }

    // Controleer of het inzetbedrag hoger is dan het huidige saldo
    if (isset($_POST['inzet'])) {
        $_SESSION['inzet'] = floatval($_POST['inzet']);
        if ($_SESSION['inzet'] > $saldo) {
            echo "<p class='bg-red-700 text-white p-4 rounded-lg mb-6'>Je inzet kan niet hoger zijn dan je saldo!</p>";
            $_SESSION['inzet'] = 0;
        } 
    }
    
    if (isset($_POST['aangepaste_inzet'])) {
        $_SESSION['inzet'] = floatval($_POST['aangepaste_inzet']);
        if ($_SESSION['inzet'] > $saldo) {
            echo "<p class='bg-red-700 text-white p-4 rounded-lg mb-6'>Je inzet kan niet hoger zijn dan je saldo!</p>";
            $_SESSION['inzet'] = 0;
        } 
    }

    if ($saldo - $_SESSION['inzet'] < 0) {
        echo "<p class='bg-red-700 text-white p-4 rounded-lg mb-6'>Je kan geen " . $_SESSION['inzet'] . " euro meer inzetten</p>";
        $_SESSION['inzet'] = 0;
    }
}

