<?php namespace Drafterbit\CMS\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Drafterbit\CMS\ThemeManager;

class ThemeServiceProvider implements ServiceProviderInterface {

	function register(Container $app)
	{
		$config = $app['user_config']['config'];

		$app['themes'] = function($c) use ($config) {
			return new ThemeManager($c['yaml'], [$config['path.theme']]);
		};
	}
}