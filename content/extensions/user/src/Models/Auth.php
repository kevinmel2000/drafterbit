<?php namespace Drafterbit\Extensions\User\Models;

use Drafterbit\Extensions\User\Auth\Exceptions\UserNotAuthorizedException;
use Drafterbit\Extensions\User\Models\User;
use Drafterbit\Extensions\User\Models\UsersGroup;

class Auth extends \Drafterbit\Framework\Model {

	public function __construct( User $user, UsersGroup $group)
	{
		$this->user = $user;
		$this->group = $group;
	}

	public function doLogin($email, $password, $remember = false)
	{
		$user = $this->user->getByEmail($email);

		// if no user got by email, we return to.
		if ( ! $user) {
			throw new \RuntimeException("Email is not registered yet");
		}

		// yes, it's password.
		if( ! $this->verifyPassword( $password, $user->password)) {
			throw new \RuntimeException("Password is not valid.");
		}

		// everything is fine ,lets register session
		$this->registerSession($user);

		// pending
		/*
		if ($remember) {
			$this->rememberUser($user);
		}*/
	}

	/**
	 * Set all required session during app run
	 *
	 * @param object $user
	 * @return void
	 */
	public function registerSession($user)
	{
		$encrypter = $this->get('encrypter');

		//$permissions = $encrypter->encrypt(serialize($this->getPermissions()));
		$userPermissions = $encrypter->encrypt(serialize($this->user->getPermissions($user->id)));
		
		$session = $this->get('session');
		$data = array(
			'user.id' => $user->id,
			'user.email' => $user->email,
			'user.name' => $user->real_name,
			'user.permissions' => $userPermissions,
			'_token' => sha1( (string) microtime(true)),
		);

		foreach ( $data as $key => $value) {
			$session->set($key, $value);
		}
	}

	public function authenticate($route)
	{
		if ($this->isLoggedIn()) {
			return;
		}

		$config = $this->get('config');

		if(trim($route->getPath(), '/') != $config['path.admin'].'/login') {

			if ($this->isRemembered()) {

				$logToken = $this->get('input')->cookies('log_token');
				$user = $this->user->getBy('log_token', $logToken);

				if ($user) {
					$this->registerSession($user);
					return;
				}
			}

			$next = urlencode($this->get('request')->getPathInfo());
			return redirect(admin_url("login?next=$next"))->send();
		}
	}

	/**
	 * Restrict user to given access key
	 *
	 * @param string $accessKey
	 * @return void
	 */
	public function restrict($accessKey)
	{
		// @todo authenticete first
		
		$encrypter = $this->get('encrypter');
		$session = $this->get('session');

		$permissions = $this->getPermissions();
		$userPermissions = unserialize($encrypter->decrypt($session->get('user.permissions')));

		try {
			if( !in_array($accessKey, $userPermissions)) {
				$label = $permissions[$accessKey];
				throw new UserNotAuthorizedException("Sorry, you are not authorized to $label.");
			}
		} catch( UserNotAuthorizedException $e ) {
			$referer = $this->get('input')->headers('referer');

			if(!$referer) {
				$referer = base_url();
			}
			
			$msg['text'] = $e->getMessage();
			$msg['type'] = 'error';

			$session->getFlashBag()->set('message', $msg);
			return redirect($referer)->send();
		}

		return true;
	}

	/**
	 * Get all permissions.
	 *
	 * @return array
	 */
	private function getPermissions()
	{
		return $this->group->cachedQuery('permissions', 'getPermissions');
	}

	public function isLoggedIn()
	{
		return $this->get('session')->get('user.id');
	}

	public function isRemembered()
	{
		return ! is_null($this->get('input')->cookies('log_token'));
	}

	private function rememberUser($user)
	{
		$logToken = str_random(32);
		$this->user->update(array('log_token' => '\''.$logToken.'\''), array('id' => $user->id));
		set_cookie('log_token', $logToken, 2628000);
	}

	private function verifyPassword($password, $hash)
	{
		return password_verify($password, $hash);
	}
}