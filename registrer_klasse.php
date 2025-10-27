<?php
// Inkluder tilkoblingen – endre sti hvis dp.php ligger et annet sted
require_once __DIR__ . '/dp.php';

$message = '';
$is_ok    = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Hent verdier (enkelt og tydelig)
    $kode    = trim($_POST['klassekode']  ?? '');
    $navn    = trim($_POST['klassenavn']  ?? '');
    $studium = trim($_POST['studiumkode'] ?? '');

    // Enkelt pålitelig sjekk
    if ($kode === '' || $navn === '' || $studium === '') {
        $message = 'Vennligst fyll ut alle feltene.';
    } else {
        try {
            // Enkelt, men riktig: prepared statement
            $stmt = $conn->prepare("INSERT INTO klasse (kode, navn, studium) VALUES (?, ?, ?)");
            $stmt->bind_param('sss', $kode, $navn, $studium);
            $stmt->execute();

            $is_ok  = true;
            $message = 'Klassen ble registrert ✔';

            // Tøm feltene etter suksess
            $kode = $navn = $studium = '';
        } catch (mysqli_sql_exception $e) {
            // Duplikatnøkkel?
            if ((int)$conn->errno === 1062) {
                $message = 'Denne klassekoden finnes allerede.';
            } else {
                $message = 'Noe gikk galt under lagring. Prøv igjen.';
            }
        }
    }
}
?>
<!doctype html>
<html lang="no">
<head>
<meta charset="utf-8">
<title>Registrer ny klasse</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
  body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; padding:24px; }
  h2 { margin-top:0; }
  form { max-width:460px; }
  label { display:block; margin:12px 0 6px; font-weight:600; }
  input[type="text"] { width:100%; padding:10px; border:1px solid #c9ced6; border-radius:6px; }
  input[type="submit"] { margin-top:16px; padding:10px 14px; border:0; border-radius:6px; cursor:pointer; background:#1f7a1f; color:#fff; font-weight:600; }
  .alert { padding:12px; border-radius:6px; margin-bottom:16px; border:1px solid transparent; }
  .alert.success { background:#eefaf0; border-color:#bfe7c6; color:#1c5e21; }
  .alert.error   { background:#fdeeee; border-color:#f3b5b5; color:#7a1b1b; }
</style>
</head>
<body>

<h2>Registrer ny klasse</h2>

<?php if ($message): ?>
  <div class="alert <?php echo $is_ok ? 'success' : 'error'; ?>">
    <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
  </div>
<?php endif; ?>

<form method="post" novalidate>
  <label for="klassekode">Klassekode:</label>
  <input id="klassekode" type="text" name="klassekode" required
         value="<?php echo isset($kode) ? htmlspecialchars($kode, ENT_QUOTES, 'UTF-8') : ''; ?>">

  <label for="klassenavn">Klassenavn:</label>
  <input id="klassenavn" type="text" name="klassenavn" required
         value="<?php echo isset($navn) ? htmlspecialchars($navn, ENT_QUOTES, 'UTF-8') : ''; ?>">

  <label for="studiumkode">Studiumkode:</label>
  <input id="studiumkode" type="text" name="studiumkode" required
         value="<?php echo isset($studium) ? htmlspecialchars($studium, ENT_QUOTES, 'UTF-8') : ''; ?>">

  <input type="submit" value="Lagre">
</form>

</body>
</html>
