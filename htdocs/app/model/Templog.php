<?php

require_once('./app/model/SQL.php');

class Templog
{
const DEFAULT_NODE_ID = 2;

	public function getCleanedData($from = NULL, $to = NULL, $sigma = 2)
	{
		$data = $this->getRawData($from, $to);
		$temps = array_map('self::getTemp', $data);

		$median = $this->getMedian($temps);
		$avg = $this->getAverage($temps);
		$stdDeviation = $this->getStandartDeviation($temps);
		$sigmas = $stdDeviation * $sigma;

		// $result = [];
		// for($i = 0; $i < count($data); $i++) {
		// 	if($i == 0 || $i == count($data) - 1) continue;
		// 	$temp = $data[$i]->temperature;
		// 	$prevTemp = $data[$i-1]->temperature;
		// 	$nextTemp = $data[$i+1]->temperature;
		// 	if($temp > $prevTemp + $sigmas && $temp > $nextTemp + $sigmas) continue;
		// 	if($temp < $prevTemp - $sigmas && $temp < $nextTemp - $sigmas) continue;
		// 	$result[] = $data[$i];
		// }
		return (object) [
			'data' => $data,
			'median' => $median,
			'avg' => $avg,
			'stdDeviation' => $stdDeviation
		];
	}

	public function getAggregatedData($from = NULL, $to = NULL)
	{
		$span = 3; // 15 minutes
		$processed = [];

		$data = $this->getCleanedData($from, $to, 2);
		$items = $data->data;
		$items = array_splice($items, count($items % $span));		
		

		$sumT = 0;
		$sumH = 0;
		foreach($items as $i => $item) {
			$sumT += $item->temperature;
			$sumH += $item->humidity;

			if($i % $span == $span-1) {
				$processed[] = (object) [
					'time' => strtotime($items[$i]->date),
					'temperature' => $sumT / $span,
					'humidity' => $sumH / $span
				];
				$sumT = 0;
				$sumH = 0;
			}			
		}

		$data->data = $processed;
		return $data;
	}

	public function getbands($data)
	{
		$bands = [];
		$moment = [];
		$lastH = 0;
		foreach($data as $i => $item) {
			$h = date('H', $item->time);

			if(!isset($moment['from']) && ($h >= 17 || $h <= 5)) {
				$moment['from'] = $i;
			} 

			if(isset($moment['from']) && ($h == 5 || $i == count($data) - 1)) {
				$moment['to'] = $i;
				$moment['color'] = 'rgba(68, 170, 213, .2)';
				$bands[] = (object) $moment;
				$moment['from'] = NULL;
				$moment['to'] = NULL;
			}

			$lastH = $h;
		}

		return $bands;
	}

	protected function getRawData($from = NULL, $to = NULL)
	{
		$where = ' node = '.self::DEFAULT_NODE_ID;
		if(!empty($from)) {
			$where .= ' AND date >= \''.$from.'\'';
		}
		if(!empty($to)) {
			$where .= ' AND date <= \''.$to.'\'';
		}
		$sql = 'SELECT * FROM templog WHERE '.$where.' ORDER BY date ASC';
		return SQL::toArray($sql);
	}

	public function getLastValue($nodeId, $column, $countToAvg = 3)
	{
		$conn = Connection::getConnection();
		$sql = 'SELECT AVG('.$column.') AS value FROM templog WHERE node = '.$nodeId.' ORDER BY date DESC LIMIT 0,'.$countToAvg;
		return SQL::toScalar($sql);
	}

	public static function getTemp($item)
	{
		return $item->temperature;
	}

	public static function getHumidity($item)
	{
		return $item->humidity;
	}

	protected function getMedian($items)
	{
		sort($items);
		return $items[floor(count($items) / 2)];
	}

	protected function getAverage($items)
	{
		return array_sum($items) / count($items);
	}

	protected function getStandartDeviation($items)
	{
		// Function to calculate square of value - mean
		function sd_square($x, $mean) { return pow($x - $mean,2); }

		// square root of sum of squares devided by N-1
		return sqrt(array_sum(array_map("sd_square", $items, array_fill(0,count($items), (array_sum($items) / count($items)) ) ) ) / (count($items)-1) );
	}
}