<?php namespace Drafterbit\System\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Drafterbit\System\ThemeManager;

class ThemeServiceProvider implements ServiceProviderInterface {

	function register(Container $app)
	{
		$app['themes'] = function($c) {
			return new ThemeManager([$c['path.content'].'themes']);
		};
	}
}