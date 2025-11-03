<?php 

// --- KOBLE TIL DATABASEN --- 

$server = "b-studentsql-1.usn.no";     // endre hvis du bruker annen server 

$bruker = "shayo1243";          // skriv inn brukernavnet ditt 

$passord = "5791shayo1243";             // skriv inn passordet ditt 

$dbnavn = "shayo1243";    

 

$conn = new mysqli($server, $bruker, $passord, $dbnavn); 

if ($conn->connect_error) { 

    die("Feil ved tilkobling: " . $conn->connect_error); 

} 

 

// --- DEFINER VARIABLER --- 

  

$studenter = [];  

  

$msg = null;  

$err = null;  

$antStudenter = 0;  

  

  

// --- HENT STUDENTER FRA DATABASE --- 

$sql = "SELECT studentnr, fornavn, etternavn FROM student ORDER BY studentnr";  

$resultat = $conn->query($sql);  

  

if ($resultat && $resultat->num_rows > 0) {  

    $studenter = $resultat;  

} 

 

// --- HÅNDTER SLETTING --- 

if ($_SERVER["REQUEST_METHOD"] === "POST") {  

$studentnr = $_POST["studentnr"] ?? '';  
 
if ($studentnr !== '') {  
    // Sjekk om studenten finnes i databasen 
    $sjekk = $conn->prepare("SELECT COUNT(*) FROM student WHERE studentnr = ?"); 
    $sjekk->bind_param("s", $studentnr); 
    $sjekk->execute(); 
    $sjekk->bind_result($antStudenter); 
    $sjekk->fetch(); 
    $sjekk->close(); 
 
    if ($antStudenter === 0) {  
        $err = "Fant ikke studenten med nummer '$studentnr'."; 
    } else {  
        // Slett studenten 
        $stmt = $conn->prepare("DELETE FROM student WHERE studentnr = ?"); 
        $stmt->bind_param("s", $studentnr); 
         
        if ($stmt->execute()) {  
            $msg = "Studenten med nummer '$studentnr' ble slettet.";  
        } else {  
            $err = "Klarte ikke å slette studenten.";  
        }  
 
        $stmt->close();  
    }  
} else {  
    $err = "Du må velge en student.";  
}  
  

} ?> 

 

<!doctype html> 

<html lang="no"> 

<head> 

<meta charset="utf-8"> 

<title>Slett klasse</title> 

<style> 

body{font-family:system-ui,Arial;margin:0;background:#f5f6fa;color:#222;padding:30px} 

.form{max-width:420px;margin:auto;background:#fff;border:1px solid #e6e8ec;border-radius:10px;padding:20px 24px;box-shadow:0 2px 5px rgba(0,0,0,.06)} 

h2{margin:0 0 14px;text-align:center} 

label{display:block;font-weight:600;margin-top:10px} 

select{width:100%;padding:10px;border:1px solid #ccc;border-radius:8px;margin-top:6px;font:inherit} 

button{width:100%;margin-top:14px;background:#111;color:#fff;border:0;border-radius:8px;padding:10px;font:inherit;cursor:pointer} 

.msg{margin:10px 0;padding:8px;border-radius:6px;text-align:center} 

.ok{background:#e8f5e9;color:#2e7d32} 

.warn{background:#fff3cd;color:#856404} 

.err{background:#fdecea;color:#c62828} 

p.link{text-align:center;margin-top:10px} 

a{color:#2563eb;text-decoration:none} 

</style> 

</head> 

<body> 

<div class="form"> 

<h2>Slett  stundt</h2> 

 

<div class="msg ok"><?= htmlspecialchars($msg) ?></div>  
 <div class="msg <?= ($antStudenter ?? 0) > 0 ? 'warn' : 'err' ?>"><?= htmlspecialchars($err) ?></div>  
  

Velg student 

Velg student 

num_rows > 0): ?> fetch_assoc()): ?>  

–  

Slett  

← Tilbake 

 