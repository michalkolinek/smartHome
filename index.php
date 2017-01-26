<?php

require_once('./app/model/Connection.php');

$conn = Connection::getConnection();


include('header.html');

$sql = 'SELECT * FROM templog ORDER BY date DESC LIMIT 0,1';
$item = $conn->query($sql)->fetchObject();

echo '<h2>Aktuální hodnoty</h2>';
echo '<div class="actual clearfix">';
echo '<div class="temp big"><span class="icon-temp-3"></span>'.$item->temperature.'°C</div>';
echo '<div class="hum big"><span class="icon-humidity"></span>'.$item->humidity.'%</div>';
echo '</div>';

$sql = 'SELECT * FROM templog WHERE date > ADDDATE(NOW(), INTERVAL -72 HOUR) ORDER BY date';
$items = $conn->query($sql);

$data = new StdClass();
$data->temp = [];
$data->humidity = [];
$data->categories = [];

foreach($items as $item) {
	$data->categories[] = $item['date'];
	$data->temp[] = (float) $item['temperature'];
	$data->humidity[] = (float) $item['humidity'];
}

echo '<h2>Posledních 72 hodin</h2>';
echo '<div id="graph"></div>';
echo '<script>var chartData = '.json_encode($data).'</script>';


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

include('footer.html');