<?php

session_start();

include "dbcon.php";

// Check of je goed verbonden bent met de db
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Haal de informatie op die bij de ingelogde gebruiker hoort
$user_id = isset($_SESSION['ingelogde_gebruiker']) ? $_SESSION['ingelogde_gebruiker'] : null;

// Check of de gebruiker is ingelogd
if ($user_id === null) {
    // Zo niet, stuur hem terug naar de inlog pagina
    header("Location: ../pages/login.php");
    exit();
}

// Maak een query om informatie uit de db te halen over de ingelogde gebruiker
$query = "SELECT gebruikersnaam, email, geboortedatum, saldo
          FROM gebruikers 
          WHERE gebruiker_id = ?";

// Error checking van de prepare statement
$stmt = mysqli_prepare($con, $query);
if ($stmt === false) {
    die("Prepare failed: " . mysqli_error($con));
}

// Error checking van de prepare statement
if (!mysqli_stmt_bind_param($stmt, "i", $user_id)) {
    die("Binding parameters failed: " . mysqli_stmt_error($stmt));
}

if (!mysqli_stmt_execute($stmt)) {
    die("Execute failed: " . mysqli_stmt_error($stmt));
}

// Haal het resultaat op
$result = mysqli_stmt_get_result($stmt);
if (!$result) {
    die("Getting result failed: " . mysqli_stmt_error($stmt));
}

$user_data = mysqli_fetch_assoc($result);

//  Geef de gegevens een variabele
$gebruikersnaam = isset($user_data['gebruikersnaam']) ? $user_data['gebruikersnaam'] : "!!Error!!";
$email = isset($user_data['email']) ? $user_data['email'] : "";
$geboortedatum = isset($user_data['geboortedatum']) ? $user_data['geboortedatum'] : "";
$saldo = isset($user_data['saldo']) ? $user_data['saldo'] : 0;

// Sluit het statement af
mysqli_stmt_close($stmt);

?>