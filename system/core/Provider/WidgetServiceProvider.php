<?php namespace Drafterbit\Core\Provider;

use Pimple\Container;
use Drafterbit\Framework\Config\Config;
use Pimple\ServiceProviderInterface;
use Drafterbit\Core\WidgetManager;
use Drafterbit\Core\WidgetUIBuilder;

class WidgetServiceProvider implements ServiceProviderInterface {

	function register(Container $app)
	{

		$config = $app->get('user_config')->get('config');

		$widgetPath = array(
			$app['path'].'widget',
			$app['path.install'].$config['path.widget']
		);

		$app['widget'] = function($c) use ($widgetPath) {
			return new WidgetManager($c['loader'], $widgetPath);
		};

		$app['widget.ui'] = function () {
			return new  WidgetUIBuilder;
		};
	}
}