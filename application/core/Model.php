<?php
	namespace application\core;

	use application\lib\Db;

	abstract class Model
	{
		public $db;
		public $errors;
		public $visible;
		public $success;
		public $user;

		function __construct()
		{
			$this->db = new Db;
			$this->errors = (!empty($_GET['errors'])) ? [$_GET['errors']] : [];
			$this->success = (!empty($_GET['success'])) ? [$_GET['success']] : [];
			$this->visible = 0;
			$this->user = NULL;
		}

		public function test_input($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}


		public function email($email, $user, $messages, $id, $subject)
		{
			if ($email != NULL)
			{
				$to      = $email;
				$message = "
				<!DOCTYPE html>
				<html lang='en'>
				<body>
					<h3 style='background=red'>Someone {$messages} your <a href='http://{$_SERVER['HTTP_HOST']}/photo/{$id}'>post.</a></h3>
				</body>
				</html>";
				$headers = 'From: no-reply@vyunak.pp.ua' . "\r\n" .
				    'Reply-To: admin@vyunak.pp.ua' . "\r\n" .
				    'Content-type: text/html; charset=UTF-8' . "\r\n";
				return mail($to, $subject, $message, $headers);
			}
			return false; 
		}

	}
?>