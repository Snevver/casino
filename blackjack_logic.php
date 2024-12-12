<?php

// Zet alle kaarten in arrays volgens hun waarde
$twee = [1, 14, 27, 40];
$drie = [2, 15, 28, 41];
$vier = [3, 16, 29, 42];
$vijf = [4, 17, 30, 43];
$zes = [5, 18, 31, 44];
$zeven = [6, 19, 32, 45];
$acht = [7, 20, 33, 46];
$negen = [8, 21, 34, 47];
$tien = [9, 22, 35, 48];
$boer = [11, 24, 37, 50];
$koningin = [12, 25, 38, 51];
$koning = [13, 26, 39, 52];
$aas = [10, 23, 36, 49];


// Twee functies om de winst/verlies te updaten in de database

function resultaat_gewonnen($saldo, $con) {
    $_SESSION['resultaat'] = "Gewonnen!";
    $saldo += $_SESSION['inzet'];
    $_SESSION['spel_klaar'] = true;

    $user_id = $_SESSION['ingelogde_gebruiker'];
    $nieuw_saldo = $saldo;
    
    $query = "UPDATE gebruikers SET saldo = ? WHERE gebruiker_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "di", $nieuw_saldo, $user_id);
    
    if (!mysqli_stmt_execute($stmt)) {
        die("Error updating saldo: " . mysqli_stmt_error($stmt));
    }
    
    mysqli_stmt_close($stmt);

    return $_SESSION['resultaat'];
}

function resultaat_verloren($saldo, $con) {
    $_SESSION['resultaat'] = "Verloren!";
    $saldo -= $_SESSION['inzet'];
    $_SESSION['spel_klaar'] = true;

    $user_id = $_SESSION['ingelogde_gebruiker'];
    $nieuw_saldo = $saldo;
    
    $query = "UPDATE gebruikers SET saldo = ? WHERE gebruiker_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "di", $nieuw_saldo, $user_id);
    
    if (!mysqli_stmt_execute($stmt)) {
        die("Error updating saldo: " . mysqli_stmt_error($stmt));
    }
    
    mysqli_stmt_close($stmt);

    return $_SESSION['resultaat'];
}

// Functie om de vaste waarde van een kaart op te halen (azen zijn altijd 1)
function vaste_kaart_waarde($kaart) {
    global $twee, $drie, $vier, $vijf, $zes, $zeven, $acht, $negen, 
           $tien, $boer, $koningin, $koning, $aas;
    if (in_array($kaart, $twee)) return 2;
    if (in_array($kaart, $drie)) return 3;
    if (in_array($kaart, $vier)) return 4;
    if (in_array($kaart, $vijf)) return 5;
    if (in_array($kaart, $zes)) return 6;
    if (in_array($kaart, $zeven)) return 7;
    if (in_array($kaart, $acht)) return 8;
    if (in_array($kaart, $negen)) return 9;
    if (in_array($kaart, $tien) || in_array($kaart, $boer) || 
        in_array($kaart, $koningin) || in_array($kaart, $koning)) return 10;
    if (in_array($kaart, $aas)) return 1;
}

// Functie om het totaal van de kaarten te berekenen
function bereken_totaal($kaarten) {
    global $aas;
    if (!isset($aas)) {
        $aas = [];
    }
    $totaal = 0;
    $aantal_azen = 0;
    foreach ($kaarten as $kaart) {
        if (in_array($kaart, $aas)) {
            $aantal_azen++;
        }
        $totaal += vaste_kaart_waarde($kaart);
    }
    for ($i = 0; $i < $aantal_azen; $i++) {
        if ($totaal + 10 <= 21) {
            $totaal += 10;
        }
    }
    return $totaal;
}

function start($saldo, $con) {
    $_SESSION['start'] = true;
    $_SESSION['spel_klaar'] = false;
    unset($_SESSION['resultaat'], $_SESSION['dealer_totaal'], $_SESSION['speler_totaal'], 
        $_SESSION['dealer_kaarten'], $_SESSION['speler_kaarten']);

    // Maak een variabelen met een geshudde lijst met alle getallen van de speelkaarten
    $kaarten = range(1, 52);
    shuffle($kaarten);

    // Geef de speler en de dealer allebij 2 kaarten en haal deze kaarten van de lijst met overige kaarten af
    $_SESSION['dealer_kaarten'] = array_splice($kaarten, 0, 2);
    $_SESSION['speler_kaarten'] = array_splice($kaarten, 0, 2);

    // Bereken het totaal van de kaarten
    $_SESSION['dealer_totaal'] = bereken_totaal($_SESSION['dealer_kaarten']);
    $_SESSION['speler_totaal'] = bereken_totaal($_SESSION['speler_kaarten']);

    // Check voor een black jack
    if ($_SESSION['speler_totaal'] == 21) {
        resultaat_gewonnen($saldo, $con);
        header("Location: blackjack.php");
    }
}

function hit($saldo, $con) {
    // Geef de speler een nieuwe kaart
    $nieuwe_kaart = rand(1, 52);
    while (in_array($nieuwe_kaart, $_SESSION['speler_kaarten']) || in_array($nieuwe_kaart, $_SESSION['dealer_kaarten'])) {
        $nieuwe_kaart = rand(1, 52);
    }

    // Voeg de nieuwe kaart toe aan de speler kaarten en reken het totaal opnieuw uit
    $_SESSION['speler_kaarten'][] = $nieuwe_kaart;
    $_SESSION['speler_totaal'] = bereken_totaal($_SESSION['speler_kaarten']);

    // Check of de speler een totaal boven de 21 heeft of juist precies 21
    if ($_SESSION['speler_totaal'] > 21) {
        resultaat_verloren($saldo, $con);
        header("Location: blackjack.php");
    } elseif ($_SESSION['speler_totaal'] == 21) {
        resultaat_gewonnen($saldo, $con);
        header("Location: blackjack.php");
    }
}

function stand($saldo, $con) {
    // Geef de dealer kaarten tot hij 17 of meer totale waarde heeft
    while ($_SESSION['dealer_totaal'] < 17) {
        do {
            $nieuwe_kaart = rand(1, 52);
        } while (in_array($nieuwe_kaart, $_SESSION['speler_kaarten']) || 
                in_array($nieuwe_kaart, $_SESSION['dealer_kaarten']));
        
        // Voeg de nieuwe kaart steeds toe aan de lijst met kaarten van de dealer en bereken steeds de nieuwe waarde
        $_SESSION['dealer_kaarten'][] = $nieuwe_kaart;
        $_SESSION['dealer_totaal'] = bereken_totaal($_SESSION['dealer_kaarten']);
    }

    // Bepaal het resulaat
    if ($_SESSION['dealer_totaal'] > 21 || $_SESSION['speler_totaal'] > $_SESSION['dealer_totaal']) {
        resultaat_gewonnen($saldo, $con);
    } elseif ($_SESSION['speler_totaal'] < $_SESSION['dealer_totaal']) {
        resultaat_verloren($saldo, $con);
    } else {
        $_SESSION['resultaat'] = "Gelijkspel!";
        $_SESSION['spel_klaar'] = true;
    }

    // Refresh de pagina om het saldo up-to-date te houden
    header("Location: blackjack.php");
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

?>