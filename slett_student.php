<?php 
require_once 'db.php'; 
?>

<!DOCTYPE html>
<html lang="no">
<head>
<meta charset="utf-8">
<title>Slett student</title>
<style>
body {
    font-family: system-ui, Arial;
    margin: 0;
    background: #f5f6fa;
    color: #222;
    padding: 30px;
}
.form {
    max-width: 420px;
    margin: auto;
    background: #fff;
    border: 1px solid #e6e8ec;
    border-radius: 10px;
    padding: 20px 24px;
    box-shadow: 0 2px 5px rgba(0,0,0,.06);
}
h2 {
    margin: 0 0 14px;
    text-align: center;
}
label {
    display: block;
    font-weight: 600;
    margin-top: 10px;
}
select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    margin-top: 6px;
    font: inherit;
}
button {
    width: 100%;
    margin-top: 14px;
    background: #111;
    color: #fff;
    border: 0;
    border-radius: 8px;
    padding: 10px;
    font: inherit;
    cursor: pointer;
}
.msg {
    margin: 10px 0;
    padding: 8px;
    border-radius: 6px;
    text-align: center;
}
.ok { background: #e8f5e9; color: #2e7d32; }
.warn { background: #fff3cd; color: #856404; }
.err { background: #fdecea; color: #c62828; }
p.link {
    text-align: center;
    margin-top: 10px;
}
a {
    color: #2563eb;
    text-decoration: none;
}
</style>
</head>
<body>
<div class="form">
    <h2>Slett student</h2>

    <?php 
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $studentnr = $_POST["studentnr"] ?? "";

        if ($studentnr === "") {
            echo "<div class='msg err'>Velg en student.</div>";
        } else {
            // Forbered DELETE-spørring
            $stmt = $conn->prepare("DELETE FROM student WHERE studentnr = ?");
            if ($stmt) {
                $stmt->bind_param("s", $studentnr);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo "<div class='msg ok'>Studenten er slettet.</div>";
                } else {
                    echo "<div class='msg warn'>Fant ingen slik student.</div>";
                }

                $stmt->close();
            } else {
                echo "<div class='msg err'>Noe gikk galt. Prøv igjen senere.</div>";
            }
        }
    }
    ?>

    <form method="post" onsubmit="return confirm('Slette valgt student?')">
        <label for="studentnr">Velg student</label>
        <select id="studentnr" name="studentnr" required>
            <option value="">Velg student</option>
            <?php
            $res = $conn->query("SELECT studentnr, fornavn, etternavn FROM student ORDER BY etternavn, fornavn");
            while ($r = $res->fetch_assoc()) {
                $id = htmlspecialchars($r['studentnr']);
                $navn = htmlspecialchars($r['fornavn'] . " " . $r['etternavn']);
                echo "<option value=\"$id\">$navn</option>";
            }
            ?>
        </select>
        <button type="submit">Slett</button>
    </form>

    <p class="link"><a href="index.php">← Tilbake</a></p>
</div>
</body>
</html>
