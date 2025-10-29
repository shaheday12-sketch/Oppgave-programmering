<?php
require_once __DIR__ . '/db.php'; // må ligge i samme mappe

// Hent klasser fra databasen
try {
    $klasser = $pdo->query("SELECT klassekode, klassenavn FROM klasse ORDER BY klassekode")->fetchAll();
} catch (PDOException $e) {
    die("Kunne ikke hente klasser: " . htmlspecialchars($e->getMessage()));
}

// Behandle innsending av skjema
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brukernavn = trim($_POST['brukernavn'] ?? '');
    $fornavn = trim($_POST['fornavn'] ?? '');
    $etternavn = trim($_POST['etternavn'] ?? '');
    $klassekode = $_POST['klassekode'] ?? '';

    $errors = [];

    if (!preg_match('/^[a-z]{1,7}$/', $brukernavn)) {
        $errors[] = "Brukernavn må bestå av 1–7 små bokstaver (a–z).";
    }
    if ($fornavn === '' || $etternavn === '') {
        $errors[] = "Fornavn og etternavn må fylles ut.";
    }
    if ($klassekode === '') {
        $errors[] = "Du må velge en klasse.";
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO student (brukernavn, fornavn, etternavn, klassekode)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$brukernavn, $fornavn, $etternavn, $klassekode]);
            $success = "Studenten '$fornavn $etternavn' ble registrert!";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $errors[] = "Brukernavnet finnes allerede eller klassen eksisterer ikke.";
            } else {
                $errors[] = "Databasefeil: " . htmlspecialchars($e->getMessage());
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <title>Registrer student</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #f8f8f8; padding: 2rem; }
        .container { background: white; padding: 2rem; border-radius: 12px; max-width: 500px; margin: auto; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        h1 { text-align: center; }
        label { display: block; margin-top: 1rem; font-weight: bold; }
        input, select, button { width: 100%; padding: .6rem; border: 1px solid #ccc; border-radius: 6px; margin-top: .3rem; }
        button { background: #007bff; color: white; font-weight: bold; cursor: pointer; margin-top: 1.2rem; }
        button:hover { background: #0056b3; }
        .success { background: #e6ffed; border: 1px solid #8fd19e; padding: .6rem; border-radius: 6px; color: #155724; }
        .error { background: #ffe6e6; border: 1px solid #f5a3a3; padding: .6rem; border-radius: 6px; color: #721c24; }
        a { text-decoration: none; display: inline-block; margin-bottom: 1rem; color: #333; }
    </style>
</head>
<body>
<div class="container">
    <a href="index.php">← Tilbake</a>
    <h1>Registrer ny student</h1>

    <?php
    if (!empty($success)) echo "<p class='success'>$success</p>";
    if (!empty($errors)) foreach ($errors as $e) echo "<p class='error'>$e</p>";
    ?>

    <form method="post">
        <label for="brukernavn">Brukernavn (1–7 små bokstaver):</label>
        <input type="text" name="brukernavn" id="brukernavn" maxlength="7" pattern="[a-z]{1,7}" required>

        <label for="fornavn">Fornavn:</label>
        <input type="text" name="fornavn" id="fornavn" required>

        <label for="etternavn">Etternavn:</label>
        <input type="text" name="etternavn" id="etternavn" required>

        <label for="klassekode">Klasse:</label>
        <select name="klassekode" id="klassekode" required>
            <option value="">-- Velg klasse --</option>
            <?php foreach ($klasser as $k): ?>
                <option value="<?= htmlspecialchars($k['klassekode']) ?>">
                    <?= htmlspecialchars($k['klassekode']) ?> - <?= htmlspecialchars($k['klassenavn']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Registrer student</button>
    </form>
</div>
</body>
</html>
