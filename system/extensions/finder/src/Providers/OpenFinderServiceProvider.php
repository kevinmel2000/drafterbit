<?php namespace Drafterbit\Extensions\Finder\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Drafterbit\Extensions\Finder\OpenFinder;

class OpenFinderServiceProvider implements ServiceProviderInterface {

	function register(Container $app)
	{
		$app['ofinder'] = function($c) {
			$uploads = $c['config.cms']->get('path.upload');
			return new OpenFinder($c['path.install'].$uploads, $c['file']);
		};
	}
}