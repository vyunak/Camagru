<?php

namespace application\core;

use application\core\View;

class Router
{
	protected $routes = [];
	protected $params = [];
	protected $matches = [];
	protected $url;

	public function __construct() {
		$arr = require 'application/config/routes.php';
		foreach ($arr as $key => $value) {
			$this->add($key, $value);
		}
	}

	public function add($route, $params) {
		$route = '/^'.str_replace('/', '\/', $route).'|{(\?.*)}$/';
		$this->routes[$route] = $params;
	}

	public function match() {
		$url = trim($_SERVER['REQUEST_URI'], '/');
		if (stristr($url, '?', true) || (!empty($url[0]) && $url[0] == '?'))
			$url = stristr($url, '?', true);
		foreach ($this->routes as $route => $params) {
			preg_match($route, $url, $matches);
			if (!empty($matches) && $matches[0] == $url)
			{
				$this->params = $params;
				$this->matches = $matches;
				$this->url = $url;
				return true;
			}
		}
		return false;
	}

	public function run() {
		if ($this->match()) {
			$connt_path = 'application\controllers\\'.ucfirst($this->params['controller']).'Controller';
			if (class_exists($connt_path)) {
				$action = $this->params['action'].'Action';
				if (method_exists($connt_path, $action)) {
					$controller = new $connt_path($this->params, $this->url, $this->matches);
					$controller->$action();
				}
				else {
					View::errorCode(404);
				}
			}
			else {
				View::errorCode(404);
			}
		}
		else
			View::errorCode(404);
	}
}

?>