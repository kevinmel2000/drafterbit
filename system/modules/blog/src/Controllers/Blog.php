<?php namespace Drafterbit\Blog\Controllers;

use Drafterbit\Blog\Models\Post;
use Drafterbit\Blog\Models\Tag;
use Drafterbit\Support\Controller as BaseController;

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
	