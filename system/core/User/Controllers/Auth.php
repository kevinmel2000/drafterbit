<?php namespace Drafterbit\User\Controllers;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Drafterbit\Admin\Auth\Exceptions\UserNotAuthorizedException;
use Drafterbit\Support\Controller as BaseController;
use Drafterbit\User\Models\User;
use Drafterbit\User\Models\UsersGroup;
use Drafterbit\User\Models\Auth as authModel;

class Auth extends BaseController {

	protected $placeBeforeLogin = '/';
	protected $authModel;

	public function __construct( User $user, UsersGroup $group, AuthModel $authModel)
	{
		$this->authModel = $authModel;
	}

	public function login()
	{
		if ($post = $this->get('input')->post()) {

			$remember = false;
			if(isset($postData['remember-me'])) {
				$remember = true;
			}

			$redirectAfterLogin = admin_url();
			if($this->get('input')->get('next')) {
				$redirectAfterLogin = urldecode($this->get('input')->get('next'));
			}

			try {
				
				$this->validate('login', $post);
				$this->authModel->doLogin($post['email'], $post['password'], $remember);
				
				return redirect($redirectAfterLogin);
			} catch (\Exception $e) {
				set('messages', [$e->getMessage()]);
			}
		}

		$this->get('asset')
			->css('@bootstrapcss')
			->css('@fontawesome', '@fontawesome')
			->css($this->assetPath('css/sign-in.css'))
			->js($this->assetPath('js/sign-in.js'));
		
		return view();
	}

	public function logout()
	{
		$session = $this->get('session');

		$userId = $session->get('uid');
		$userName = $session->get('user.name');
		
		$url = admin_url("user/edit/{$userId}");

		$session->clear();
		$session->invalidate();

		$response = new RedirectResponse(admin_url('login'));

		$cookies = $this->get('input')->cookies();
		
		foreach ($cookies as $key => $value) {
			$response->headers->clearCookie($key);
		}

		log_db("Logged out", "<a href='$url'>{$userName}</a>");

		return $response;
	}
}