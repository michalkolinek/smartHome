#!/usr/bin/php
<?php

$dns = 'mysql:host=localhost;dbname=SmartHome;charset=utf8';
$connection = new PDO($dns, 'root', '0i549bmK');


$nodeId = 5;

$sql = 'SELECT moisture FROM templog WHERE node = '.nodeId.' AND moisture IS NOT NULL ORDER BY date DESC LIMIT 0,1';
$result = $connection->query($sql);

if($result) {
	$value = $result->fetch(PDO::FETCH_OBJ);
  	echo $value->moisture;
} else {
  	echo "Failed\n";
}
exit(0);