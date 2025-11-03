<?php
require_once _DIR_ . '/db.php';

$ok = $err = null;

// til listeboks
$sql = "SELECT s.brukernavn, s.fornavn, s.etternavn FROM student s ORDER BY s.brukernavn";
$res = $conn->query($sql);
$studenter = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') { h
    $bn = $_POST['brukernavn'] ?? '';
    if ($bn === '') {
        $err = "Velg en student å slette.";
    } else {
        $stmt = $conn->prepare("DELETE FROM student WHERE brukernavn = ?");
        $stmt->bind_param("s", $bn);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) $ok = "Student slettet.";
            else $err = "Fant ikke studenten.";
        } else {
            $err = "Ukjent feil under sletting.";
        }
        $stmt->close();
    }
    // Oppdater listen etter sletting
    $res = $conn->query($sql);
    $studenter = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
}
?>
<!doctype html>
<html lang="no">
<head>
  <meta charset="utf-8">
  <title>Slett student</title>
  <style>
    body{font-family:system-ui,Segoe UI,Roboto,Arial,sans-serif;margin:2rem;}
    select,button{padding:.6rem;border-radius:8px;border:1px solid #ccc;min-width:360px}
    .row{margin:.6rem 0}
    .msg{padding:.7rem;border-radius:8px;margin:.7rem 0;max-width:520px}
    .ok{background:#e8f7ee;border:1px solid #9cd7b4}
    .err{background:#fdeaea;border:1px solid #f2a3a3}
    a{color:#1a1a1a}
  </style>
</head>
<body>
  <p><a href="index.php">← Tilbake</a></p>
  <h1>Slett student</h1>

  <?php if ($ok): ?><div class="msg ok"><?= htmlspecialchars($ok) ?></div><?php endif; ?>
  <?php if ($err): ?><div class="msg err"><?= htmlspecialchars($err) ?></div><?php endif; ?>

  <form method="post" onsubmit="return confirm('Slette valgt student?')">
    <div class="row">
      <select name="brukernavn" required>
        <option value="">— Velg student —</option>
        <?php foreach ($studenter as $s): ?>
          <option value="<?= htmlspecialchars($s['brukernavn']) ?>">
            <?= htmlspecialchars($s['brukernavn'].' – '.$s['fornavn'].' '.$s['etternavn']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="row"><button type="submit">Slett</button></div>
  </form>
</body>
</html>