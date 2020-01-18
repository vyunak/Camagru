<?php

require	'application/lib/Dev.php';

ini_set('display_errors', 'on');

use	application\core\Router;

spl_autoload_register(function ($class) {
	$path = str_replace('\\', '/', $class.'.php');
	if (file_exists($path))
		require $path;
});

$router = new Router;

session_start();
$router->run();

?>