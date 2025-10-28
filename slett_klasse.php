<?php
// Koble til databasen
require 'db.php'; // Pass på at db.php ligger i samme mappe som denne filen

?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <title>Slett student</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Slett student</h1>
        <a href="index.php">← Tilbake</a>

        <?php
        // Hent alle studenter fra databasen
        try {
            $studenter = $pdo->query("
                SELECT s.brukernavn, s.fornavn, s.etternavn, k.klassenavn
                FROM student s
                JOIN klasse k ON s.klassekode = k.klassekode
                ORDER BY s.etternavn
            ")->fetchAll();
        } catch (PDOException $e) {
            die("<p class='error'>Feil ved henting av studenter: " . htmlspecialchars($e->getMessage()) . "</p>");
        }

        // Når skjema sendes inn
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $brukernavn = trim($_POST['brukernavn'] ?? '');

            if (empty($brukernavn)) {
                echo "<p class='error'>Du må velge en student å slette.</p>";
            } else {
                try {
                    $stmt = $pdo->prepare("DELETE FROM student WHERE brukernavn = ?");
                    $stmt->execute([$brukernavn]);

                    if ($stmt->rowCount() > 0) {
                        echo "<p class='success'>Student ble slettet!</p>";
                    } else {
                        echo "<p class='error'>Ingen student med dette brukernavnet ble funnet.</p>";
                    }
                } catch (PDOException $e) {
                    echo "<p class='error'>Feil ved sletting: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
            }
        }
        ?>

        <form method="post">
            <label>Velg student å slette:</label>
            <select name="brukernavn" required>
                <option value="">-- Velg student --</option>
                <?php foreach ($studenter as $s): ?>
                    <option value="<?= htmlspecialchars($s['brukernavn']) ?>">
                        <?= htmlspecialchars($s['brukernavn']) ?> - 
                        <?= htmlspecialchars($s['fornavn']) ?> <?= htmlspecialchars($s['etternavn']) ?> 
                        (<?= htmlspecialchars($s['klassenavn']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" onclick="return confirm('Er du sikker på at du vil slette denne studenten?')">Slett</button>
        </form>
    </div>
</body>
</html>