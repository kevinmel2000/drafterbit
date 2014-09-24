<?php namespace Drafterbit\CMS\Provider;

use Pimple\Container;
use Drafterbit\Framework\Config\Config;
use Pimple\ServiceProviderInterface;
use Drafterbit\CMS\ExtensionManager;

class ExtensionServiceProvider implements ServiceProviderInterface {

	function register(Container $app)
	{

		$config = $app['config'];

		$modulesPath = array(
			$app['path'].'extensions',
		);

		$app['extension.manager'] = function($c) use ($modulesPath){
			return new ExtensionManager($c, $c['loader'], $modulesPath);
		};
	}
}