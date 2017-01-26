<?php

class Connection 
{
	private	$host = 'localhost';
	private	$user = 'root';
	private	$pass = '0i549bmK';
	private	$db = 'logger';

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
