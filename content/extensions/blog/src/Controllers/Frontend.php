<?php namespace Drafterbit\Blog\Controllers;

use Drafterbit\Extensions\System\FrontendController;

class Frontend extends FrontendController {

	public function index($page = 1)
	{
		$perPage = 5;
		$offset = ($page*$perPage)-$perPage;

		$nextOffset = (($page+1)*$perPage)-$perPage;

		$posts = $this->model('@blog\Post')->take($perPage, $offset) or show_404();
		$nextPosts = $this->model('@blog\Post')->take($perPage, $nextOffset);

		foreach ($posts as &$post) {

			$date = date('Y/m', strtotime($post['created_at']));

			$post['date'] = $this->get('time')->parse($post['created_at'])->format('d F Y');

			$post['url'] = blog_url($date.'/'.$post['slug']);

			$post['excerpt'] = false;
			
			if(strpos($post['content'], '<!--more-->') !== false) {
				$post['excerpt'] = current(explode('<!--more-->', $post['content'])).'&hellip;';
			}

			$post['tags'] = $this->model('@blog\Post')->getTags($post['id']);
		}

		$data['posts'] = $posts;

		$data['prev_link'] = false;
		$data['next_link'] = false;

		if($page > 1) {
			$data['prev_link'] = blog_url('page/'.($page-1));
		}

		if($nextPosts) {
			$data['next_link'] = blog_url('page/'.($page+1));
		}

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

	public function tag($slug) {

		$tag = $this->model('@blog\Tag')->getSingleBy('slug', $slug); 
		$posts = $this->model('@blog\Tag')->getPosts($tag['id']);
		

		// @todo :(
		$data['prev_link'] = false;
		$data['next_link'] = false;
		
		foreach ($posts as &$post) {

			$date = date('Y/m', strtotime($post['created_at']));

			$post['date'] = $this->get('time')->parse($post['created_at'])->format('d F Y');

			$post['url'] = blog_url($date.'/'.$post['slug']);

			$post['excerpt'] = false;
			
			if(strpos($post['content'], '<!--more-->') !== false) {
				$post['excerpt'] = current(explode('<!--more-->', $post['content'])).'&hellip;';
			}

			$post['tags'] = $this->model('@blog\Post')->getTags($post['id']);
		}

		$data['tag'] = $tag;
		$data['posts'] = $posts;
		return $this->render('blog/tag/index', $data);
	}
}