<?php

namespace application\models;

use application\core\Model;

class Main extends Model
{
	public function setupProfile($info)
	{
		if (!empty($info))
		{
			$nickname = preg_replace('/\s+/', '', $this->test_input($info['nickname']));
			$name = preg_replace('/\s+/', '', $this->test_input($info['name']));
			$surname = preg_replace('/\s+/', '', $this->test_input($info['surname']));


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
				if (!empty($fileinfo))
				{
					$getimg = file_get_contents($_FILES["userPhoto"]["tmp_name"]);
					$imgBase = base64_encode($getimg);
				}
				else
				{
					$getimg = file_get_contents("public/images/standertPhoto/{$info['standartPhoto']}.png");
					$imgBase = base64_encode($getimg);	
				}
				$updateInfo = array(
					'id' => $this->user['id'],
					'login' => $info['nickname'],
					'name' => $info['name'],
					'surname' => $info['surname'],
					'photo' => "/public/images/users/{$info['nickname']}.png"
				);
				file_put_contents("public/images/users/{$info['nickname']}.png", base64_decode($imgBase));
				$res = $this->db->pquery('UPDATE `users` SET `login` = :login, `groups` = "user", `surname` = :surname, `name` = :name, `photo` = :photo WHERE `id` = :id', $updateInfo);
				return ($res == []);
			}
			else
				$this->errors[] = 'All input must be filled!';
		}
		return false;
	}

	public function loadImg($info)
	{
		if (!empty($_FILES['userPhoto']['name'])) {
			$filename = $_FILES['userPhoto'];
			$allowType = ['jpeg', 'png'];
			$type = preg_split('/\//', $_FILES['userPhoto']['type']);
			if (in_array($type[1], $allowType) && file_exists($_FILES["userPhoto"]["tmp_name"]))
			{
				$getimg = file_get_contents($_FILES["userPhoto"]["tmp_name"]);
				$imgBase = base64_encode($getimg);
				return $imgBase;
			}
		}
		return false;
	}

	public function gallery($info)
	{
		if (empty($info['page']) || !is_numeric($info['page']))
			$info['page'] = 1;
		// $params = [
		// 	'start' => ,
		// ];
					// 'ends' => (($info['page'] - 1) * 12) + 12
		$index = ($info['page'] - 1) * 12;
		$res = $this->db->query("SELECT * FROM `gallery` ORDER BY `id` DESC LIMIT 12 OFFSET {$index}");
		foreach ($res as $key => $value) {
			$comments = $this->db->pquery('SELECT count(`id`) AS "comment_count" FROM `comments` WHERE `id_photo` = :id', ['id' => $value['id']])[0];
			$author = $this->db->pquery('SELECT `id`, `login`, `photo` FROM `users` WHERE `id` = :id', ['id' => $value['owner']])[0];
			$res[$key]['author'] = $author;
			$res[$key]['comments_count'] = $comments['comment_count'];
		}
		$count = $this->db->query("SELECT COUNT(`id`) AS \"post_count\" FROM `gallery`")[0];
		return ([$res, $count]);
	}

}

?>