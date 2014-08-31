<?php namespace Drafterbit\Core\Provider;

use Pimple\Container;
use Drafterbit\Framework\Config\Config;
use Pimple\ServiceProviderInterface;
use Drafterbit\Core\ModuleManager;

class ModuleServiceProvider implements ServiceProviderInterface {

	function register(Container $app)
	{

		$config = $app->get('user_config')->get('config');

		$modulesPath = array(
			$app['path'].'modules',
			$app['path.install'].$config['path.modules']
		);

		$app['modules'] = function($c) use ($modulesPath){
			return new ModuleManager($c, $c['loader'], $modulesPath);
		};
	}
}