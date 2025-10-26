<?php
include 'db_connect.php'; // kobler til databasen

$sql = "SELECT * FROM klasse"; // henter alle rader fra tabellen 'klasse'
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <title>Vis alle klasser</title>
</head>
<body>
    <h2>Alle klasser</h2>

    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Klassekode</th>
            <th>Klassenavn</th>
            <th>Studiumkode</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            // viser rad for rad
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['klassekode']}</td>
                        <td>{$row['klassenavn']}</td>
                        <td>{$row['studiumkode']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Ingen klasser funnet</td></tr>";
        }

        $conn->close();
        ?>
    </table>

    <br>
    <a href="index.php">⬅ Tilbake til meny</a>
</body>
</html>
