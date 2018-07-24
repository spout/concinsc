<?php
ini_set('display_errors', 1);

try {
    $conn = new PDO("oci:dbname=ODUP", 'webuser', 'kyvhuqspoz');
} catch (PDOException $e) {
    echo($e->getMessage());
}

$code = '%' . date('my');

$stmt = $conn->prepare("
SELECT
  IDENTITE.CONCINSC.CODECONC,
  IDENTITE.CONCINSC.NOORDRE,
  IDENTITE.IDENTANV.NOAN,
  IDENTITE.IDENTANV.BSAUMON_PAYS,
  IDENTITE.IDENTANV.BSAUMON,
  IDENTITE.IDENTANV.BTRAV,
  IDENTITE.IDENTANV.NOMAN
FROM 
  IDENTITE.CONCINSC, 
  IDENTITE.IDENTANV
WHERE 
  IDENTITE.CONCINSC.NOAN = IDENTITE.IDENTANV.NOAN AND
  IDENTITE.CONCINSC.CODECONC LIKE :code
");

$stmt->execute([':code' => $code]);

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
echo json_encode($results);