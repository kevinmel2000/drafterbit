<?php namespace Drafterbit\CMS\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Drafterbit\Framework\Config\Config;
use Drafterbit\Framework\Config\Loader\YamlLoader;

class UserConfigServiceProvider implements ServiceProviderInterface {

	function register(Container $app)
	{
		$app['user_config'] = function($c) {
			return new Config($c['path.install'], $c['environment']);
		};
	}
}