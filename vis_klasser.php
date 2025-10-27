<?php
require_once __DIR__ . '/db.php';
$res = $conn->query("SELECT klassekode, klassenavn, studiumkode FROM klasse ORDER BY klassekode");
?>
<!doctype html>
<html lang="no"><head><meta charset="utf-8"><title>Alle klasser</title><link rel="stylesheet" href="style.css"></head>
<body>
<h1>Alle klasser</h1>
<table>
  <tr><th>Klassekode</th><th>Klassenavn</th><th>Studiumkode</th></tr>
  <?php while ($r = $res->fetch_assoc()): ?>
  <tr>
    <td><?= htmlspecialchars($r['klassekode']) ?></td>
    <td><?= htmlspecialchars($r['klassenavn']) ?></td>
    <td><?= htmlspecialchars($r['studiumkode']) ?></td>
  </tr>
  <?php endwhile; ?>
</table>
<p><a href="index.php">Tilbake</a></p>
</body></html>