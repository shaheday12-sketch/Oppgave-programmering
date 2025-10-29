<?php
// Koble til databasen – db.php ligger i SAMME MAPPE
require_once 'db.php';
$ok = $err = null;

// Hent klasser fra databasen
$klasseresultat = $conn->query("SELECT klassekode, klassenavn FROM klasse ORDER BY klassekode");
$klasser = $klasseresultat->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brukernavn = trim($_POST['brukernavn'] ?? '');
    $fornavn = trim($_POST['fornavn'] ?? '');
    $etternavn = trim($_POST['etternavn'] ?? '');
    $klassekode = trim($_POST['klassekode'] ?? '');

    if ($brukernavn === '' || $fornavn === '' || $etternavn === '' || $klassekode === '') {
        $err = "Vennligst fyll ut alle felt.";
    } elseif (!preg_match('/^[a-z]{1,7}$/', $brukernavn)) {
        $err = "Brukernavn må bestå av 1–7 små bokstaver (a–z).";
    } else {
        // Sjekk om brukernavnet finnes
        $sjekk = $conn->prepare("SELECT 1 FROM student WHERE brukernavn = ?");
        $sjekk->bind_param("s", $brukernavn);
        $sjekk->execute();
        $sjekk->store_result();

        if ($sjekk->num_rows > 0) {
            $err = "Brukernavnet '{$brukernavn}' finnes allerede.";
        } else {
            // Legg til ny student
            $stmt = $conn->prepare("INSERT INTO student (brukernavn, fornavn, etternavn, klassekode) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $brukernavn, $fornavn, $etternavn, $klassekode);

            try {
                if ($stmt->execute()) {
                    $ok = "Studenten '{$fornavn} {$etternavn}' ble registrert!";
                } else {
                    $err = "Noe gikk galt under registrering. Prøv igjen.";
                }
            } catch (mysqli_sql_exception $e) {
                $err = "Teknisk feil: " . htmlspecialchars($e->getMessage());
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
  <title>Registrer student</title>
  <style>
    body {font-family: system-ui, Segoe UI, Roboto, Arial, sans-serif; margin: 2rem;}
    input, select, button {padding: .6rem; border-radius: 8px; border: 1px solid #ccc; min-width: 320px;}
    .row {margin: .6rem 0;}
    .msg {padding: .7rem; border-radius: 8px; margin: .7rem 0; max-width: 520px;}
    .ok {background: #e8f7ee; border: 1px solid #9cd7b4;}
    .err {background: #fdeaea; border: 1px solid #f2a3a3;}
    a {color: #1a1a1a;}
  </style>
</head>
<body>
  <a href="index.php">← Tilbake</a>
  <h1>Registrer ny student</h1>

  <?php if ($ok): ?><div class="msg ok"><?= htmlspecialchars($ok) ?></div><?php endif; ?>
  <?php if ($err): ?><div class="msg err"><?= htmlspecialchars($err) ?></div><?php endif; ?>

  <form method="post">
    <div class="row">
      <label>Brukernavn (1–7 små bokstaver):</label><br>
      <input type="text" name="brukernavn" maxlength="7" pattern="[a-z]{1,7}" required>
    </div>
    <div class="row">
      <label>Fornavn:</label><br>
      <input type="text" name="fornavn" required>
    </div>
    <div class="row">
      <label>Etternavn:</label><br>
      <input type="text" name="etternavn" required>
    </div>
    <div class="row">
      <label>Klasse:</label><br>
      <select name="klassekode" required>
        <option value="">-- Velg klasse --</option>
        <?php foreach ($klasser as $k): ?>
          <option value="<?= htmlspecialchars($k['klassekode']) ?>">
            <?= htmlspecialchars($k['klassekode']) ?> - <?= htmlspecialchars($k['klassenavn']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="row">
      <button type="submit">Registrer student</button>
    </div>
  </form>
</body>
</html>