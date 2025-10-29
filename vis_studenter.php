<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <title>Vis studenter</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <h1>Alle studenter</h1>
        <a href="../index.php">Tilbake</a>

        <?php
        // KOBLE TIL DATABASE – BRUK SAMME SOM I REGISTRER-STUDENT
        require_once '../db.php';  // ← JUSTER STIEN OM NØDVENDIG

        // Hent alle studenter med klassenavn
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
                <tr>
                    <th>Brukernavn</th>
                    <th>Navn</th>
                    <th>Klasse</th>
                </tr>
                <?php while ($s = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($s['brukernavn']) ?></td>
                        <td><?= htmlspecialchars($s['fornavn'] . ' ' . $s['etternavn']) ?></td>
                        <td><?= htmlspecialchars($s['klassenavn']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>Ingen studenter registrert.</p>
        <?php endif; ?>

        <?php 
        // Lukk tilkobling (valgfritt, men ryddig)
        if ($result) $result->free();
        ?>
    </div>
</body>
</html>