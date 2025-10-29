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
        <a href="index.php">Tilbake</a>

        <?php
        // KOBLE TIL DATABASE – db.php i SAMME MAPPE
        require_once 'db.php';

        // Hent alle studenter
        $sql = "
            SELECT s.brukernavn, s.fornavn, s.etternavn, k.klassenavn 
            FROM student s 
            JOIN klasse k ON s.klassekode = k.klassekode 
            ORDER BY s.etternavn, s.fornavn
        ";
        $result = $conn->query($sql);
        $studenter = [];
        while ($row = $result->fetch_assoc()) {
            $studenter[] = $row;
        }

        // SLETT STUDENT
        if ($_POST && isset($_POST['slett'])) {
            $brukernavn = trim($_POST['brukernavn']);

            if ($brukernavn !== '') {
                $stmt = $conn->prepare("DELETE FROM student WHERE brukernavn = ?");
                $stmt->bind_param("s", $brukernavn);

                if ($stmt->execute()) {
                    echo "<p class='success'>Studenten ble slettet!</p>";
                    // Oppdater listen
                    header("Location: slett_student.php");
                    exit;
                } else {
                    echo "<p class='error'>Kunne ikke slette studenten.</p>";
                }
                $stmt->close();
            }
        }
        ?>

        <form method="post" onsubmit="return confirm('Er du helt sikker på at du vil slette denne studenten?');">
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
            <button type="submit" name="slett" class="btn-delete">Slett student</button>
        </form>
    </div>
</body>
</html>
