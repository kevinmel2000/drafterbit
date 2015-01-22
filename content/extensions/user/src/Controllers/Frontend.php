<?php namespace Drafterbit\Extensions\User\Controllers;

use Drafterbit\Extensions\System\FrontendController;

class Frontend extends FrontendController
{   
    public function view($username)
    {
        $user = $this->model('@user\User')->getByUserName($username) or show_404();

        $posts = $this->model('@blog\Post')->all(['status' => 'untrashed', 'user_id' => $user['id']]);

        // @todo Move this to blog extension
        // @todo :(
        $data['prev_link'] = false;
        $data['next_link'] = false;
        
        foreach ($posts as &$post) {
            $date = date('Y/m', strtotime($post['created_at']));

            $post['date'] = $this->get('time')->parse($post['created_at'])->format('d F Y');

            $post['url'] = blog_url($date.'/'.$post['slug']);

            $post['excerpt'] = false;
            
            if (strpos($post['content'], '<!--more-->') !== false) {
                $post['excerpt'] = current(explode('<!--more-->', $post['content'])).'&hellip;';
            }

            $post['tags'] = $this->model('@blog\Post')->getTags($post['id']);
        }

        $data['posts'] = $posts;
        $data['user'] = $user;
        return $this->render('user/view', $data);
    }
}
