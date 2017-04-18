<?php

require_once('./app/model/Connection.php');
require_once('./app/model/Templog.php');
require_once('./app/model/Battery.php');
require_once('./app/model/SQL.php');

$conn = Connection::getConnection();


include('header.html');

$sql = 'SELECT * FROM templog ORDER BY date DESC LIMIT 0,1';
$in = $conn->query($sql)->fetchObject();

// nasteni venkovnich hodnt z OpenWeatherMap
// $apiKey = '3e250edbdf91f2c4827fb9f4f03d60a2';
// $cityId = '3078610';
// $api = 'http://api.openweathermap.org/data/2.5/weather?id='.$cityId.'&units=metric&APPID='.$apiKey;
// $json = file_get_contents($api);
// $out = json_decode($json);

echo '<section class="clearfix">';
echo '<h2>Aktuální hodnoty</h2>';

$sql = 'SELECT n.id, n.title FROM node n WHERE enabled = 1 ORDER BY priority';
$nodes = SQL::toArray($sql);
$log = new Templog();
$battery = new Battery();

foreach($nodes as $node) {
	$temp = number_format($log->getLastValue($node->id, 'temperature'), 1);
	$hum = number_format($log->getLastValue($node->id, 'humidity'), 1);
	$updated = SQL::toScalar('SELECT MAX(date) FROM templog WHERE node = '.$node->id);
	echo '<div class="location box">';
	echo '<h3>'.$node->title.'</h3>';
	echo '<div class="info">';
	echo '<span class="item updated" title="naposledy aktualizováno '.date('G:i j.n.Y', strtotime($updated)).'">'.date('G:i', strtotime($updated)).' <span class="icon-time"></span></span>';
	echo $battery->getStatusIcon($node->id);
	echo '</div>';
	echo '<div class="actual clearfix">';
	echo '<div class="temp big"><span class="icon-temp-3"></span>'.$temp.'°C</div>';
	echo '<div class="hum big"><span class="icon-humidity"></span>'.$hum.'%</div>';
	echo '</div>';
	echo '</div>';
}

echo '</section>';


// $sql = 'SELECT * FROM templog WHERE date > ADDDATE(NOW(), INTERVAL -72 HOUR) ORDER BY date';
// $items = $conn->query($sql);

$log = new Templog();
$from = date('Y-m-d h:i:s', strtotime('-7 days'));
$items = $log->getAggregatedData($from);
$bands = $log->getBands($items);

$data = new StdClass();
$data->in = [];
$data->out = [];
$data->categories = [];
$data->bands = $bands;

foreach($items as $item) {
	$data->categories[] = $item->time;
	$data->in[] = (float) number_format($item->in, 1);
	$data->out[] = (float) number_format($item->out, 1);
}

echo '<h2>Posledních 7 dní</h2>';
echo '<section class="box history">';
echo '<div id="graph"></div>';
echo '</section>';
echo '<script>var chartData = '.json_encode($data).'</script>';

/*
$data = $conn->query('SELECT * FROM templog ORDER BY date DESC');

echo '<h2>Všechny naměřené hodnoty</h2>';
echo '<table>';
echo '<tr><th>Čas</th><th>Teplota</th><th>Vlhkost</th></tr>';

foreach($data as $row) {
	echo '<tr>
		<td>'.$row['date'].'</td>
		<td>'.$row['temperature'].'°C</td>
		<td>'.$row['humidity'].'%</td>
	</tr>';
}
echo '</table>';
*/

include('footer.html');