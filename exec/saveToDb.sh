#!/usr/bin/php
<?php

$dns = 'mysql:host=localhost;dbname=SmartHome;charset=utf8';
$connection = new PDO($dns, 'root', '0i549bmK');

$params = explode(',', $argv[1]);

$nodeId = $params[0];
$voltage = $params[1];
$temperature = $params[2];
$humidity = $params[3];
$moisture = $params[4];
$output = $params[5];
$date = date('Y-m-d H:i:s');

$sql = 'INSERT INTO templog(node, date, temperature, humidity, moisture, output, voltage)
	VALUES('.$nodeId.', "'.$date.'", '.$temperature.', '.$humidity.', '.$moisture.', '.$output.', '.$voltage.')';

$connection->query($sql);

echo 'OK';
exit;