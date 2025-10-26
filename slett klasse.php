<?php include 'db.php'; ?>

<h2>Slett klasse</h2>

<form method="post">
  <label>Velg klasse:</label>
  <select name="klassekode">
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
if (isset($_POST['slett'])) {
  $kode = $_POST['klassekode'];
  $conn->query("DELETE FROM klasse WHERE klassekode='$kode'");
  echo "✅ Klassen ble slettet!";
}
?>
