<?php
// Inkluder databasekoblingen
include __DIR__ . '/db.php'; // Juster stien hvis db.php ligger i en undermappe

// Hent alle klasser
try {
    $klasser = $pdo->query("SELECT klassekode, klassenavn FROM klasse ORDER BY klassekode")->fetchAll();
} catch (PDOException $e) {
    die("Kunne ikke hente klasser: " . $e->getMessage());
}

// Håndter skjema-innsending
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brukernavn = trim($_POST['brukernavn']);
    $fornavn = trim($_POST['fornavn']);
    $etternavn = trim($_POST['etternavn']);
    $klassekode = $_POST['klassekode'];

    $errors = [];

    // Validering
    if (!preg_match('/^[a-z]{1,7}$/', $brukernavn)) {
        $errors[] = "Brukernavn må være 1–7 små bokstaver.";
    }
    if (empty($fornavn) || empty($etternavn)) {
        $errors[] = "Fornavn og etternavn er påkrevd.";
    }
    if (empty($klassekode)) {
        $errors[] = "Du må velge en klasse.";
    }

    // Sett inn i databasen hvis ingen feil
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO student (brukernavn, fornavn, etternavn, klassekode) VALUES (?, ?, ?, ?)");
            $stmt->execute([$brukernavn, $fornavn, $etternavn, $klassekode]);
            $success = "Student registrert!";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $errors[] = "Brukernavn finnes allerede eller klasse finnes ikke.";
            } else {
                $errors[] = "Feil: " . $e->getMessage();
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
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Registrer ny student</h1>
        <a href="../index.php">← Tilbake</a>

        <?php
        // Vis meldinger
        if (!empty($success)) echo "<p class='success'>$success</p>";
        if (!empty($errors)) {
            foreach ($errors as $e) echo "<p class='error'>$e</p>";
        }
        ?>

        <form method="post">
            <label>Brukernavn (max 7 små bokstaver):</label>
            <input type="text" name="brukernavn" maxlength="7" required pattern="[a-z]{1,7}">

            <label>Fornavn:</label>
            <input type="text" name="fornavn" required>

            <label>Etternavn:</label>
            <input type="text" name="etternavn" required>

            <label>Klasse:</label>
            <select name="klassekode" required>
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