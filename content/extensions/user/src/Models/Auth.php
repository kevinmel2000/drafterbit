<?php namespace Drafterbit\Extensions\User\Models;

use Drafterbit\Extensions\User\Auth\Exceptions\UserNotAuthorizedException;

class Auth extends \Drafterbit\Framework\Model
{

    public function __construct()
    {
        $this->user = $this->model('@user\User');
        $this->role = $this->model('@user\Role');
    }

    public function doLogin($login, $password)
    {
        if(filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $user = $this->user->getByEmail($login);
        } else {
            $user = $this->user->getByUserName($login);
        }

        if ( !$user or !password_verify($password, $user['password']) or ($user['status'] != 1)) {
            throw new \RuntimeException(__("Incorrect Username/Email or Password"));
        }
        
        $this->registerSession($user);
    }

    /**
     * Set all required session during app run
     *
     * @param  object $user
     * @return void
     */
    public function registerSession($user)
    {
        $encrypter = $this->get('encrypter');
        
        $userPermissions = $encrypter->encrypt(serialize($this->user->getPermissions($user['id'])));
        
        $session = $this->get('session');
        $data = array(
            'user.id' => $user['id'],
            'user.email' => $user['email'],
            'user.name' => $user['real_name'],
            'user.permissions' => $userPermissions,
            '_token' => sha1((string) microtime(true)),
        );

        foreach ($data as $key => $value) {
            $session->set($key, $value);
        }
    }

    public function authenticate($route, $request)
    {
        if ($this->isLoggedIn()) {
            return true;
        }

        $config = $this->get('config');

        if(!in_array(trim($route->getPath(), '/'), [$config['path.admin'].'/login', $config['path.admin'].'/do_login'])) {
            $next = urlencode(base_url($request->getPathInfo()));
            return redirect(admin_url("login?next=$next"))->send();
        }
    }

    /**
     * Restrict user to given access key
     *
     * @param  string $accessKey
     * @return void
     */
    public function restrict($accessKey)
    {        
        $encrypter = $this->get('encrypter');
        $session = $this->get('session');

        $perm = $this->get('app')->getPermissions();

        $permissions = array();
        foreach ($perm as $key => $value) {
            $permissions = array_merge($permissions, $value);
        }

        $userPermissions = unserialize($encrypter->decrypt($session->get('user.permissions')));

        if( !in_array($accessKey, $userPermissions)) {
            $label = $permissions[$accessKey];
            throw new UserNotAuthorizedException(
                "Sorry, you are not authorized to $label.
                Try logout and login again or please request access to administrator"
            );
        }

        return true;
    }

    /**
     * Check if there is a logged in user
     *
     * @return boolean
     */
    public function isLoggedIn()
    {
        return $this->get('session')->get('user.id');
    }
}