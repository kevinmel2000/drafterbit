<?php namespace Drafterbit\CMS\System\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Gregwar\Image\Image;

class ImageServiceProvider implements ServiceProviderInterface {

	public function register(Container $app)
	{
		$app['image'] = $app->factory(function(){

			return (new Image)->setCacheDir($app['path.install'].'cache/images');
		});
	}
}