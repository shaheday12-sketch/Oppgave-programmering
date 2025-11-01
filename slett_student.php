<html lang="no">
<head>
<meta charset="utf-8"><title>Slett stundet</title>
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
  <h2>Slett stundet</h2>
  <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $kode = $_POST["klassekode"] ?? "";
      if ($kode === "") {
        echo "<div class='msg err'>Velg en studnet.</div>";
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
            ? "<div class='msg ok'>studenten er slettet.</div>"
            : "<div class='msg warn'>Fant ingen slik studnet.</div>";
        }
      }
    }
  ?>
  <form method="post" onsubmit="return confirm('Slette valgt studnet?')">
    <label for="klassekode">Velg student </label>
    <select id="klassekode" name="klassekode" required>
      <option value="">Velg student</option>
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

vis klasser  
<?php require_once 'db.php'; ?>
<!doctype html>
<html lang="no">
<head>
  <meta charset="utf-8"><title>Alle studenter</title>
  <style>
    body{font-family:system-ui,Arial;margin:0;background:#f5f6fa;color:#222;padding:30px}
    .wrap{max-width:900px;margin:auto}
    .box{background:#fff;border:1px solid #e6e8ec;border-radius:12px;overflow:hidden;box-shadow:0 1px 2px rgba(0,0,0,.05)}
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px 12px;border-bottom:1px solid #e6e8ec;text-align:left}
    th{background:#f3f4f6}
    tr:nth-child(even) td{background:#fafafa}
    a{color:#2563eb;text-decoration:none}
    h2{margin:0 0 12px}
    .pad{padding:16px}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="pad"><h2>Registrerte klasser</h2></div>
    <div class="box">
      <table>
        <thead><tr><th>Klassekode</th><th>Klassenavn</th><th>Studiumkode</th></tr></thead>
        <tbody>
        <?php
          $res = $conn->query("SELECT klassekode, klassenavn, studiumkode FROM klasse ORDER BY klassekode");
          while ($r = $res->fetch_assoc()){
            echo "<tr>
                    <td>".htmlspecialchars($r['klassekode'])."</td>
                    <td>".htmlspecialchars($r['klassenavn'])."</td>
                    <td>".htmlspecialchars($r['studiumkode'])."</td>
                  </tr>";
          }
        ?>
        </tbody>
      </table>
    </div>
    <div class="pad"><a href="index.php">← Tilbake</a></div>
  </div>
</body>
</html>