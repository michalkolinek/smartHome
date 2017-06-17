<?php

require_once('./app/model/Connection.php');

class SQL
{
	public static function toArray($sql)
	{
		$conn = Connection::getConnection();
		$rows = $conn->query($sql);
		$data = [];
		foreach($rows as $row) {
			$data[] = (object) $row;
		}
		return $data;
	}

	public static function toValues($sql)
	{
		$conn = Connection::getConnection();
		$rows = $conn->query($sql);
		$data = [];
		foreach($rows as $row) {
			$data[] = $row[0];
		}
		return $data;
	}

	public static function toScalar($sql)
	{
		$conn = Connection::getConnection();
		$row = $conn->query($sql)->fetch();
		if(isset($row)) {
			return $row[0];
		} else {
			return FALSE;
		}
	}

	public static function toObject($sql)
	{
		$conn = Connection::getConnection();
		$row = $conn->query($sql)->fetch();
		if(isset($row)) {
			return (object) $row;
		} else {
			return FALSE;
		}
	}
}