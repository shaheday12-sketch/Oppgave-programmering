<?php
// registrer_klasse.php

// Inkluder db.php - pass på at stien er riktig
include __DIR__ . "/db.php"; // Hvis db.php ligger i samme mappe

?>
<!doctype html>
<html lang="no">
<head>
<meta charset="utf-8">
<title>Registrer klasse</title>
<style>
  body{font-family:system-ui,Arial;margin:0;background:#f5f6fa;color:#222;padding:30px}
  .form{max-width:420px;margin:auto;background:#fff;border:1px solid #e6e8ec;border-radius:10px;padding:20px 24px;box-shadow:0 2px 5px rgba(0,0,0,.06)}
  h2{margin:0 0 14px;text-align:center}
  label{display:block;font-weight:600;margin-top:10px}
  input{width:100%;padding:10px;border:1px solid #ccc;border-radius:8px;margin-top:6px;font:inherit}
  input:focus{outline:none;border-color:#2563eb}
  button{width:100%;margin-top:14px;background:#2563eb;color:#fff;border:0;border-radius:8px;padding:10px;font:inherit;cursor:pointer}
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
  <h2>Registrer klasse</h2>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $kode = trim($_POST["klassekode"] ?? "");
    $navn = trim($_POST["klassenavn"] ?? "");
    $stud = trim($_POST["studiumkode"] ?? "");

    if ($kode === "" || $navn === "" || $stud === "") {
        echo "<div class='msg err'>Fyll ut alle felt.</div>";
    } else {
        // Sjekk om klassekode allerede finnes
        $s = $conn->prepare("SELECT 1 FROM klasse WHERE klassekode=?");
        $s->bind_param("s", $kode);
        $s->execute();
        $s->store_result();

        if ($s->num_rows > 0) {
            echo "<div class='msg warn'>Klassen finnes allerede.</div>";
        } else {
            // Sett inn ny klasse
            $i = $conn->prepare("INSERT INTO klasse(klassekode, klassenavn, studiumkode) VALUES (?, ?, ?)");
            $i->bind_param("sss", $kode, $navn, $stud);
            if($i->execute()) {
                echo "<div class='msg ok'>Klassen ble registrert.</div>";
            } else {
                echo "<div class='msg err'>Noe gikk galt: " . $conn->error . "</div>";
            }
            $i->close();
        }
        $s->close();
    }
}
?>

<form method="post" autocomplete="off">
    <label for="klassekode">Klassekode</label>
    <input id="klassekode" name="klassekode" required>

    <label for="klassenavn">Klassenavn</label>
    <input id="klassenavn" name="klassenavn" required>

    <label for="studiumkode">Studiumkode</label>
    <input id="studiumkode" name="studiumkode" required>

    <button>Registrer</button>
</form>

<p class="link"><a href="index.php">← Tilbake</a></p>
</div>
</body>
</html>
