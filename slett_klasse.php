<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <title>Slett student</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .error   { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .btn-delete { background: #dc3545; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; }
        .btn-delete:hover { background: #c82333; }
        select, button { margin: 10px 0; padding: 8px; font-size: 16px; }
    </style>
</head>
<body require_once 'db.php';
    <div class="container">
        <h1>Slett student</h1>
        <a href="index.php">Tilbake</a>

        <?php
        // KOBLE TIL DATABASE – db.php MÅ LIGGE I SAMME MAPPE!
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
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $studenter[] = $row;
            }
        }

        // SLETT STUDENT
        $melding = '';
        if ($_POST && isset($_POST['slett'])) {
            $brukernavn = trim($_POST['brukernavn']);

            if ($brukernavn !== '') {
                $stmt = $conn->prepare("DELETE FROM student WHERE brukernavn = ?");
                $stmt->bind_param("s", $brukernavn);

                if ($stmt->execute()) {
                    $melding = "<p class='success'>Studenten ble slettet!</p>";
                    // Oppdater siden for å vise ny liste
                    header("Location: slett_student.php");
                    exit;
                } else {
                    $melding = "<p class='error'>Kunne ikke slette studenten.</p>";
                }
                $stmt->close();
            } else {
                $melding = "<p class='error'>Velg en student.</p>";
            }
        }
        ?>

        <?php if ($melding) echo $melding; ?>

        <form method="post" onsubmit="return confirm('Er du HELT sikker på at du vil slette denne studenten?');">
            <label><strong>Velg student å slette:</strong></label><br>
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
            <br>
            <button type="submit" name="slett" class="btn-delete">Slett student</button>
        </form>

        <?php if (empty($studenter)): ?>
            <p><em>Ingen studenter å vise.</em></p>
        <?php endif; ?>
    </div>
</body>
</html>