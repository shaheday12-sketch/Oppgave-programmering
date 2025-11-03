<?php
// 1️⃣ Koble til databasen
$servername = "localhost";  // eller din server
$username = "brukernavn";   // ditt brukernavn
$password = "passord";      // ditt passord
$dbname = "databasenavn";   // din database

$conn = new mysqli($servername, $username, $password, $dbname);

// Sjekk tilkobling
if ($conn->connect_error) {
    die("Tilkoblingsfeil: " . $conn->connect_error);
}

// 2️⃣ Hent studentnummer fra skjema (POST)
$studentnr = isset($_POST['studentnr']) ? $_POST['studentnr'] : null;

if ($studentnr === null) {
    echo "Ingen studentnummer valgt!";
    exit;
}

// 3️⃣ Hent informasjon om studenten
$sql = "SELECT * FROM studenten WHERE studentnr = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $studentnr); // "i" = integer, bruk "s" hvis studentnr er tekst
$stmt->execute();
$resultat = $stmt->get_result();

if ($resultat->num_rows === 0) {
    echo "Student ikke funnet!";
    exit;
}

// 4️⃣ Vis studentinfo (trygt med htmlspecialchars)
$row = $resultat->fetch_assoc();
$navn = isset($row['navn']) ? htmlspecialchars($row['navn']) : '';
$studentnr_vis = isset($row['studentnr']) ? htmlspecialchars($row['studentnr']) : '';

echo "<p>Student: $navn (Studentnr: $studentnr_vis)</p>";

// 5️⃣ Slett studenten hvis ønsket
if (isset($_POST['slett'])) {
    $sql_delete = "DELETE FROM studenten WHERE studentnr = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $studentnr);
    if ($stmt_delete->execute()) {
        echo "<p>Student slettet!</p>";
    } else {
        echo "<p>Kunne ikke slette studenten: " . $conn->error . "</p>";
    }
}

// 6️⃣ Lukk tilkobling
$stmt->close();
$conn->close();
?>

<!-- 7️⃣ Enkel HTML-form for å slette studenten -->
<form method="post">
    <input type="hidden" name="studentnr" value="<?php echo $studentnr_vis; ?>">
    <button type="submit" name="slett">Slett student</button>
</form>
