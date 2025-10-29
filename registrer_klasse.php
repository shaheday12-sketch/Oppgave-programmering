<?php
require_once __DIR__ . '/db.php'; // Koble til databasen

$ok = $err = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Hent og rens verdier fra skjemaet
    $klassekode = trim($_POST['klassekode'] ?? '');
    $klassenavn = trim($_POST['klassenavn'] ?? '');
    $studiumkode = trim($_POST['studiumkode'] ?? '');

    // Sjekk at alle felt er fylt ut
    if ($klassekode === '' || $klassenavn === '' || $studiumkode === '') {
        $err = "Vennligst fyll ut alle felt.";
    } else {
        // 1) Sjekk om klassekode allerede finnes
        $sjekk = $conn->prepare("SELECT 1 FROM klasse WHERE klassekode = ?");
        $sjekk->bind_param("s", $klassekode);
        $sjekk->execute();
        $sjekk->store_result();

        if ($sjekk->num_rows > 0) {
            $err = "Klassekoden '{$klassekode}' finnes allerede. Prøv en annen kode.";
        } else {
            // 2) Sett inn ny klasse
            $stmt = $conn->prepare("
                INSERT INTO klasse (klassekode, klassenavn, studiumkode)
                VALUES (?, ?, ?)
            ");
            $stmt->bind_param("sss", $klassekode, $klassenavn, $studiumkode);

            try {
                if ($stmt->execute()) {
                    $ok = "✅ Klassen '{$klassekode}' ble registrert!";
                } else {
                    $err = "Noe gikk galt under registrering. Prøv igjen.";
                }
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() === 1062) {
                    $err = "Klassekoden '{$klassekode}' finnes allerede. Prøv en annen kode.";
                } else {
                    $err = "Teknisk feil: " . htmlspecialchars($e->getMessage());
                }
            }
            $stmt->close();
        }
        $sjekk->close();
    }
}
?>
<!doctype html>
<html lang="no">
<head>
  <meta charset="utf-8">
  <title>Registrer klasse</title>
  <style>
    body {
      font-family: system-ui, Segoe UI, Roboto, Arial, sans-serif;
      margin: 2rem;
      background: #fafafa;
    }
    input, button {
      padding: .6rem;
      border-radius: 8px;
      border: 1px solid #ccc;
      min-width: 320px;
    }
    .row { margin: .8rem 0; }
    .msg {
      padding: .7rem;
      border-radius: 8px;
      margin: .7rem 0;
      max-width: 520px;
    }
    .ok { background: #e8f7ee; border: 1px solid #9cd7b4; }
    .err { background: #fdeaea; border: 1px solid #f2a3a3; }
    a { color: #1a1a1a; text-decoration: none; }
