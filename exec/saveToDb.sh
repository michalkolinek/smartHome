#!/usr/bin/php
<?php

include('db.php');

$params = explode(',', $argv[1]);

$nodeId = $params[0];
$voltage = $params[1] ? $params[1] : 'NULL';
$temperature = $params[2] ? $params[2] : 'NULL';
$humidity = $params[3] ? $params[3] : 'NULL';
$moisture = $params[4] ? $params[4] : 'NULL';
$output = $params[5] || $params[5] === '0' ? $params[5] : 'NULL';
$pressure = $params[9] || $params[9] === '0' ? $params[9] : 'NULL';
$windAvg = $params[10] || $params[10] === '0' ? $params[10] : 'NULL';
$windMax = $params[11] || $params[11] === '0' ? $params[11] : 'NULL';
$waterImpuls = $params[12] || $params[12] === '0'? $params[12] : 'NULL';
$light = $params[13] || $params[13] === '0'? $params[13] : 'NULL';
$pump = $params[14] || $params[14] === '0'? $params[14] : 'NULL';

$date = date('Y-m-d H:i:s');

$sql = 'INSERT INTO templog(node, date, temperature, humidity, moisture, pressure, output, voltage, windAvg, windMax, waterImpuls, light, pump)
    VALUES('.$nodeId.', "'.$date.'", '.$temperature.', '.$humidity.', '.$moisture.', '.$pressure.', '.$output.', '.$voltage.', '.$windAvg.', '.$windMax.', '.$waterImpuls.', '.$light.', '.$pump.')';
$result = $connection->query($sql);

if($result) {
  echo "OK\n";
  exit(0);
} else {
  echo "Failed\n";
  exit(1);
}
