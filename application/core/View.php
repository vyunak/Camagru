<?php

	namespace application\core;

	class View
	{
		public $path;
		public $route;
		public $layout = 'default';

		public function __construct($route)
		{
			$this->route = $route;
			$this->path = $route['action'];
		}

		public function render($title = '', $vars = [])
		{
			if (empty($title))
				$title = $this->route['action'];
			extract($vars);
			$this->path = 'application/views/'.$this->path.'.view.php';
			if (file_exists($this->path))
			{
				ob_start();
				require $this->path;
				$content = ob_get_clean();
				require 'application/views/layouts/'.$this->layout.'.php';
			}
			else {
				View::errorCode(404);
			}
		}

		public static function errorCode($code) {
			http_response_code($code);
			$exCode = 'application/views/errorCode/'.$code.'.php';
			if (file_exists($exCode))
			{
				require 'application/views/errorCode/'.$code.'.php';
				exit ;
			}
			else {
				require 'application/views/errorCode/500.php';
				exit ;
			}
		}

		public static function redirect($url)
		{
			header('Location: '.$url);
			exit ;
		}

		public function message($status, $message)
		{
			exit(json_encode(['status' => $status, 'message' => $message]));
		}

	}


?>