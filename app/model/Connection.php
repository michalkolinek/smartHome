<?php

class Connection 
{
	public static $connection = NULL;

	public static function getConnection() 
	{
		if(self::$connection === NULL) {
			$conn = new self();
			$conn->connect();
		}
		return self::$connection;
	}

	public function connect()
	{
		$dns = 'mysql:host='.$this->host.';dbname='.$this->db.';charset=utf8';
		self::$connection = new PDO($dns, $this->user, $this->pass);		
	}

}
