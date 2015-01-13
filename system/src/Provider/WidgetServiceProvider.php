<?php namespace Drafterbit\System\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Drafterbit\System\Widget\WidgetManager;
use Drafterbit\System\Widget\WidgetUIBuilder;

class WidgetServiceProvider implements ServiceProviderInterface {

    function register(Container $app)
    {
        $app['widget'] = function($c) {
            return new WidgetManager($c['loader']);
        };

        $app['widget.ui'] = function () {
            return new WidgetUIBuilder;
        };
    }
}