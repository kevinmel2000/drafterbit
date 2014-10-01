<?php namespace Drafterbit\Extensions\Files\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Drafterbit\Extensions\Files\OpenFinder;

class OpenFinderServiceProvider implements ServiceProviderInterface {

	function register(Container $app)
	{
		$app['ofinder'] = function($c) {
			$uploads = $c['config']->get('path.upload');
			return new OpenFinder($c['path.install'].$uploads, $c['file']);
		};
	}
}