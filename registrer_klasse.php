<?php
// Inkluder db.php fra samme mappe
include(__DIR__ . '/db.php');

// Sjekk at databasekoblingen finnes
if (!$conn) {
    die("Database connection not established.");
}

// Hvis skjemaet er sendt
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Hent verdier fra skjema
    $klasse_navn = trim($_POST['klasse_navn']);
    $trinn = trim($_POST['trinn']);

    // Sjekk at verdiene ikke er tomme
    if (empty($klasse_navn) || empty($trinn)) {
        echo "Vennligst fyll ut alle feltene.";
    } else {
        // Forbered SQL-setning
        $stmt = $conn->prepare("INSERT INTO klasser (klasse_navn, trinn) VALUES (?, ?)");
        if ($stmt) {
            $stmt->bind_param("ss", $klasse_navn, $trinn);

            // Utfør spørringen
            if ($stmt->execute()) {
                echo "Klassen er registrert!";
            } else {
                echo "Feil under registrering: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Feil i SQL-preparering: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrer klasse</title>
</head>
<body>
    <h1>Registrer klasse</h1>
    <form method="post" action="">
        <label for="klasse_navn">Klassenavn:</label>
        <input type="text" id="klasse_navn" name="klasse_navn" required><br><br>

        <label for="trinn">Trinn:</label>
        <input type="text" id="trinn" name="trinn" required><br><br>

        <input type="submit" value="Registrer">
    </form>
</body>
</html>