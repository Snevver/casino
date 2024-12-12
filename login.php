<?php

session_start();

include 'dbcon.php';

$errors = [];

if (isset($_POST['login'])) {
    $gebruikersnaam = $_POST['gebruikersnaam'];
    $wachtwoord = $_POST['wachtwoord'];

    // Maak een query om te kijken of de opgegeven username in de database zit
    $query = "SELECT * FROM gebruikers 
              WHERE gebruikersnaam = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $gebruikersnaam);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Als de gebruikersnaam voorkomt in de database
    if ($result && mysqli_num_rows($result) > 0) {

        // Haal de data van de user op uit de database (alles wat in dezelfde row staat als de overeenkomende username)
        $user_data = mysqli_fetch_assoc($result);
        
        // Als het wachtwoord overeenkomt met wachtwoord wat bij de username hoort in de database
        if (password_verify($wachtwoord, $user_data['wachtwoord'])) {
            // Zet in de sessie dat de gebruiker is ingelogd
            $_SESSION['ingelogde_gebruiker'] = $user_data['gebruiker_id'];

            // Redirect de gebruiker naar de home pagina
            header("Location: home.php"); 
            exit();
        } else {  
            // Als het wachtwoord niet overeenkomt met het wachtwoord wat bij de username hoort in de database
            $errors[] = "Onjuist wachtwoord!";
        }
    } else {   
        // Als de gebruikersnaam niet voorkomt in de database
        $errors[] = "Gebruiker niet gevonden!";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="logo.png">
</head>
<body class="bg-black text-white min-h-screen flex items-center justify-center" style="font-family: 'Andale Mono', monospace;">
    <div class="w-full max-w-sm bg-[#000000] p-8 rounded-lg shadow-lg border border-white">
        <h2 class="text-3xl font-bold text-center mb-6">Login</h2>
        
        <!-- Error Messages -->
        <?php if (!empty($errors)): ?>
            <div class="bg-red-700 text-white p-4 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="login.php" method="post" class="space-y-6">
            <!-- Gebruikersnaam -->
            <div>
                <label for="gebruikersnaam" class="block text-sm font-medium mb-2">Gebruikersnaam</label>
                <input 
                    type="text" 
                    id="gebruikersnaam" 
                    name="gebruikersnaam" 
                    class="w-full px-4 py-2 bg-[#CCCCCC] border border-[#000000] rounded-lg text-black focus:outline-none focus:ring-2 focus:ring-gray-500"
                    required
                >
            </div>

            <!-- Wachtwoord -->
            <div>
                <label for="wachtwoord" class="block text-sm font-medium mb-2">Wachtwoord</label>
                <input 
                    type="password" 
                    id="wachtwoord" 
                    name="wachtwoord" 
                    class="w-full px-4 py-2 bg-[#CCCCCC] border border-[#000000] rounded-lg text-black focus:outline-none focus:ring-2 focus:ring-gray-500"
                    required
                >
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                name="login"
                class="w-full bg-black border border-white text-white px-6 py-3 rounded-lg hover:bg-white hover:text-black transition">
                Login
            </button>
        </form>

        <!-- Registratie Link -->
        <p class="mt-4 text-center text-sm">
            Nog geen account? 
            <a href="registratie.php" class="text-gray-400 underline hover:text-white">
                Maak een account aan!
            </a>
        </p>
    </div>

</body>
</html>
