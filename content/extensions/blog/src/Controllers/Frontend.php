<?php namespace Drafterbit\Blog\Controllers;

use Drafterbit\Extensions\System\FrontendController;

class Frontend extends FrontendController {

	public function index($page = 1)
	{
		$posts = $this->model('@blog\Post')->all(['status' => 'published']);

		foreach ($posts as &$post) {

			$date = date('Y/m', strtotime($post['created_at']));

			$post['url'] = blog_url($date.'/'.$post['slug']);

			$post['excerpt'] = current(explode('<!--more-->', $post['content']));
			$post['excerpt'] .= '&hellip; <a href="'.$post['url'].'" />Read more </a></p>';
		}

		$data['posts'] = $posts;

		$data['prev_link'] = false;
		if($page > 1) {
			$data['prev_link'] = blog_url('page/'.($page-1));
		}

		$data['next_link'] = blog_url('page/'.($page+1));
		return $this->render('blog/index', $data);
	}

	public function view($yyyy = null, $mm = null, $slug = null)
	{
		$post = $this->model('@blog\Post')->getSingleBy('slug', $slug);

		if(!$post) {
			show_404();
		}

		$post['tags'] = $this->model('@blog\Tag')->getByPost($post['id']);

		$data['post'] = $post;
		return $this->render('blog/view', $data);
	}
}