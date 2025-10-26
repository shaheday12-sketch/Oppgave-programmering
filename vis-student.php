<?php
include 'db_connect.php';

$sql = "SELECT * FROM student";
$result = $conn->query($sql);

echo "<h2>Alle studenter</h2>";
echo "<table border='1'>
<tr>
<th>Brukernavn</th>
<th>Fornavn</th>
<th>Etternavn</th>
<th>Klassekode</th>
</tr>";

while($row = $result->fetch_assoc()) {
    echo "<tr>
    <td>{$row['brukernavn']}</td>
    <td>{$row['fornavn']}</td>
    <td>{$row['etternavn']}</td>
    <td>{$row['klassekode']}</td>
    </tr>";
}

echo "</table>";

$conn->close();
?>
