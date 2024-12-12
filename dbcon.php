<?php

$host = "localhost";
$username = "bit_academy";
$password = "bit_academy";
$database = "svens_casino";

$con = mysqli_connect($host, $username, $password, $database);

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>

