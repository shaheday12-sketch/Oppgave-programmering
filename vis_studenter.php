<?php require '../db.php'; ?>
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
        <a href="../index.php">← Tilbake</a>

        <?php
        $stmt = $pdo->query("
            SELECT s.*, k.klassenavn 
            FROM student s 
            JOIN klasse k ON s.klassekode = k.klassekode 
            ORDER BY s.etternavn, s.fornavn
        ");
        $studenter = $stmt->fetchAll();
        ?>

        <?php if (count($studenter) > 0): ?>
            <table>
                <tr>
                    <th>Brukernavn</th>
                    <th>Navn</th>
                    <th>Klasse</th>
                </tr>
                <?php foreach ($studenter as $s): ?>
                    <tr>
                        <td><?= htmlspecialchars($s['brukernavn']) ?></td>
                        <td><?= htmlspecialchars($s['fornavn'] . ' ' . $s['etternavn']) ?></td>
                        <td><?= htmlspecialchars($s['klassenavn']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Ingen studenter registrert.</p>
        <?php endif; ?>
    </div>
</body>
</html>