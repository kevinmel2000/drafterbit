<?php namespace Drafterbit\Extensions\System\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Drafterbit\Extensions\System\Asset\AssetManager;

class AssetServiceProvider implements ServiceProviderInterface {

	function register( Container $app)
	{
		$app['asset'] = function($c) {
			$publicPath = $c['config']['asset.path'];
			return new AssetManager(null, $c['debug']);
		};
	}
}