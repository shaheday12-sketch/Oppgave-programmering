<?php
include 'db_connect.php';

// Hent alle studenter fra databasen
$sql = "SELECT * FROM student";
$result = $conn->query($sql);

echo "<h2>Alle studenter</h2>";

if ($result->num_rows > 0) {
    echo "<table border='1' cellpadding='5' cellspacing='0'>
    <tr>
        <th>Brukernavn</th>
        <th>Fornavn</th>
        <th>Etternavn</th>
        <th>Klassekode</th>
    </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['brukernavn']}</td>
            <td>{$row['fornavn']}</td>
            <td>{$row['etternavn']}</td>
            <td>{$row['klassekode']}</td>
        </tr>";
    }

    echo "</table>";
} else {
    echo "<p>Ingen studenter funnet i databasen.</p>";
}

$conn->close();
?>

