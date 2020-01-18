<?php

namespace application\models;

use application\core\Model;

class Profile extends Model
{
	public function loadProfile($info)
	{
		$user = $this->db->pquery('SELECT `id`, `login`, `surname`, `name`, `photo`, `register_at` FROM `users` WHERE `id` = :id', ['id' => $info]);
		if ($user != false)
		{
			// $photo = $this->db->query('SELECT * FROM `gallery` LEFT JOIN `comments` ON gallery.id = comments.id_photo GROUP BY `comment`');
			if (empty($_GET['page']) || !is_numeric($_GET['page']))
				$_GET['page'] = 1;
			$index = ($_GET['page'] - 1) * 8;
			$photo = $this->db->pquery("SELECT * FROM `gallery` WHERE `owner` = :id ORDER BY `create_at` DESC LIMIT 8 OFFSET {$index}", ['id' => $info]);
			foreach ($photo as $key => $value) {
				$photo[$key]['commentsCount'] = $this->db->pquery('SELECT COUNT(`id`) AS "commentsCount" FROM `comments` WHERE `id_photo` = :id', ['id' => $value['id']])[0]['commentsCount'];
				$photo[$key]['likeCount'] = $this->db->pquery('SELECT COUNT(`id`) AS "likeCount" FROM `likes` WHERE `id_photo` = :id', ['id' => $value['id']])[0]['likeCount'];
			}
			$user[0]["post_count"] = $this->db->pquery("SELECT COUNT(`id`) AS \"post_count\" FROM `gallery` WHERE `owner` = :id", ['id' => $info])[0]["post_count"];
			return [$user, $photo];
		}
		return false;
	}

	public function loadPhoto($info)
	{
		$photo = $this->db->pquery("SELECT * FROM `gallery` WHERE `id` = :id", ['id' => $info])[0];
		if (!empty($photo))
		{
			$photo['owner'] = $this->db->pquery("SELECT `id`, `login`, `surname`, `name`, `photo` FROM `users` WHERE `id` = :id", ['id' => $photo['owner']])[0];
			$photo['allComments'] = $this->db->pquery('SELECT `id`, `id_author`, `comment`, `create_at` FROM `comments` WHERE `id_photo` = :id', ['id' => $photo['id']]);
			foreach ($photo['allComments'] as $key => $value) {
				$photo['allComments'][$key]['id_author'] = $this->db->pquery("SELECT `id`, `login`, `photo` FROM `users` WHERE `id` = :id", ['id' => $value['id_author']])[0];
			}
			$photo['allLike'] = $this->db->pquery('SELECT `id_author` FROM `likes` WHERE `id_photo` = :id AND `active` = 1', ['id' => $photo['id']]);
			foreach ($photo['allLike'] as $key => $value) {
				$photo['allLike'][$key]['id_author'] = $this->db->pquery("SELECT `id`, `login`, `photo` FROM `users` WHERE `id` = :id", ['id' => $value['id_author']])[0];
			}
			$photo['likeCount'] = count($photo['allLike']);
			$photo['commentCount'] = count($photo['allComments']);
			return $photo;
		}
		return false;
	}

	public function newPost($info = '')
	{
		if (!empty($info['userPhoto']))
		{
			$imageInfo = explode(";base64,", $info['userPhoto']);
			$imgExt = str_replace('data:image/', '', $imageInfo[0]);      
			$image = str_replace(' ', '+', $imageInfo[1]);
			$pathPhoto = "public/images/cams/{$this->user['login']}-".time().".png";
			file_put_contents($pathPhoto, base64_decode($image));
			$res = $this->db->pquery("INSERT INTO `gallery` (`owner`, `photo`) VALUES(:owner, :photo)", ['owner' => $this->user['id'], 'photo' => $pathPhoto]);
			return ($res == []);
		}
		return false;
	}

