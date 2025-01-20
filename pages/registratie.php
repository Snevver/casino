<?php
include '../database/dbcon.php';

$errors = [];

if (isset($_POST['registreer'])) {
    // zet de gegevens in variabelen
    $gebruikersnaam = ($_POST['gebruikersnaam']);
    $email = ($_POST['email']);
    $geboortedatum = $_POST['geboortedatum'];
    $wachtwoord = $_POST['wachtwoord'];
    $herhaal_wachtwoord = $_POST['herhaal_wachtwoord'];

    // Check de leeftijd
    $dob = new DateTime($geboortedatum);
    $vandaag = new DateTime(); 
    $leeftijd = $vandaag->diff($dob)->y;

    if ($leeftijd < 18) {
        $errors[] = "Je bent helaas te jong om door te gaan.";
    }

    // Check of het wachtwoord overeen komt
    if ($wachtwoord !== $herhaal_wachtwoord) {
        $errors[] = "De wachtwoorden komen niet overeen!";
    } 

    // Check of de gebruikersnaam al bestaat
    $stmt = $con->prepare("SELECT * FROM gebruikers WHERE gebruikersnaam = ?");
    $stmt->bind_param("s", $gebruikersnaam);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors[] = "Gebruikersnaam is al in gebruik.";
    }

    // Als er een errors zijn opgedoken
    if (empty($errors)) {
        // Hash het wachtwoord
        $hashed_wachtwoord = password_hash($wachtwoord, PASSWORD_DEFAULT);

        // Prepared statements om de informatie in de database op te slaan
        $stmt = $con->prepare("INSERT INTO gebruikers (gebruikersnaam, email, wachtwoord, geboortedatum, saldo) VALUES (?, ?, ?, ?, 0)");
        $stmt->bind_param("ssss", $gebruikersnaam, $email, $hashed_wachtwoord, $geboortedatum);
        
        if ($stmt->execute()) {
            header("Location: ../index.php");
            exit();
        } else {
            $errors[] = "Registratie mislukt. Probeer het opnieuw.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registratie</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="../images/logo.png">
</head>
<body class="bg-black text-white min-h-screen flex items-center justify-center" style="font-family: 'Andale Mono', monospace;">
    <div class="w-full max-w-sm bg-[#000000] p-8 rounded-lg shadow-lg border border-white">
        <h2 class="text-3xl font-bold text-center mb-6">Maak een account aan!</h2>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-700 text-white p-4 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="registratie.php" method="post" class="space-y-6">
            <!-- Gebruikersnaam -->
            <div>
                <label for="gebruikersnaam" class="block text-sm font-medium mb-2">Gebruikersnaam</label>
                <input 
                    type="text" 
                    id="gebruikersnaam" 
                    name="gebruikersnaam" 
                    class="w-full px-4 py-2 bg-[#CCCCCC] border border-[#000000] rounded-lg text-black focus:outline-none focus:ring-2 focus:ring-gray-500"
                    required
                    value="<?php echo isset($gebruikersnaam) ? htmlspecialchars($gebruikersnaam) : ''; ?>"
                >
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium mb-2">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="w-full px-4 py-2 bg-[#CCCCCC] border border-[#000000] rounded-lg text-black focus:outline-none focus:ring-2 focus:ring-gray-500"
                    required
                    value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                >
            </div>

            <!-- Geboortedatum -->
            <div>
                <label for="geboortedatum" class="block text-sm font-medium mb-2">Geboortedatum</label>
                <input 
                    type="date" 
                    id="geboortedatum" 
                    name="geboortedatum" 
                    class="w-full px-4 py-2 bg-[#CCCCCC] border border-[#000000] rounded-lg text-black focus:outline-none focus:ring-2 focus:ring-gray-500"
                    required
                    value="<?php echo isset($geboortedatum) ? htmlspecialchars($geboortedatum) : ''; ?>"
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

            <!-- Herhaal wachtwoord -->
            <div>
                <label for="herhaal_wachtwoord" class="block text-sm font-medium mb-2">Herhaal wachtwoord</label>
                <input 
                    type="password" 
                    id="herhaal_wachtwoord" 
                    name="herhaal_wachtwoord" 
                    class="w-full px-4 py-2 bg-[#CCCCCC] border border-[#000000] rounded-lg text-black focus:outline-none focus:ring-2 focus:ring-gray-500"
                    required
                >
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                name="registreer" 
                class="w-full bg-black border border-white text-white px-6 py-3 rounded-lg hover:bg-white hover:text-black transition">
                Registreer!
            </button>
        </form>

        <!-- Login Link -->
        <p class="mt-4 text-center text-sm">
            Heeft u al een account? 
            <a href="login.php" class="text-gray-400 underline hover:text-white">
                Klik hier
            </a>
        </p>
    </div>
</body>
</html>
