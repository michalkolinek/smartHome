<?php

require_once('./app/model/SQL.php');

class Battery
{

	public static $tresholds = [
		3.0 => 'full',
		2.95 => 'high',
		2.9 => 'mid',
		0 => 'low'
	];

	public static $levelNames = [
		'full' => 'Baterie je plná',
		'high' => 'Baterie je téměř plná',
		'mid' => 'Baterie je téměř vybitá',
		'low' => 'Baterie je vybitá, potřebuje co nejdříve vyměnit',
	];

	public function getState($nodeId)
	{
		$val = $this->getLastValue($nodeId);
		$level = 'low';
		foreach(self::$tresholds as $t => $l) {
			$level = $l;
			if($val >= $t) {
				break;
			}
		}

		$result = new StdClass();
		$result->level = $level;
		$result->icon = 'icon-battery-'.$level;
		$result->info = self::$levelNames[$level];
		$result->value = $val;

		return $result;
	}

	public function getStatusIcon($nodeId)
	{
			$state = $this->getState($nodeId);
			$supplyV = number_format($state->value, 2);

			$info = 'Napětí '.$supplyV.'V, '.$state->info;
			$html = '<div class="battery-status'.($state->level == 'low' ? ' critical' : '').'" title="'.$info.'">
				<span class="'.$state->icon.'"></span>
			</div>';

			return $html;
	}

	public function getLastValue($nodeId, $countToAvg = 3)
	{
		$conn = Connection::getConnection();
		$sql = 'SELECT AVG(voltage) AS value FROM templog WHERE node = '.$nodeId.' ORDER BY date DESC LIMIT 0,3';
		return SQL::toScalar($sql);
	}
}