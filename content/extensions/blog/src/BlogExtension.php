<?php namespace Drafterbit\Blog;

use Drafterbit\Framework\ExtensionEvent;
use Drafterbit\Framework\Application;

class BlogExtension extends \Drafterbit\Framework\Extension {

	public function register(Application $app)
	{
		$this['helper']->register('blog', $this->getResourcesPath().'helpers/blog.php');
		$this['helper']->load('blog');
	}

	public function boot()
	{
		$ns = $this->getNamespace();
		$extensionClass = $ns.'\\Extensions\\TwigExtension';

		// this must be after path.theme registered
		if(class_exists($extensionClass)) {
			$this['twig']->addExtension( new $extensionClass);
		}

		$this->app->addFrontPageOption(['blog' => 'Blog']);
	}
}