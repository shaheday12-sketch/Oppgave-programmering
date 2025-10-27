<?php
$host = "b-studentsql-1.usn.no";
$db   = "shayo1243";
$user = "shayo1243";
$pass = "5791shayo1243";

// Koble til databasen
$conn = new mysqli($host, $user, $pass, $db);

// Sjekk om tilkoblingen fungerer
if ($conn->connect_error) {
    die("Tilkoblingsfeil: " . $conn->connect_error);
}

// Sett korrekt tegnsett
$conn->set_charset('utf8mb4');

// Hvis du vil teste at alt virker:
// echo "Koblet til databasen!";
?>
