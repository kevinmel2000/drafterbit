<?php namespace Drafterbit\Extensions\User\Controllers;

use Drafterbit\Framework\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Auth extends Controller {

    public function login()
    {
        $data = array();
        $this->get('helper')->load('form');

        return $this->render('@user/auth/login', $data);
    }

    public function doLogin()
    {
        $redirectAfterLogin = admin_url();

        if($next = $this->get('input')->get('next')) {
            $redirectAfterLogin = urldecode($next);
        }

        try {

            $post = $this->get('input')->post();

            $this->model('@user\Auth')->doLogin($post['login'], $post['password']);

            $userId = $this->get('session')->get('user.id');
            $userName = $this->get('session')->get('user.name');

            $url = admin_url("user/edit/$userId");

            $response = ['next' => $redirectAfterLogin];

        } catch (\Exception $e) {
            $response = ['error' => 1, 'message' => $e->getMessage()];
        }

        return $this->jsonResponse($response);
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

        return $response;
    }
}