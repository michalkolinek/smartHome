#!/usr/bin/php
<?php

include('db.php');

//$dns = 'mysql:host=localhost;dbname=SmartHome;charset=utf8';
//$connection = new PDO($dns, 'root', '0i549bmK');

$params = explode(',', $argv[1]);

$nodeId = $params[0];
$voltage = $params[1] === '0' ? 'NULL' : $params[1];
$temperature = $params[2] ? $params[2] : 'NULL';
$humidity = $params[3] ? $params[3] : 'NULL';
$moisture = $params[4] ? $params[4] : 'NULL';
$output = $params[5] || $params[5] === '0' ? $params[5] : 'NULL';
$date = date('Y-m-d H:i:s');

$sql = 'INSERT INTO templog(node, date, temperature, humidity, moisture, output, voltage)
	VALUES('.$nodeId.', "'.$date.'", '.$temperature.', '.$humidity.', '.$moisture.', '.$output.', '.$voltage.')';
$result = $connection->query($sql);

if($result) {
  echo "OK\n";
} else {
  echo "Failed\n";
}
exit(0);