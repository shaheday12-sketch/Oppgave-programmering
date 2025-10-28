<?php
// Koble til databasen (forutsetter at db.php ligger i samme mappe)
require_once __DIR__ . '/db.php';
$ok = $err = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $klassekode = trim($_POST['klassekode'] ?? '');
    $klassenavn = trim($_POST['klassenavn'] ?? '');
    $studiumkode = trim($_POST['studiumkode'] ?? '');

    if ($klassekode === '' || $klassenavn === '' || $studiumkode === '') {
        $err = "Vennligst fyll ut alle felt.";
    } else {
        // 1) Forhåndssjekk – om klassekoden finnes
        $sjekk = $conn->prepare("SELECT 1 FROM klasse WHERE klassekode = ?");
        $sjekk->bind_param("s", $klassekode);
        $sjekk->execute();
        $sjekk->store_result();

        if ($sjekk->num_rows > 0) {
            $err = "Klassekoden '{$klassekode}' finnes allerede. Prøv en annen kode.";
        } else {
            // 2) Sikker INSERT med prepared statement
            $stmt = $conn->prepare("INSERT INTO klasse (klassekode, klassenavn, studiumkode) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $klassekode, $klassenavn, $studiumkode);

            try {
                if ($stmt->execute()) {
                    $ok = "Klassen '{$klassekode}' ble registrert!";
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
    body {font-family: system-ui, Segoe UI, Roboto, Arial, sans-serif; margin: 2rem;}
    input, button {padding: .6rem; border-radius: 8px; border: 1px solid #ccc; min-width: 320px;}
    .row {margin: .6rem 0;}
    .msg {padding: .7rem; border-radius: 8px; margin: .7rem 0; max-width: 520px;}
    .ok {background: #e8f7ee; border: 1px solid #9cd7b4;}
    .err {background: #fdeaea; border: 1px solid #f2a3a3;}
    a {color: #1a1a1a;}
  </style>
</head>
<body>
  <p><a href="index.php">← Tilbake</a></p>
  <h1>Registrer klasse</h1>

  <?php if ($ok): ?><div class="msg ok"><?= htmlspecialchars($ok) ?></div><?php endif; ?>
  <?php if ($err): ?><div class="msg err"><?= htmlspecialchars($err) ?></div><?php endif; ?>

  <form method="post">
    <div class="row">
      <label>Klassekode:<br>
        <input type="text" name="klassekode" maxlength="5" required>
      </label>
    </div>
    <div class="row">
      <label>Klassenavn:<br>
        <input type="text" name="klassenavn" maxlength="50" required>
      </label>
    </div>
    <div class="row">
      <label>Studiumkode:<br>
        <input type="text" name="studiumkode" maxlength="50" required>
      </label>
    </div>
    <div class="row">
      <button type="submit">Registrer</button>
    </div>
  </form>
</body>
</html>
