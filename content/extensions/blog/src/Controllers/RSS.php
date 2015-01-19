<?php namespace Drafterbit\Blog\Controllers;

use Drafterbit\Extensions\System\FrontendController;
use Symfony\Component\HttpFoundation\Response;

class RSS extends FrontendController
{

    public function index()
    {
        $system = $this->model('@system\System')->all();
        $data = array(
            'siteName' =>  $system['site.name'],
            'siteDesc' => $system['site.description']
        );

        $perPage = $this->model('@system\System')->fetch('post.per_page') ?: 0;

        $page = 1;
        $offset = ($page*$perPage)-$perPage;

        $nextOffset = (($page+1)*$perPage)-$perPage;

        $posts = $this->model('@blog\Post')->take($perPage, $offset);
        
        $nextPosts = $this->model('@blog\Post')->take($perPage, $nextOffset);

        $system = $this->model('@system\System')->all();

        foreach ($posts as &$post) {
            $date = date('Y/m', strtotime($post['created_at']));

            $post['date'] = $this->get('time')->parse($post['created_at'])->format($system['format.date']);

            $post['url'] = blog_url($date.'/'.$post['slug']);

            $post['excerpt'] = false;
            
            if (strpos($post['content'], '<!--more-->') !== false) {
                $post['excerpt'] = current(explode('<!--more-->', $post['content'])).'&hellip;';
            }

            $post['tags'] = $this->model('@blog\Post')->getTags($post['id']);
        }

        $data['posts'] = $posts;

        $content =  $this->get('template')->render('@blog/feed', $data);

        $response = new Response($content);
        $response->headers->set('Content-Type', 'application/xml');
        return $response;
    }
}
