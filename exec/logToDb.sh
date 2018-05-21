#!/usr/bin/php
<?php

include('db.php');

$msg = json_decode($argv[1]);
$date = date('Y-m-d H:i:s');
$data = $msg->data !== NULL ? '"'.addslashes(json_encode($msg->data)).'"' : 'NULL';

$sql = 'INSERT INTO log(time, object, action, data)
	VALUES("'.$date.'", "'.$msg->object.'", "'.$msg->action.'", '.$data.')';
$result = $connection->query($sql);

if($result) {
  echo "OK\n";
} else {
  echo "Failed\n";
}
exit(0);