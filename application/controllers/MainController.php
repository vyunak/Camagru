<?php

	namespace application\controllers;

	use application\core\Controller;

	class MainController extends Controller
	{
		public $status = [
			'errors' => [],
			'success' => [],
			'user' => NULL,
			'visible' => 0,
			'FToken' => NULL,
		];

		public function indexAction()
		{
			if (!empty($_POST) && !empty($_POST['submit']) && $_POST['submit'] == 'setupProfile')
			{
				$res = $this->model->setupProfile($_POST);
				if ($res)
				{
					$this->view->redirect("/?success={$this->model->test_input('Account setup was successful...')}");
				}
			}

			$this->status['gallery'] = $this->model->gallery($_GET);
			$this->status['errors'] = $this->model->errors;
			$this->status['success'] = $this->model->success;
			$this->status['user'] = $this->user;
			$this->status['FToken'] = $this->FToken;
			$this->view->render('Главная страница', $this->status);
		}

		public function aboutAction()
		{
			$this->status['FToken'] = $this->FToken;
			$this->view->render('About', $this->status);
		}

		public function webcamAction()
		{
			if (!empty($_POST) && $_POST['submit'] == 'loadPhoto')
			{
				$img = $this->model->loadImg($_POST);
				$this->status['videoimg'] = $img;
			}
			$this->status['errors'] = $this->model->errors;
			$this->status['success'] = $this->model->success;
			$this->status['user'] = $this->user;
			$this->status['FToken'] = $this->FToken;
			$this->view->render('WebCam', $this->status);
		}

	}
?>