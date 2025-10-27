<?php
require_once __DIR__ . '/db.php';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode = trim($_POST['klassekode']);
    $navn = trim($_POST['klassenavn']);
    $studium = trim($_POST['studiumkode']);

    if ($kode && $navn && $studium) {
        $stmt = $conn->prepare("INSERT INTO klasse (klassekode, klassenavn, studiumkode) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $kode, $navn, $studium);
        if ($stmt->execute()) {
            $message = "✅ Klassen ble registrert!";
        } else {
            $message = "Feil ved registrering.";
        }
    } else {
        $message = "Fyll ut alle felt.";
    }
}
?>
<h2>Registrer ny klasse</h2>
<form method="post">
  <p style="color:green;"><?php echo $message; ?></p>
  Klassekode: <input type="text" name="klassekode" required><br>
  Klassenavn: <input type="text" name="klassenavn" required><br>
  Studiumkode: <input type="text" name="studiumkode" required><br>
  <input type="submit" value="Lagre">
</form>
<a href="index.php">⬅ Tilbake</a>
