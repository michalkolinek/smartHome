#!/usr/bin/php
<?php

$dns = 'mysql:host=localhost;dbname=SmartHome;charset=utf8';
$connection = new PDO($dns, 'root', '0i549bmK');

$params = explode(',', $argv[1]);

$nodeId = $params[0];
$voltage = $params[1];
$temperature = $params[2];
$humidity = $params[3];
$date = date('Y-m-d H:i:s');

$sql = 'INSERT INTO templog(node, date, temperature, humidity, voltage) 
	VALUES('.$nodeId.', "'.$date.'", '.$temperature.', '.$humidity.', '.$voltage.')';

$connection->query($sql);

echo 'OK';
exit;