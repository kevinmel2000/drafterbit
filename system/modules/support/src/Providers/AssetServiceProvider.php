<?php namespace Drafterbit\Modules\Support\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Drafterbit\Modules\Support\Assetic\AssetManager;

class AssetServiceProvider implements ServiceProviderInterface {

	function register( Container $app)
	{
		
		$app['asset'] = function($c) {
			$cachePath = $c['user_config']->get('config.path.cache');
			$assetPath = $c['config']['asset.path'];
			return new AssetManager($cachePath.'/asset/', $c['path.public'].$assetPath, $c['debug']);
		};
	}
}