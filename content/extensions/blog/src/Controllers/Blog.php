<?php namespace Drafterbit\Blog\Controllers;

use Drafterbit\Blog\Models\Post;
use Drafterbit\Blog\Models\Tag;
use Drafterbit\Extensions\System\Controller as BaseController;

class Blog extends BaseController {

	protected $post;
	protected $tag;

	public function __construct(Post $post, Tag $tag)
	{
		$this->post = $post;
		$this->tag = $tag;
	}

	public function index()
	{
		$posts = $this->post->all();
		set('posts', $posts);

		foreach ($posts as &$post) {

			$date = date('Y/m', strtotime($post->created_at));

			$post->url = base_url('blog/'.$date.'/'.$post->slug);

			$post->excerpt = current(explode('<!--more-->', $post->content));
			$post->excerpt .= '&hellip; <a href="'.$post->url.'" />Read more </a></p>';
		}

		return $this->get('twig')->render('blog/index.html', $this->data);
	}

	public function view($yyyy = null, $mm = null, $slug = null)
	{
		$post = $this->post->getSingleBy('slug', $slug);
		
		if(!$post) {
			show_404();
		}

		$post->tags = $this->tag->getByPost($post->id);

		set('post', $post);
		return $this->get('twig')->render('blog/view.html', $this->data);
	}

	public function tag($tag)
	{
		return 'UNDER CONSTRUCTION';
	}
}
	