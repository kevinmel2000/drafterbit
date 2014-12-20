<?php namespace Drafterbit\Blog\Controllers;

use Drafterbit\Extensions\System\FrontendController;

class Frontend extends FrontendController {

	public function index()
	{
		$posts = $this->model('@blog\Post')->all(['status' => 'published']);
		set('posts', $posts);

		foreach ($posts as &$post) {

			$date = date('Y/m', strtotime($post->created_at));

			$post->url = base_url('blog/'.$date.'/'.$post->slug);

			$post->excerpt = current(explode('<!--more-->', $post->content));
			$post->excerpt .= '&hellip; <a href="'.$post->url.'" />Read more </a></p>';
		}

		return $this->render('blog/index', $this->data);
	}

	public function view($yyyy = null, $mm = null, $slug = null)
	{
		$post = $this->model('@blog\Post')->getSingleBy('slug', $slug);
		
		if(!$post) {
			show_404();
		}

		$post->tags = $this->model('@blog\Tag')->getByPost($post->id);

		set('post', $post);
		return $this->render('blog/view', $this->data);
	}
}