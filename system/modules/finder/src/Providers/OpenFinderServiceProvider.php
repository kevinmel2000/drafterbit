<?php namespace Drafterbit\Modules\Finder\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Drafterbit\Modules\Finder\OpenFinder;

class OpenFinderServiceProvider implements ServiceProviderInterface {

	function register(Container $app)
	{
		$app['ofinder'] = function($c) {
			$uploads = $c['user_config']->get('config.path.upload');
			return new OpenFinder($c['path.install'].$uploads, $c['file']);
		};
	}
}