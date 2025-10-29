<?php include "db.php"; ?>
<!doctype html>
<html lang="no">
<head>
<meta charset="utf-8">
<title>Slett student</title>
<style>
  body {font-family: Arial; background: #f5f6fa; padding: 30px;}
  .form {max-width: 400px; margin: auto; background: #fff; padding: 20px; border-radius: 8px;}
  h2 {text-align: center;}
  select, button {width: 100%; padding: 10px; margin-top: 10px; border-radius: 6px; border: 1px solid #ccc;}
  button {background: #111; color: #fff; cursor: pointer;}
  .msg {text-align: center; margin-top: 10px; padding: 8px; border-radius: 6px;}
  .ok {background: #e8f5e9; color: #2e7d32;}
  .warn {background: #fff3cd; color: #856404;}
  .err {background: #fdecea; color: #c62828;}
</style>
</head>
<body>

<div class="form">
  <h2>Slett student</h2>

  <?php
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $studentnr = $_POST["studentnr"] ?? "";

      if ($studentnr == "") {
          echo "<div class='msg err'>Velg en student.</div>";
      } else {
          // Slett studenten
          $sql = "DELETE FROM student WHERE studentnr='$studentnr'";
          if ($conn->query($sql) === TRUE) {
              if ($conn->affected_rows > 0) {
                  echo "<div class='msg ok'>Studenten er slettet.</div>";
              } else {
                  echo "<div class='msg warn'>Fant ingen student med dette nummeret.</div>";
              }
          } else {
              echo "<div class='msg err'>Feil: " . $conn->error . "</div>";
          }
      }
  }
  ?>

  <form method="post" onsubmit="return confirm('Vil du slette denne studenten?')">
    <label for="studentnr">Velg student</label>
    <select name="studentnr" id="studentnr" required>
      <option value="">Velg student</option>
      <?php
      $result = $conn->query("SELECT studentnr, fornavn, etternavn FROM student ORDER BY studentnr");
      if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              $id = htmlspecialchars($row['studentnr']);
              $navn = htmlspecialchars($row['fornavn'] . " " . $row['etternavn']);
              echo "<option value='$id'>$id – $navn</option>";
          }
      }
      ?>
    </select>

    <button>Slett</button>
  </form>

  <p style="text-align:center; margin-top:10px;"><a href="index.php">← Tilbake</a></p>
</div>

</body>
</html>