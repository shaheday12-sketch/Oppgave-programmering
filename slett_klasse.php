<?php require_once(__DIR__ . '/db.php');
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
>>>>>>> 622d0cd9e7c88719d65b331815060f96c70c3592
<!doctype html>
<html lang="no">
<head>
<meta charset="utf-8"><title>Slett klasse</title>
<style>
  body{font-family:system-ui,Arial;margin:0;background:#f5f6fa;color:#222;padding:30px}
  .form{max-width:420px;margin:auto;background:#fff;border:1px solid #e6e8ec;border-radius:10px;padding:20px 24px;box-shadow:0 2px 5px rgba(0,0,0,.06)}
  h2{margin:0 0 14px;text-align:center}
  label{display:block;font-weight:600;margin-top:10px}
  select{width:100%;padding:10px;border:1px solid #ccc;border-radius:8px;margin-top:6px;font:inherit}
  button{width:100%;margin-top:14px;background:#111;color:#fff;border:0;border-radius:8px;padding:10px;font:inherit;cursor:pointer}
  .msg{margin:10px 0;padding:8px;border-radius:6px;text-align:center}
  .ok{background:#e8f5e9;color:#2e7d32}
  .warn{background:#fff3cd;color:#856404}
  .err{background:#fdecea;color:#c62828}
  p.link{text-align:center;margin-top:10px}
  a{color:#2563eb;text-decoration:none}
</style>
</head>
<body>
<div class="form">
  <h2>Slett klasse</h2>
  <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $kode = $_POST["klassekode"] ?? "";
      if ($kode === "") {
        echo "<div class='msg err'>Velg en klasse.</div>";
      } else {
        // ikke slett hvis klassen har studenter
        $c = $conn->prepare("SELECT COUNT(*) FROM student WHERE klassekode=?");
        $c->bind_param("s", $kode); $c->execute(); $c->bind_result($ant); $c->fetch(); $c->close();
        if ($ant > 0) {
          echo "<div class='msg warn'>Kan ikke slette (".$ant." student(er) i klassen).</div>";
        } else {
          $d = $conn->prepare("DELETE FROM klasse WHERE klassekode=?");
          $d->bind_param("s", $kode); $d->execute();
          echo ($d->affected_rows > 0)
            ? "<div class='msg ok'>Klassen er slettet.</div>"
            : "<div class='msg warn'>Fant ingen slik klasse.</div>";
        }
      }
    }
  ?>
  <form method="post" onsubmit="return confirm('Slette valgt klasse?')">
    <label for="klassekode">Velg klasse</label>
    <select id="klassekode" name="klassekode" required>
      <option value="">Velg klasse</option>
      <?php
        $r = $conn->query("SELECT klassekode, klassenavn FROM klasse ORDER BY klassekode");
        while ($x = $r->fetch_assoc()) {
          $k = htmlspecialchars($x['klassekode']);
          $n = htmlspecialchars($x['klassenavn']);
          echo "<option value=\"$k\">$k – $n</option>";
        }
      ?>
    </select>
    <button>Slett</button>
  </form>
  <p class="link"><a href="index.php">← Tilbake</a></p>
</div>
</body>
</html>