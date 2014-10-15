<?php namespace Drafterbit\System\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Drafterbit\Component\Template\Asset\AssetManager;

class AssetServiceProvider implements ServiceProviderInterface {

	function register( Container $app)
	{
		$app['config']->addReplaces('%path.vendor.asset%', $app['path'].'vendor/web');
		
		$app['asset'] = function($c) use($app) {


			$asset = new AssetManager($c['path.content'] . 'cache/asset', $c['debug']);

		  	foreach ($app['config']->get('asset.assets') as $name => $value) {
	            $asset->register($name, $value);
	        }
		    
		    foreach (
		    	[
		    	'fontawesome' => 'Drafterbit\\System\\Asset\Filter\\DrafterbitFontAwesomeFilter',
		    	'chosen_css' => 'Drafterbit\\System\\Asset\Filter\\DrafterbitChosenFilter'
		    	]
		    	as $name => $class) {
			    	$asset->getFilterManager()->set($name, new $class('system/vendor/web'));
			}

			return $asset;
		};
	}
}