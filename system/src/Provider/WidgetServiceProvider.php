<?php namespace Drafterbit\CMS\Provider;

use Pimple\Container;
use Drafterbit\Framework\Config\Config;
use Pimple\ServiceProviderInterface;
use Drafterbit\CMS\Widget\WidgetManager;
use Drafterbit\CMS\Widget\WidgetUIBuilder;

class WidgetServiceProvider implements ServiceProviderInterface {

	function register(Container $app)
	{

		$config = $app['user_config']['config'];

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