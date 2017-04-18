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
			$html = '<span class="item battery-status'.($state->level == 'low' ? ' critical' : '').'" title="'.$info.'">'.$supplyV.'V <span class="'.$state->icon.'"></span>
			</span>';

			return $html;
	}

	public function getLastValue($nodeId, $countToAvg = 3)
	{
		$conn = Connection::getConnection();
		$sql = 'SELECT AVG(item.voltage) FROM (SELECT voltage FROM templog WHERE node = '.$nodeId.' ORDER BY date DESC LIMIT 0,'.$countToAvg.') as item';
		return SQL::toScalar($sql);
	}
}