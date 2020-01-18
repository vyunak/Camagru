<?php
namespace application\core;

use application\core\View;
use application\lib\Db;

abstract class Controller
{
	public $route;
	public $view;
	public $model;
	public $url;
	public $matches;
	public $user;
	public $FToken;

	public function __construct($route, $url, $matches)
	{
		$this->route = $route;
		$this->matches = $matches;
		$this->url = $url;
		$this->user = $this->get_user();
		if (!empty($this->user) && empty($this->user['login']) && $route['action'] != 'index' && $route['action'] != 'logout')
			View::redirect("/");
		if ($this->checkForm() == true)
			View::redirect($this->urlFormater());
		if ($this->checkPermission() || $this->user['block'] == 1)
			View::errorCode(403);
		$this->FToken = $this->generateFormToken();
		$this->view = new View($route);
		$this->model = $this->load_model($route['controller']);
		if (!empty($this->model))
			$this->model->user = $this->user;
	}

	public function load_model($name)
	{
		$path = 'application\models\\'.ucfirst($name);
		if (class_exists($path))
			return new $path();
		return NULL;
	}

	public function generateFormToken()
	{
		$token = md5(uniqid(microtime(), true));
		$_SESSION['camagru_token'] = $token;
    	return $token;
	}

	public function checkForm()
	{
		if (empty($_POST))
			return false;
		if(!isset($_SESSION['camagru_token'])) { 
			return true;
		}
		
		if(!isset($_POST['token'])) {
			return true;
		}
		
		if ($_SESSION['camagru_token'] !== $_POST['token']) {
			return true;
		}
		
		return false;
	}

	public function get_user()
	{
		if (empty($_SESSION['hash']))
			return false;
		$hash = $_SESSION['hash'];
		$db = new Db;
		$res = $db->pquery("SELECT * FROM `users` WHERE `session` = :hash", array('hash' => $hash));
		if (empty($res))
			return false;
		return $res[0];
	}

	public function checkPermission()
	{
		$perm = require 'application/config/permission.php';
		if (in_array($this->route['action'], $perm['all']))
			return false;
		else if (in_array($this->route['action'], $perm['guest']) && empty($this->user))
			return false;
		else if (!empty($this->user['groups']) && in_array($this->route['action'], $perm[$this->user['groups']]))
			return false;
		return true;
	}

	public function email($email, $token, $subject, $endpage)
	{
		if ($email != NULL)
		{
			$to      = $email;
			$message = "
			<!DOCTYPE html>
			<html lang='en'>
			<body>
				<h3 style='background=red'>http://{$_SERVER['HTTP_HOST']}/{$endpage}?hash={$token}</h3>
			</body>
			</html>";
			$headers = 'From: no-reply@vyunak.pp.ua' . "\r\n" .
			    'Reply-To: admin@vyunak.pp.ua' . "\r\n" .
			    'Content-type: text/html; charset=UTF-8' . "\r\n";
			return mail($to, $subject, $message, $headers);
		}
		return false; 
	}

	public function urlFormater()
	{
		if (empty($_SERVER['HTTP_REFERER']))
			return "{$_SERVER['HTTP_HOST']}";
		$url = $_SERVER['HTTP_REFERER'];
		if (stristr($url, '?', true))
			$url = stristr($url, '?', true);
		return $url;
	}

}

?>