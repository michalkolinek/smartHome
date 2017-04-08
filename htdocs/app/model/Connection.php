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
		// $dns = 'mysql:host='.$this->host.';dbname='.$this->db.';charset=utf8';
		$dns = 'mysql:host='.getenv('DB_HOST').';dbname='.getenv('DB_NAME').';charset=utf8';
		self::$connection = new PDO($dns, getenv('DB_USER'), getenv('DB_PASS'));		
	}

}
