<?php

	namespace application\controllers;

	use application\core\Controller;

	class ProfileController extends Controller
	{
		public $status = [
			'errors' => [],
			'success' => [],
			'user' => NULL,
			'visible' => 0,
			'info' => NULL,
			'FToken' => NULL,
		];

		public function newPostAction()
		{
			if ($this->model->newPost($_POST))
				$this->view->redirect("/profile/{$this->user['id']}?success={$this->model->test_input('Photo added in your profile!')}");
			else
				$this->view->redirect("{$this->urlFormater()}?errors={$this->model->test_input('Internal Server Error!')}");
		}

		public function deletePostAction()
		{
			if ($this->model->deletePost($_POST))
				$this->view->redirect("/profile/{$this->user['id']}?success={$this->model->test_input('Photo has been deleted...')}");
			else
				$this->view->redirect("{$this->urlFormater()}?errors={$this->model->test_input('Internal Server Error!')}");
		}

		public function newCommentAction()
		{
			if ($this->model->newComment($_POST))
				$this->view->redirect("{$this->urlFormater()}?success={$this->model->test_input('Yippee, comment added!')}");
			else
				$this->view->redirect("{$this->urlFormater()}?errors={$this->model->test_input('Internal Server Error!')}");
		}

		public function deleteCommentAction()
		{
			if ($this->model->deleteComment($_POST))
				$this->view->redirect("{$this->urlFormater()}?success={$this->model->test_input('Comment has been deleted...')}");
			else
				$this->view->redirect("{$this->urlFormater()}?errors={$this->model->test_input('Internal Server Error!')}");
		}

		public function likePostAction()
		{
			if (($id = $this->model->likePost($_POST)) != false)
				$this->view->redirect("{$this->urlFormater()}");
			else
				$this->view->redirect("{$this->urlFormater()}?errors={$this->model->test_input('Internal Server Error!')}");
		}

		public function profileAction()
		{
			$this->view->redirect("/profile/{$this->user['id']}");
		}

		public function profileIdAction()
		{
			$info = $this->model->loadProfile($this->matches[2]);
			if (empty($info))
				$this->view->errorCode(404);
			$this->status['errors'] = $this->model->errors;
			$this->status['success'] = $this->model->success;
			$this->status['user'] = $this->user;
			$this->status['info'] = $info;
			$this->status['FToken'] = $this->FToken;
			$this->view->render("Profile {$this->matches[2]}", $this->status);
		}

		public function photoIdAction()
		{
			$info = $this->model->loadPhoto($this->matches[2]);
			if (empty($info))
				$this->view->errorCode(404);
			$this->status['errors'] = $this->model->errors;
			$this->status['success'] = $this->model->success;
			$this->status['user'] = $this->user;
			$this->status['info'] = $info;
			$this->status['FToken'] = $this->FToken;
			$this->view->render("Photo {$this->matches[2]}", $this->status);
		}

	}

?>