	public function deletePost($info = '')
	{
		if (!empty($info['deletePhoto']))
		{
			if ($res = $this->db->pquery("SELECT `photo` FROM `gallery` WHERE `id` = :id", ['id' => $info['deletePhoto']]))
			{
				$res3 = $this->db->pquery("DELETE FROM `comments` WHERE `id_photo` = :id", ['id' => $info['deletePhoto']]);
				$res2 = $this->db->pquery("DELETE FROM `gallery` WHERE `id` = :id", ['id' => $info['deletePhoto']]);
				if (file_exists($res[0]['photo']))
					unlink($res[0]['photo']);
				return ($res2 == [] && $res3 == []);
			}
		}
		return false;
	}

	public function newComment($info = '')
	{
		if (!empty($info['id']))
		{
			$params = [
				'id_photo' => $info['id'],
				'id_author' => $this->user['id'],
				'comment' => $this->test_input($info['comment'])
			];
			$res = $this->db->pquery("INSERT INTO `comments` (`id_photo`, `id_author`, `comment`) VALUES(:id_photo, :id_author, :comment)", $params);
			$photo_autor = $this->db->pquery("SELECT `owner` FROM `gallery` WHERE `id` = :id", ['id' => $info['id']])[0];
			if ($photo_autor['owner'] != $this->user['id'])
			{
				$email = $this->db->pquery("SELECT `email`, `email_noty` FROM `users` WHERE `id` = :id", ['id' => $photo_autor['owner']])[0];
				if ($email['email_noty'] == 1)
					$this->email($email['email'], $this->user['login'], 'left a comment under', $info['id'], 'NEW COMMENTS');
			}
			return ($res == []);
		}
		return false;
	}

	public function deleteComment($info = '')
	{
		if (!empty($info['deleteComment']))
		{
			$comment = $this->db->pquery("SELECT * FROM `comments` WHERE `id` = :id", ['id' => $info['deleteComment']]);
			if (!empty($comment))
			{
				$post = $this->db->pquery("SELECT * FROM `gallery` WHERE `id` = :id", ['id' => $comment[0]['id_photo']]);
				if ($post[0]['owner'] == $this->user['id'] || $comment[0]['id_author'] == $this->user['id'])
				{
					$res = $this->db->pquery("DELETE FROM `comments` WHERE `id` = :id", ['id' => $info['deleteComment']]);
					return ($res == []);
				}
			}
		}
		return false;
	}

	public function likePost($info = '')
	{
		if (!empty($info['like']))
		{
			$params = [
				'id_photo' => $info['like'],
				'id_author' => $this->user['id']
			];
			$res0 = $this->db->pquery("SELECT * FROM `likes` WHERE `id_author` = :id_author AND `id_photo` = :id_photo", $params);
			if (!empty($res0))
			{
				$params = [
					'id' => $res0[0]['id'],
					'active' => ($res0[0]['active'] == 0) ? 1 : 0
				];
				if ($params['active'] == 1)
					$res2 = $this->db->pquery("UPDATE `gallery` SET `likes` = `likes` + 1 WHERE `id` = :id", ['id' => $info['like']]);
				else
					$res2 = $this->db->pquery("UPDATE `gallery` SET `likes` = `likes` - 1 WHERE `id` = :id", ['id' => $info['like']]);
				$res = $this->db->pquery("UPDATE `likes` SET `active` = :active WHERE `id` = :id", $params);
				return ($res == [] && $res2 == []);
			}
			else
			{
				$photo_autor = $this->db->pquery("SELECT `owner` FROM `gallery` WHERE `id` = :id", ['id' => $info['like']])[0];
				if ($photo_autor['owner'] != $this->user['id'])
				{
					$email = $this->db->pquery("SELECT `email`, `email_noty` FROM `users` WHERE `id` = :id", ['id' => $photo_autor['owner']])[0];
					if ($email['email_noty'] == 1)
						$this->email($email['email'], $this->user['login'], 'liked', $info['like'], 'NEW LIKE');
				}
				$params['active'] = 1;
				$res = $this->db->pquery("INSERT INTO `likes` (`id_photo`, `id_author`, `active`) VALUES(:id_photo, :id_author, :active)", $params);
				$res2 = $this->db->pquery("UPDATE `gallery` SET `likes` = `likes` + 1 WHERE `id` = :id", ['id' => $info['like']]);
				return ($res == [] && $res2 == []);
			}
		}
		return false;
	}
	
}

?>