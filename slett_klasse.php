<?php include 'db_connect.php'; ?>  <!-- Pass på riktig filnavn -->

<h2>Slett klasse</h2>

<form method="post">
  <label>Velg klasse:</label>
  <select name="klassekode" required>
    <option value="">-- Velg klasse --</option>
    <?php
      $res = $conn->query("SELECT klassekode FROM klasse");
      while ($r = $res->fetch_assoc()) {
        echo "<option value='{$r['klassekode']}'>{$r['klassekode']}</option>";
      }
    ?>
  </select>
  <input type="submit" name="slett" value="Slett">
</form>

<?php
if (isset($_POST['slett']) && !empty($_POST['klassekode'])) {
  $kode = $conn->real_escape_string($_POST['klassekode']); // gjør koden trygg
  $sql = "DELETE FROM klasse WHERE klassekode='$kode'";
  
  if ($conn->query($sql) === TRUE) {
    echo "✅ Klassen med kode <strong>$kode</strong> ble slettet!";
  } else {
    echo "❌ Feil: " . $conn->error;
  }
}

$conn->close();
?>
