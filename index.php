<?php

require_once('./app/model/Connection.php');

$conn = Connection::getConnection();


include('header.html');

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