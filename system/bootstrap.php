<?php 

defined('ENVIRONMENT') or define('ENVIRONMENT', 'dev');

$loader = require './system/vendor/autoload.php';


use Drafterbit\CMS\CMSBase;
use Drafterbit\CMS\Provider\ModuleServiceProvider;
use Drafterbit\CMS\Provider\WidgetServiceProvider;

class Application extends CMSBase {}

$app = new Application;

$app->set('path.install', __DIR__ . '/../');
$app->set('path.public', __DIR__ . '/../');

$config = $app->get('user_config')->get('config');
$app->set('path.cache', $config['path.cache'].'/data');

$app->set('loader', $loader);

$app->register(new ModuleServiceProvider);
$app->register(new WidgetServiceProvider);

$app['widget']->registerAll();
$app['modules']->registerAll();
return $app;