<?php namespace Drafterbit\CMS\Provider;

use Pimple\Container;
use Drafterbit\Framework\Config\Config;
use Pimple\ServiceProviderInterface;
use Drafterbit\CMS\ModuleManager;

class ModuleServiceProvider implements ServiceProviderInterface {

	function register(Container $app)
	{

		$config = $app['user_config']['config'];

		$modulesPath = array(
			$app['path'].'modules',
			$app['path.install'].$config['path.modules']
		);

		$app['modules.manager'] = function($c) use ($modulesPath){
			return new ModuleManager($c, $c['loader'], $modulesPath);
		};
	}
}