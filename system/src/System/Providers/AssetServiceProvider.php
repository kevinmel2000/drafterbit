<?php namespace Drafterbit\CMS\System\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Drafterbit\CMS\System\Assetic\AssetManager;

class AssetServiceProvider implements ServiceProviderInterface {

	function register( Container $app)
	{
		
		$app['asset'] = function($c) {
			$assetPath = $c['config']['asset.path'];
			return new AssetManager(null, $c['path.public'].$assetPath, $c['debug']);
		};
	}
}