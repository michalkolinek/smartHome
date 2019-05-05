#!/usr/bin/php
<?php

include('db.php');

$nodeId = 8;
$impulsVolume = 0.337; // jedno preklopeni je 0.3mm vodniho sloupce

$sql = 'SELECT SUM(waterImpuls) as impulses FROM templog WHERE node = '.$nodeId.' AND date >= SUBDATE(CURTIME(), INTERVAL 1 HOUR)';
$result = $connection->query($sql);

if($result) {
	$value = $result->fetch(PDO::FETCH_OBJ);
	$lastHour = ($value->impulses / 2) * $impulsVolume;
} else {
  	echo "Failed\n";
  	exit(1);
}

$sql = 'SELECT SUM(waterImpuls) as impulses FROM templog WHERE node = '.$nodeId.' AND date >= SUBDATE(CURTIME(), INTERVAL 1 DAY)';
$result = $connection->query($sql);

if($result) {
	$value = $result->fetch(PDO::FETCH_OBJ);
	$lastDay = ($value->impulses / 2) * $impulsVolume;
} else {
  	echo "Failed\n";
  	exit(1);
}

$data = new StdClass();
$data->hour = $lastHour;
$data->day = $lastDay;

echo json_encode($data);

exit(0);