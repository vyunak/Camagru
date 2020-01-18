<?php

	namespace application\controllers;

	use application\core\Controller;

	class AccountController extends Controller
	{
		public $status = [
			'errors' => [],
			'success' => [],
			'visible' => 0,
			'info' => NULL,
			'FToken' => NULL,
		];

		public function loginAction()
		{
			if (!empty($_POST) && $_POST['submit'] == 'login')
			{
				$res = $this->model->login($_POST);
				if ($res !== false)
				{
					$_SESSION['hash'] = $res;
					$this->view->redirect('/');
				}
			}
			$this->status['FToken'] = $this->FToken;
			$this->status['errors'] = $this->model->errors;
			$this->status['success'] = $this->model->success;
			$this->view->render('Вход', $this->status);
		}

		public function registerAction()
		{
			if (!empty($_POST) && $_POST['submit'] == 'register')
			{
				if (($token = $this->model->register($_POST)) != false)
				{
					$this->email($_POST['email'], $token, 'Email Verifications', 'verify');
				}
			}
			$this->status['FToken'] = $this->FToken;
			$this->status['errors'] = $this->model->errors;
			$this->status['success'] = $this->model->success;
			$this->view->render('Регистрация', $this->status);
		}

		public function logoutAction()
		{
			unset($_SESSION['hash']);
			$this->view->redirect("/?success={$this->model->test_input('Bye Bye...')}");
		}

		public function verifyAction()
		{
			$res = $this->model->verify($_GET);
			$this->status['FToken'] = $this->FToken;
			$this->status['errors'] = $this->model->errors;
			$this->status['success'] = $this->model->success;
			$this->view->render('Verify Account', $this->status);
		}

		public function passwordAction()
		{
			if (($token = $this->model->Password($_POST)) != false)
				$this->email($_POST['email'], $token, 'Reset Password', 'account/reset');
			$this->status['FToken'] = $this->FToken;
			$this->status['errors'] = $this->model->errors;
			$this->status['success'] = $this->model->success;
			$this->view->render('Reset Password', $this->status);
		}

		public function resetAction()
		{
			if ($this->model->resetPassword($_GET, $_POST))
				$this->view->redirect("/?success={$this->model->test_input('Your password changed...')}");
			$this->status['FToken'] = $this->FToken;
			$this->status['errors'] = $this->model->errors;
			$this->status['success'] = $this->model->success;
			$this->status['visible'] = $this->model->visible;
			$this->status['user'] = $this->user;
			$this->view->render('Reset Password', $this->status);
		}

		public function settingsAction()
		{
			if (!empty($_POST) && !empty($_POST['settings']) && $_POST['settings'] == 'saveSettings')
			{
				if ($this->model->saveSettings($_POST))
				{
					$this->view->redirect("/settings?success={$this->model->test_input('Settings saved...')}");
				}
			}
			else if (!empty($_POST) && !empty($_POST['settings']) && $_POST['settings'] == 'changePassword')
			{
				if (($token = $this->model->Password($this->user)) != false)
					$this->email($this->user['email'], $token, 'Reset password', 'account/reset');
			}
			$this->status['FToken'] = $this->FToken;
			$this->status['user'] = $this->user;
			$this->status['errors'] = $this->model->errors;
			$this->status['success'] = $this->model->success;
			$this->status['visible'] = $this->model->visible;
			$this->view->render('Settings', $this->status);
		}

	}
?>