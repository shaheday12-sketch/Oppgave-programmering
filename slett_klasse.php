<?php
require_once _DIR_ . '/db.php';

$msg = null; $err = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $klassekode = $_POST['klassekode'] ?? '';
    if ($klassekode !== '') {
        $stmt = $conn->prepare("DELETE FROM klasse WHERE klassekode = ?");
        $stmt->bind_param("s", $klassekode);
        try {
            $stmt->execute();
            $msg = "Klasse slettet.";
        } catch (mysqli_sql_exception $e) {
            $err = "Kunne ikke slette (har kanskje studenter knyttet?): " . htmlspecialchars($e->getMessage());
        }
        $stmt->close();
    }
}
$klasser = $conn->query("SELECT klassekode, klassenavn FROM klasse ORDER BY klassekode");
?>
<!doctype html>
<html lang="no"><head><meta charset="utf-8"><title>Slett klasse</title><link rel="stylesheet" href="style.css"></head>
<body>
<h1>Slett klasse</h1>
<?php if ($msg): ?><p class="ok"><?= htmlspecialchars($msg) ?></p><?php endif; ?>
<?php if ($err): ?><p class="err"><?= htmlspecialchars($err) ?></p><?php endif; ?>
<form method="post" class="form">
  <label>Velg klasse:
    <select name="klassekode" required>
      <option value="">-- Velg --</option>
      <?php while ($k = $klasser->fetch_assoc()): ?>
        <option value="<?= htmlspecialchars($k['klassekode']) ?>">
          <?= htmlspecialchars($k['klassekode']) ?> – <?= htmlspecialchars($k['klassenavn']) ?>
        </option>
      <?php endwhile; ?>
    </select>
  </label>
  <button type="submit">Slett</button>
</form>
<p><a href="index.php">Tilbake</a></p>
</body></html>