<?php
<<<<<<< HEAD
require 'db.php';
=======
// Inkluder tilkoblingen – endre sti hvis dp.php ligger et annet sted
require_once __DIR__ . '/dp.php';

$message = '';
$is_ok    = false;
>>>>>>> 7609f7f4220fcde6c2c4bbbf7a800ce27a852e6e

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $klassekode = $_POST['klassekode'];
    $klassenavn = $_POST['klassenavn'];
    $studiumkode = $_POST['studiumkode'];

    $sql = "INSERT INTO klasse (klassekode, klassenavn, studiumkode) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $klassekode, $klassenavn, $studiumkode);

    if ($stmt->execute()) {
        echo "<p>Klasse registrert!</p>";
    } else {
        echo "<p>Feil ved registrering: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <title>Registrer klasse</title>
</head>
<body>
    <h1>Registrer klasse</h1>
    <form method="POST">
        <label>Klassekode: <input type="text" name="klassekode" required></label><br>
        <label>Klassenavn: <input type="text" name="klassenavn" required></label><br>
        <label>Studiumkode: <input type="text" name="studiumkode" required></label><br>
        <button type="submit">Lagre</button>
    </form>

    <p><a href="index.php">Tilbake</a></p>
</body>
</html>
<<<<<<< HEAD

=======
>>>>>>> 7609f7f4220fcde6c2c4bbbf7a800ce27a852e6e
