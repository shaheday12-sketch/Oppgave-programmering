<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <title>Slett klasse</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .error   { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin: 10px 0; }
        .btn-delete { background: #dc3545; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; }
        .btn-delete:hover { background: #c82333; }
        select, button { margin: 10px 0; padding: 8px; font-size: 16px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Slett klasse</h1>
        <a href="index.php">Tilbake</a>

        <?php
        // KOBLE TIL DATABASE – db.php MÅ LIGGE I SAMME MAPPE!
        require_once 'db.php';

        // Hent alle klasser
        $sql = "SELECT klassekode, klassenavn FROM klasse ORDER BY klassekode";
        $result = $conn->query($sql);
        $klasser = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $klasser[] = $row;
            }
        }

        // SLETT KLASSE
        $melding = '';
        if ($_POST && isset($_POST['slett'])) {
            $klassekode = trim($_POST['klassekode']);

            if ($klassekode !== '') {
                // Sjekk om klassen har studenter
                $check = $conn->prepare("SELECT 1 FROM student WHERE klassekode = ?");
                $check->bind_param("s", $klassekode);
                $check->execute();
                $check->store_result();

                if ($check->num_rows > 0) {
                    $melding = "<p class='error'>Kan ikke slette klasse – den har studenter!</p>";
                } else {
                    $stmt = $conn->prepare("DELETE FROM klasse WHERE klassekode = ?");
                    $stmt->bind_param("s", $klassekode);

                    if ($stmt->execute()) {
                        $melding = "<p class='success'>Klassen ble slettet!</p>";
                        header("Location: slett_klasse.php");
                        exit;
                    } else {
                        $melding = "<p class='error'>Kunne ikke slette klassen.</p>";
                    }
                    $stmt->close();
                }
                $check->close();
            } else {
                $melding = "<p class='error'>Velg en klasse.</p>";
            }
        }
        ?>

        <?php if ($melding) echo $melding; ?>

        <form method="post" onsubmit="return confirm('Er du HELT sikker på at du vil slette denne klassen?');">
            <label><strong>Velg klasse å slette:</strong></label><br>
            <select name="klassekode" required>
                <option value="">-- Velg klasse --</option>
                <?php foreach ($klasser as $k): ?>
                    <option value="<?= htmlspecialchars($k['klassekode']) ?>">
                        <?= htmlspecialchars($k['klassekode']) ?> - <?= htmlspecialchars($k['klassenavn']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br>
            <button type="submit" name="slett" class="btn-delete">Slett klasse</button>
        </form>

        <?php if (empty($klasser)): ?>
            <p><em>Ingen klasser å vise.</em></p>
        <?php endif; ?>
    </div>
</body>
</html>