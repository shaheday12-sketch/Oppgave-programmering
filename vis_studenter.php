<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <title>Vis studenter</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Alle studenter</h1>
        <a href="index.php">Tilbake</a>

        <?php
        // KOBLE TIL DATABASE – db.php MÅ ligge i SAMME MAPPE
        require_once 'db.php';

        // SQL-spørring: Hent studenter + klassenavn
        $sql = "
            SELECT s.brukernavn, s.fornavn, s.etternavn, k.klassenavn 
            FROM student s 
            JOIN klasse k ON s.klassekode = k.klassekode 
            ORDER BY s.etternavn, s.fornavn
        ";

        $result = $conn->query($sql);
        ?>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Brukernavn</th>
                        <th>Navn</th>
                        <th>Klasse</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($s = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($s['brukernavn']) ?></td>
                            <td><?= htmlspecialchars($s['fornavn'] . ' ' . $s['etternavn']) ?></td>
                            <td><?= htmlspecialchars($s['klassenavn']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p><em>Ingen studenter registrert ennå.</em></p>
        <?php endif; ?>

        <?php 
        // Frigjør minne (valgfritt, men ryddig)
        if ($result) $result->free();
        ?>
    </div>
</body>
</html>