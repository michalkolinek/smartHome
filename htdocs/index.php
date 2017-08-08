<?php

require_once('./app/model/Connection.php');
require_once('./app/model/Templog.php');
require_once('./app/model/Battery.php');
require_once('./app/model/Box.php');
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
echo '<div class="live-values">';

$sql = 'SELECT id FROM `box` WHERE enabled = 1 ORDER BY priority';
$boxes = SQL::toValues($sql);

foreach($boxes as $id) {
	$box = new Box($id);
	echo $box->render();
}

echo '</div>';
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
	$data->in[] = $item->in ? (float) number_format($item->in, 1) : NULL;
	$data->out[] = $item->out ? (float) number_format($item->out, 1) : NULL;
	$data->moist[] = $item->moist ? round($item->moist) : NULL;
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