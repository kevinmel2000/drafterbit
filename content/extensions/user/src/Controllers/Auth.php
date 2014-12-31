<?php namespace Drafterbit\Extensions\User\Controllers;

use Drafterbit\Framework\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Auth extends Controller {

	protected $placeBeforeLogin = '/';

	public function login()
	{
		$data = array();
		$this->get('helper')->load('form');

		if ($post = $this->get('input')->post()) {

			$remember = false;
			if(isset($post['remember-me'])) {
				$remember = true;
			}

			$redirectAfterLogin = admin_url('system/dashboard');
			if($this->get('input')->get('next')) {
				$redirectAfterLogin = urldecode($this->get('input')->get('next'));
			}

			try {

				$this->model('@user\Auth')->doLogin($post['login'], $post['password'], $remember);

				$userId = $this->get('session')->get('user.id');
				$userName = $this->get('session')->get('user.name');

				$url = admin_url("user/edit/$userId");
				log_activity("logged in");
				
				return redirect($redirectAfterLogin);
			} catch (\Exception $e) {
				$data['messages'] = [$e->getMessage()];
			}
		}

		return $this->render('@user/auth/login', $data);
	}

	public function logout()
	{
		$session = $this->get('session');
		$userId = $session->get('user.id');
		$userName = $session->get('user.name');
		
		$session->clear();
		$session->invalidate();

		$cookies = $this->get('input')->cookies();
		
		$response = new RedirectResponse(admin_url('login?logged_out=1'));
		foreach ($cookies as $key => $value) {
			$response->headers->clearCookie($key);
		}

		log_activity("logged out", array('user_id' => $userId, 'user_name' => $userName));

		return $response;
	}
}