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

		$this->getApplication()->addFrontPageOption(['blog' => 'Blog']);
	}
}