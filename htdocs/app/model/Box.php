<?php

require_once('./app/model/SQL.php');

class Box
{
	const METRIC_TYPE_NUMBER = 0;
	const METRIC_TYPE_BOOL = 1;

	private $id;
	private $config;

	public static $icons = [
		'temperature' => 'icon-temp-3',
		'humidity' => 'icon-humidity',
		'moisture' => 'icon-water',
		'output' => 'icon-power-plug',
		'light' => 'icon-light',
		'pump' => 'icon-droplet',
		'wind' => 'icon-wind',
		'waterImpuls' => 'icon-water',
		'pressure' => '',
	];

	public static $cssClasses = [
		'temperature' => 'temp',
		'humidity' => 'hum',
		'moisture' => 'moist',
		'output' => 'output',
		'light' => 'output',
		'pump' => 'output',
		'wind' => 'hum',
		'waterImpuls' => 'hum',
		'pressure' => 'hum',
	];

	public static $units = [
		'temperature' => '°C',
		'humidity' => '%',
		'moisture' => '',
		'output' => '',
		'wind' => 'm/s',
		'waterImpuls' => 'mm/h',
		'pressure' => 'hPa',
	];

	public function __construct($id)
	{
		$this->id = $id;
		$cfg = SQL::toScalar('SELECT config FROM `box` WHERE id = '.$this->id);
		$this->config = json_decode($cfg);
	}

	public function render()
	{
		$log = new Templog();
		$battery = new Battery();

		$updated = SQL::toScalar('SELECT MAX(date) FROM templog WHERE node = '.$this->config->metrics[0]->node);
		echo '<div class="location box">';
		echo '<h3>'.$this->config->title.'</h3>';
		echo '<div class="info">';
		echo '<span class="item updated" title="naposledy aktualizováno '.date('G:i j.n.Y', strtotime($updated)).'">'.date('G:i', strtotime($updated)).' <span class="icon-time"></span></span>';
		echo $battery->getStatusIcon($this->config->metrics[0]->node);
		echo '</div>';
		echo '<div class="actual clearfix">';

		foreach($this->config->metrics as $metric) {
			$avgCount = isset($metric->avgCount) ? $metric->avgCount : 3;

			if($metric->type == self::METRIC_TYPE_BOOL) {
				$val = (int) $log->getLastValue($metric->node, $metric->column, $avgCount);
				$formatedVal = ($val ? 'ON' : 'OFF');
			} else {
				$val = number_format($log->getLastValue($metric->node, $metric->column, $avgCount), 1);
				$formatedVal = $val.self::$units[$metric->column];
			}
			echo '<div class="'.self::$cssClasses[$metric->column].' big"><span class="'.self::$icons[$metric->column].'"></span>'.$formatedVal.'</div>';
		}

		echo '</div>';
		echo '</div>';
	}


}