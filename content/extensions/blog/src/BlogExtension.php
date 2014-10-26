<?php namespace Drafterbit\Blog;

use Drafterbit\Framework\ExtensionEvent;
use Drafterbit\Framework\Application;

class BlogExtension extends \Drafterbit\Framework\Extension {

	public function boot()
	{
		$this['helper']->register('blog', $this->getResourcesPath('helpers/blog.php'));
		$this['helper']->load('blog');

		$ns = $this->getNamespace();
		$extensionClass = $ns.'\\Extensions\\TwigExtension';

		// this must be after path.theme registered
		if(class_exists($extensionClass)) {
			$this['twig']->addExtension( new $extensionClass);
		}

		$this->getApplication()->addFrontPageOption(['blog' => [
			'label' => 'Blog',
			'controller' => '@blog\Blog::index',
			'defaults' => array()
			]
		]);
	}

	public function getComments($id)
	{
		$model = $this->model('@blog\Comment');

		$comments = $model->getByPostId($id);

		return $comments;
	}

	function getSearchQuery()
	{
		$query = $this['db']->createQueryBuilder()
			->select('*')
			->from('#_posts', 'p')
			->where("p.title like :q")
			->orWhere("p.content like :q");

		return array('blog', $query);
	}

	function getShortcuts()
	{
		return [
			['href' => admin_url('blog/create'), 'icon' => 'fa fa-edit','label' => 'New Post']
		];
	}
}