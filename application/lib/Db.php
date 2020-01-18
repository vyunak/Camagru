<?php

namespace application\lib;

use PDO;

class Db
{
	protected $db;

	public function __construct()
	{
		require 'application/config/database.php';
		try {
			$this->db = new PDO('mysql:host='.$DB_HOST.';dbname='.$DB_DSN, $DB_USER, $DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ERRMODE_EXCEPTION));
		} catch (PDOException $e) {
			exit("Fatal error!");
		}
	}

	public function verify($sql)
	{
		$res = $this->db->query($sql);
		return ($res);
	}

	public function query($sql)
	{
		$res = $this->verify($sql);
		if ($res)
			$res = $res->fetchAll(PDO::FETCH_ASSOC);
		return ($res);
	}

	public function pquery($sql, $params = [])
	{
		$stmt = $this->db->prepare($sql);
		if (!empty($params))
		{
			foreach ($params as $key => $val) {
				$stmt->bindValue(':'.$key, $val);
			}
			if ($stmt->execute())
				return ($stmt->fetchAll(PDO::FETCH_ASSOC));
		}
		return (false);
	}

	public function column($sql)
	{
		$res = $this->verify($sql);
		if ($res)
			$res = $res->fetchColumn(PDO::FETCH_ASSOC);
		return ($res);
	}

}

?>