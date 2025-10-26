<?php
$host = "b-studentsql-1.usn.no";
$user = "shayo1243"; //brukernavnet ditt
$pass = "5791shayo1243"; // skriv inn passordet ditt her
$db   = "shayo1243"; // databasenavn (samme som brukernavnet ditt)

// Koble til databasen
$conn = new mysqli($host, $user, $pass, $db);

// Sjekk om tilkoblingen fungerer
if ($conn->connect_error) {
    die("Tilkoblingsfeil: " . $conn->connect_error);
}

// Hvis du vil teste at alt virker:
// echo "Koblet til databasen!"; 