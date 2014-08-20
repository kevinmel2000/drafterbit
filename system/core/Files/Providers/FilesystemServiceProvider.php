<?php namespace Drafterbit\Files\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Filesystem\Filesystem;

class FilesystemServiceProvider implements ServiceProviderInterface {

	function register(Container $app)
	{
		$app['file'] = function() {
			return new Filesystem();
		};
	}
}