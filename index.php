<?php

require_once('./app/model/Connection.php');
require_once('./app/model/Templog.php');

$conn = Connection::getConnection();


include('header.html');

$sql = 'SELECT * FROM templog ORDER BY date DESC LIMIT 0,1';
$item = $conn->query($sql)->fetchObject();

echo '<h2>Aktuální hodnoty</h2>';
echo '<div class="actual clearfix">';
echo '<div class="temp big"><span class="icon-temp-3"></span>'.$item->temperature.'°C</div>';
echo '<div class="hum big"><span class="icon-humidity"></span>'.$item->humidity.'%</div>';
echo '</div>';

// $sql = 'SELECT * FROM templog WHERE date > ADDDATE(NOW(), INTERVAL -72 HOUR) ORDER BY date';
// $items = $conn->query($sql);

$log = new Templog();
$from = date('Y-m-d h:i:s', strtotime('-7 days'));
$cleanedData = $log->getAggregatedData($from);
$bands = $log->getBands($cleanedData->data);

$data = new StdClass();
$data->temp = [];
$data->humidity = [];
$data->categories = [];
$data->bands = $bands;

foreach($cleanedData->data as $item) {
	$data->categories[] = $item->time;
	$data->temp[] = (float) number_format($item->temperature, 1);
	$data->humidity[] = (float) number_format($item->humidity, 1);
}

echo '<h2>Posledních 7 dní</h2>';
echo '<p class="info">Medián: '.$cleanedData->median.'°C, průměr: '.number_format($cleanedData->avg, 1).'°C, std. odchylka: '.number_format($cleanedData->stdDeviation, 3).'</p>';
echo '<div id="graph"></div>';
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