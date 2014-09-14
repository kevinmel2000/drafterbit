<?php namespace Drafterbit\Blog;

use Drafterbit\Framework\ModuleEvent;
use Drafterbit\Framework\Application;

class BlogModule extends \Drafterbit\Framework\Module {

	public function register(Application $app)
	{
		$app['helper']->register('blog', $this->getResourcesPath().'helpers/blog.php');
		$app['helper']->load('blog');
	}

	public function boot()
	{
		$ns = $this->getNamespace();
		$extensionClass = $ns.'\\Extensions\\TwigExtension';

		// this must be after path.theme registered
		if(class_exists($extensionClass)) {
			$this->app['twig']->addExtension( new $extensionClass);
		}
	}
}