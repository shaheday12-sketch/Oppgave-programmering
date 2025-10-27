<?php include "db.php"; ?>
<!doctype html>
<html lang="no">
<head>
  <meta charset="utf-8"><title>Alle klasser</title>
  <style>
    body{font-family:system-ui,Arial;margin:0;background:#f5f6fa;color:#222;padding:30px}
    .wrap{max-width:900px;margin:auto}
    .box{background:#fff;border:1px solid #e6e8ec;border-radius:12px;overflow:hidden;box-shadow:0 1px 2px rgba(0,0,0,.05)}
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px 12px;border-bottom:1px solid #e6e8ec;text-align:left}
    th{background:#f3f4f6}
    tr:nth-child(even) td{background:#fafafa}
    a{color:#2563eb;text-decoration:none}
    h2{margin:0 0 12px}
    .pad{padding:16px}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="pad"><h2>Registrerte klasser</h2></div>
    <div class="box">
      <table>
        <thead><tr><th>Klassekode</th><th>Klassenavn</th><th>Studiumkode</th></tr></thead>
        <tbody>
        <?php
          $res = $conn->query("SELECT klassekode, klassenavn, studiumkode FROM klasse ORDER BY klassekode");
          while ($r = $res->fetch_assoc()){
            echo "<tr>
                    <td>".htmlspecialchars($r['klassekode'])."</td>
                    <td>".htmlspecialchars($r['klassenavn'])."</td>
                    <td>".htmlspecialchars($r['studiumkode'])."</td>
                  </tr>";
          }
        ?>
        </tbody>
      </table>
    </div>
    <div class="pad"><a href="index.php">← Tilbake</a></div>
  </div>
</body>
</html>