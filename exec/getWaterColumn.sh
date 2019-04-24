#!/usr/bin/php
<?php

include('db.php');

$nodeId = 8;
$impulsVolume = 0.337; // jedno preklopeni je 0.3mm vodniho sloupce

$sql = 'SELECT SUM(waterImpuls) as impulses FROM templog WHERE node = '.$nodeId.' AND date >= SUBDATE(CURTIME(), INTERVAL 1 HOUR)';
$result = $connection->query($sql);

if($result) {
	$value = $result->fetch(PDO::FETCH_OBJ);
	$waterColumn = ($value->impulses / 2) * $impulsVolume;
  	echo $waterColumn;
} else {
  	echo "Failed\n";
}
exit(0);