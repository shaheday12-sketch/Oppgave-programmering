<?php require '../db.php'; ?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <title>Slett student</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Slett student</h1>
        <a href="../index.php">← Tilbake</a>

        <?php
        $studenter = $pdo->query("
            SELECT s.*, k.klassenavn 
            FROM student s 
            JOIN klasse k ON s.klassekode = k.klassekode 
            ORDER BY s.etternavn
        ")->fetchAll();

        if ($_POST && isset($_POST['slett'])) {
            $brukernavn = $_POST['brukernavn'];
            try {
                $stmt = $pdo->prepare("DELETE FROM student WHERE brukernavn = ?");
                $stmt->execute([$brukernavn]);
                echo "<p class='success'>Student slettet!</p>";
            } catch (Exception $e) {
                echo "<p class='error'>Kunne ikke slette: " . $e->getMessage() . "</p>";
            }
        }
        ?>

        <form method="post">
            <label>Velg student å slette:</label>
            <select name="brukernavn" required>
                <option value="">-- Velg student --</option>
                <?php foreach ($studenter as $s): ?>
                    <option value="<?= $s['brukernavn'] ?>">
                        <?= $s['brukernavn'] ?> - <?= $s['fornavn'] ?> <?= $s['etternavn'] ?> (<?= $s['klassenavn'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="slett" onclick="return confirm('Er du sikker?')">Slett</button>
        </form>
    </div>
</body>
</html>