<?php namespace Drafterbit\Extensions\User\Controllers;

use Drafterbit\Extensions\System\FrontendController;

class Frontend extends FrontendController
{
    
    public function view($username)
    {
        $user = $this->model('@user\User')->getByUserName($username) or show_404();

        $data['user'] = $user;
        return $this->render('user/view', $data);
    }
}