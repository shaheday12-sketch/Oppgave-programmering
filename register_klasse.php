<?php
require_once __DIR__ . '/db.php';

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
<html lang="no"><head><meta charset="utf-8"><title>Registrer klasse</title></head>
<body>
<h1>Registrer klasse</h1>
<?php if ($msg): ?><p style="color:green"><?= htmlspecialchars($msg) ?></p><?php endif; ?>
<?php if ($err): ?><p style="color:red"><?= htmlspecialchars($err) ?></p><?php endif; ?>
<form method="post">
  <label>Klassekode (max 5): <input name="klassekode" maxlength="5" required></label><br>
  <label>Klassenavn: <input name="klassenavn" required></label><br>
  <label>Studiumkode: <input name="studiumkode" required></label><br>
  <button type="submit">Lagre</button>
</form>
<p><a href="index.php">Tilbake</a></p>
</body></html>