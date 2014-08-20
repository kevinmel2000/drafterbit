<?php namespace Egig\OpenFinder\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Egig\OpenFinder;

class OpenFinderServiceProvider implements ServiceProviderInterface {

	function register(Container $app)
	{
		$app['ofinder'] = function($c) {
			$uploads = $c['user_config']->get('config.upload_dir');
			return new OpenFinder($c['path.install'].$uploads, $c['file']);
		};
	}
}