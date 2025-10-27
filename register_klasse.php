<?php
require_once _DIR_ . '/db.php';

$msg = null; $err = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $klassekode = trim($_POST['klassekode'] ?? '');
    $klassenavn = trim($_POST['klassenavn'] ?? '');
    $studiumkode = trim($_POST['studiumkode'] ?? '');

    if ($klassekode === '' || $klassenavn === '' || $studiumkode === '') {
        $err = "Alle felter må fylles ut.";
    } elseif (mb_strlen($klassekode) > 5) {
        $err = "Klassekode kan maks være 5 tegn.";
    } else {
        $stmt = $conn->prepare("INSERT INTO klasse (klassekode, klassenavn, studiumkode) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $klassekode, $klassenavn, $studiumkode);
        try {
            $stmt->execute();
            $msg = "Klasse registrert.";
        } catch (mysqli_sql_exception $e) {
            $err = ($conn->errno == 1062) ? "Klassekoden finnes allerede." : "Feil ved lagring: " . htmlspecialchars($e->getMessage());
        }
        $stmt->close();
    }
}
?>
<!doctype html>
<html lang="no"><head><meta charset="utf-8"><title>Registrer klasse</title><link rel="stylesheet" href="style.css"></head>
<body>
<h1>Registrer klasse</h1>
<?php if ($msg): ?><p class="ok"><?= htmlspecialchars($msg) ?></p><?php endif; ?>
<?php if ($err): ?><p class="err"><?= htmlspecialchars($err) ?></p><?php endif; ?>
<form method="post" class="form">
  <label>Klassekode (max 5): <input name="klassekode" maxlength="5" required></label>
  <label>Klassenavn: <input name="klassenavn" required></label>
  <label>Studiumkode: <input name="studiumkode" required></label>
  <button type="submit">Lagre</button>
</form>
<p><a href="index.php">Tilbake</a></p>
</body></html>