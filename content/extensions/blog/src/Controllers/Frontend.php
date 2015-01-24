<?php namespace Drafterbit\Blog\Controllers;

use Drafterbit\Extensions\System\FrontendController;
use Symfony\Component\HttpFoundation\Response;

class Frontend extends FrontendController
{
    public function index()
    {
        $page = $this->get('input')->get('p') or $page = 1;

        $posts = $this->getFormattedPostList($page);

        $data['posts'] = $posts;

        $data = array_merge($data, $this->getNav($page));

        return $this->render('blog/index', $data);
    }

    public function view($yyyy = null, $mm = null, $slug = null)
    {
        $post = $this->model('@blog\Post')->getSingleBy('slug', $slug) or show_404();

        $post['date'] = $this->get('time')->parse($post['created_at'])->format('d F Y');

        $post['tags'] = $this->model('@blog\Tag')->getByPost($post['id']);

        $data['post'] = $post;
        return $this->render('blog/view', $data);
    }

    public function tag($slug)
    {
        $page = $this->get('input')->get('p') or $page = 1;
        $filter = ['tag' => $slug];

        $posts = $this->getFormattedPostList($page, $filter);

        $data['posts'] = $posts;
        $data = array_merge($data, $this->getNav($page, $filter));

        $tag = $this->model('Tag')->getSingleBy('slug', $slug);
        $data['tag'] = $tag;
        
        return $this->render('blog/tag/index', $data);
    }

    public function author($username)
    {
        $page = $this->get('input')->get('p') or $page = 1;
        $filter = ['username' => $username];

        $user = $this->model('@user\User')->getByUserName($username) or show_404();

        $posts = $this->getFormattedPostList($page, $filter);

        $data['posts'] = $posts;
        $data['user'] = $user;
        $data = array_merge($data, $this->getNav($page, $filter));

        return $this->render('user/view', $data);
    }

    public function feed()
    {
        $page = $this->get('input')->get('p') or $page = 1;

        $system = $this->model('@system\System')->all();
        $data = array(
            'siteName' =>  $system['site.name'],
            'siteDesc' => $system['site.description']
        );

        $posts = $this->getFormattedPostList($page);

        $data['posts'] = $posts;

        $content =  $this->get('template')->render('@blog/feed', $data);

        $response = new Response($content);
        $response->headers->set('Content-Type', 'application/xml');
        return $response;
    }

    private function format($posts)
    {
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

        return $posts;
    }

    private function getPostList($page, $filter = array())
    {
        $perPage = $this->model('@system\System')->fetch('post.per_page') ?: 5;
        
        $offset = ($page*$perPage)-$perPage;

        return $this->model('@blog\Post')->take($perPage, $offset, $filter);
    }

    private function getFormattedPostList($page, $filter = array())
    {
        $posts = $this->getPostList($page, $filter);

        return $this->format($posts);
    }

    private function hasNextPage($page, $filter = array())
    {
        return (boolean) count($this->getPostList($page+1, $filter));
    }

    private function getNav($page, $filter = array())
    {
        $data['prev_link'] = false;
        $data['next_link'] = false;

        if ($page > 1) {
            if($page == 2) {
                $data['prev_link'] = current_url();
            } else {
                $data['prev_link'] = current_url().'?p='.($page-1);
            }
        }

        if ($this->hasNextPage($page, $filter)) {
            $data['next_link'] = current_url().'?p='.($page+1);
        }

        return $data;
    }
}