<?php namespace Drafterbit\System\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Egig\Tika;

class TimeServiceProvider implements ServiceProviderInterface {

	function register(Container $app)
	{
		$app['time'] = function($c) {

			return new Tika;;
		};
	}
}