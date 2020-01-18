<?php

namespace application\models;

use application\core\Model;

class Account extends Model
{

	public function login($info = [])
	{
		if (!empty($info) && !empty($info['email']) && !empty($info['password'])
			&& strlen($info['email']) > 6 && strlen($info['password']) > 6)
		{

			$check = $this->db->pquery('SELECT * FROM `users` WHERE `email` = :email', ['email' => $info['email']]);
			if ( !empty($check) ) {
				if (password_verify($info['password'], $check[0]['password']))
				{
					$session = hash('sha256', $info['email'].time());
					$res = $this->db->pquery('UPDATE `users` SET `session` = :session WHERE `email` = :email', ['session' => $session, 'email' => $info['email']]);
					return $session;
				}
			}
			else {
				$this->errors[] = "User does not exist, use the registration <a href=\"/\">page</a> to create account.";
				return false;
			}
		}
		$this->errors[] = "Incorrect login or password!";
		return false;
	}

	public function register($info = [])
	{
		if (!empty($info) && !empty($info['email']) && !empty($info['password']) && !empty($info['confirm_password'])
			&& strlen($info['password']) > 6)
		{
			if ($info['confirm_password'] != $info['password'])
			{
				$this->errors[] = "Passwords do not match!";
				return false;
			}
			$check = $this->db->pquery('SELECT * FROM `users` WHERE `email` = :email', ['email' => $info['email']]);
			if (empty($check)) {
				$params = [
					'email' => $info['email'],
					'password' => password_hash($info['password'], PASSWORD_DEFAULT),
					'hash' => hash('sha256', password_hash($info['password'], PASSWORD_DEFAULT))
				];
				$res = $this->db->pquery('INSERT INTO `users` (`email`, `password`, `verify_hash`) VALUES (:email, :password, :hash)', $params);
				if ($res !== false)
				{
					$this->success[] = "You have successfully registered. An activation letter has been sent to you in the mail...";
					return $params['hash'];
				}
				else {
					$this->errors[] = "Server error!";
					return false;
				}
			}
			$this->errors[] = "This mail is already taken!";
			return false;
		}
		$this->errors[] = "You must have at least 7 characters in the password!";
		return false;
	}

	public function verify($info = [])
	{
		if (empty($info))
			return $this->errors[] = 'Hash not valid!';
		$params = [
			'verifyHash' => $info['hash'],
		];
		$res = $this->db->pquery('SELECT * FROM `users` WHERE `verify_hash` = :verifyHash', $params);
		if (empty($res))
			return $this->errors[] = 'Hash not valid!';
		if ($res[0]['verify'] == 0)
		{
			$setDate = $this->db->pquery('UPDATE `users` SET `verify` = 1, `groups` = "user_off" WHERE `verify_hash` = :verifyHash', $params);
			return $this->success[] = 'Accaunt activated!';
		}
		else
		{
			return $this->success[] = 'Accaunt alredy activated!';
		}
		return false;
	}

	public function Password($info = '')
	{
		if (!empty($info) && !empty($info['email']))
		{
			$res = $this->db->pquery('SELECT * FROM `users` WHERE `email` = :email', ['email' => $info['email']]);
			if (!empty($res))
			{
				$newToken = hash('sha256', $res[0]['email'].time());
				$res = $this->db->pquery("UPDATE `users` SET `verify_hash` = :new_token WHERE `email` = :email", ['new_token' => $newToken, 'email' => $info['email']]);
				$this->success[] = 'We sent an email to your mail with a link to reset your password!';
				return $newToken;
			}
			$this->errors[] = 'Account not found!';
			return false;
		}
		return false;
	}

	public function resetPassword($get, $post)
	{
		if (!empty($get) && $res = $this->db->pquery('SELECT * FROM `users` WHERE `verify_hash` = :hash', ['hash' => $get['hash']]))
		{
			if (!empty($post['submit']) && $post['submit'] == 'reset')
			{
				if (!empty($post['password']) && !empty($post['passwordConfirm']) && $post['password'] != $post['passwordConfirm'])
					$this->errors[] = 'Passwords do not match!';
				else if (!empty($post['password']) && strlen($post['password']) <= 6)	
					$this->errors[] = 'You must have at least 7 characters in the password!';
				$res = $this->db->pquery('UPDATE `users` SET `password` = :newPassword, `verify_hash` = :newHash WHERE `id` = :id', ['newPassword' => password_hash($post['password'], PASSWORD_DEFAULT), 'newHash' => hash('sha256', $res[0]['email'].time()), 'id' => $res[0]['id']]);
				$this->success[] = 'Password changed, redirect in 3 seconds...';
				$this->visible = 1;
				return true;
			}
		}
		else
		{
			$this->errors[] = 'Request not found...';
			$this->visible = 1;
		}
		return false;
	}

	public function saveSettings($info)
	{
		if (!empty($info))
		{
			$nickname = preg_replace('/\s+/', '', $info['nickname']);
			$name = preg_replace('/\s+/', '', $info['name']);
			$surname = preg_replace('/\s+/', '', $info['surname']);

			if (!empty($nickname) && !empty($name) && !empty($surname))
			{
				if (!empty($_FILES['userPhoto']['name'])) {
					$filename = $_FILES['userPhoto'];
					$allowType = ['jpeg', 'png'];
					$type = preg_split('/\//', $_FILES['userPhoto']['type']);

					$fileinfo = getimagesize($_FILES["userPhoto"]["tmp_name"]);
					if ($fileinfo[0] != 512 || $fileinfo[1] != 512)
					{
						$this->errors[] = 'Image should be size 512x512!';
						return false;
					}
					else if ($type[0] != 'image' || !in_array($type[1], $allowType))
					{
						$this->errors[] = 'Wrong image format, only jpeg/png!';
						return false;
					}
				}
				$updateInfo = array(
					'id' => $this->user['id'],
					'login' => $this->test_input($info['nickname']),
					'name' => $this->test_input($info['name']),
					'surname' => $this->test_input($info['surname']),
					'email_noty' => $this->test_input($info['email_noty']),
					'photo' => ''
				);
				if (!empty($fileinfo))
				{
					$getimg = file_get_contents($_FILES["userPhoto"]["tmp_name"]);
					$imgBase = base64_encode($getimg);
					file_put_contents("public/images/users/{$this->user['login']}.png", base64_decode($imgBase));
				}
				$updateInfo['photo'] = "/public/images/users/{$this->user['login']}.png";
				$res = $this->db->pquery('UPDATE `users` SET `login` = :login, `surname` = :surname, `name` = :name, `photo` = :photo, `email_noty` = :email_noty WHERE `id` = :id', $updateInfo);
				$this->success[] = 'Saved!';
				return ($res == []);
			}
			else
				$this->errors[] = 'All input must be filled!';
		}
		return false;
	}

}

?>