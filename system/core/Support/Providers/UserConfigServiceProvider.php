<?php namespace Drafterbit\Support\Providers;


use Pimple\Container;
use Partitur\Config\Config;
use Pimple\ServiceProviderInterface;
use Partitur\Config\Loader\YamlLoader;

class UserConfigServiceProvider implements ServiceProviderInterface {

	function register(Container $app)
	{
		$app['user_config'] = function($c) {
			return new Config($c['path.install'], null, new YamlLoader);
		};
	}
}