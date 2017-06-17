<?php

require_once('./app/model/SQL.php');

class Templog
{
	const DEFAULT_NODE_IN = 2;
	const DEFAULT_NODE_OUT = 5;
	const TIME_SPAN = 30; // 15 minutes

	public function getAggregatedData($from = NULL, $to = NULL)
	{
		$data = $this->getRawData([self::DEFAULT_NODE_IN, self::DEFAULT_NODE_OUT], $from, $to);

		$processed = [];
		$sumIn = 0;
		$sumOut = 0;
		$sumMoist = 0;
		$countIn = 0;
		$countOut = 0;
		$countMoist = 0;
		$start = FALSE;

		foreach($data as $i => $item) {
			if(!$start) {
				$start = strtotime($item->date);
			}
			if($item->node == self::DEFAULT_NODE_IN) {
				$sumIn += $item->temperature;
				$countIn++;
			} else {
				$sumOut += $item->temperature;
				$countOut++;
				$sumMoist += $item->moisture;
				$countMoist++;
			}

			if(strtotime($item->date) - $start >= self::TIME_SPAN * 60) {
				$processed[] = (object) [
					'time' => strtotime($item->date),
					'in' => $countIn ? $sumIn / $countIn : NULL,
					'out' => $countOut ? $sumOut / $countOut : NULL
					'moist' => $countMoist ? $sumMoist / $countMoist : NULL
				];
				$start = strtotime($item->date);
				$sumIn = 0;
				$sumOut = 0;
				$sumMoist = 0;
				$countIn = 0;
				$countOut = 0;
				$countMoist = 0;
			}
		}

		return $processed;
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

	protected function getRawData($nodes = [], $from = NULL, $to = NULL)
	{
		$where = ' node IN ('.join(',', $nodes).')';
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
		$sql = 'SELECT AVG(item.val) FROM (SELECT '.$column.' AS val FROM templog WHERE node = '.$nodeId.' ORDER BY date DESC LIMIT 0,'.$countToAvg.') as item';
		return SQL::toScalar($sql);
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