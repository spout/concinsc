<?php
ini_set('display_errors', 1);

try {
    $conn = new PDO("oci:dbname=ODUP", 'webuser', 'kyvhuqspoz');
} catch (PDOException $e) {
    echo($e->getMessage());
}

$conn->exec("ALTER SESSION SET NLS_DATE_FORMAT='YYYY-MM-DD HH24:MI:SS'");

$stmt = $conn->prepare("
SELECT
  ci.CODECONC,
  cs.LIBCONC ,
  cs.LIEUCONC,
  to_date(cs.DTCONCINV, 'YYYYMMDD') as dtconc,
  ci.NOORDRE,
  iv.NOAN,
  iv.BSAUMON_PAYS,
  iv.BSAUMON,
  iv.BTRAV,
  iv.NOMAN ,
  ivp.NOMAN as pere,
  ivm.noman as mere
FROM
  IDENTITE.CONCINSC ci ,
  IDENTITE.IDENTANV iv,
  identite.concsig cs ,
  identite.identanv ivp ,
  identite.identanv ivm
WHERE
  ci.NOAN = iv.NOAN AND
  ci.CODECONC = cs.CODECONC and
  cs.DTCONCINV > 20180101 and
  iv.noanpere = ivp.noan(+) and
  iv.noanmere = ivm.noan(+)
ORDER BY dtconc DESC
");

function utf8ize($d)
{
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else if (is_string($d)) {
        return utf8_encode($d);
    }
    return $d;
}

$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($results as &$result) {
    $result['DTCONC'] = \DateTime::createFromFormat('Y-m-d H:i:s', $result['DTCONC'])->format('d/m/Y');
}

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
echo json_encode(utf8ize($results));