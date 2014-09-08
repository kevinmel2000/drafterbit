<?php defined('ENVIRONMENT') or define('ENVIRONMENT', 'dev');

$loader = require './system/vendor/autoload.php';

use Drafterbit\CMS\CMSBase;
use Drafterbit\CMS\Provider\ModuleServiceProvider;
use Drafterbit\CMS\Provider\WidgetServiceProvider;

class Application extends CMSBase {}

$app = new Application;

$app['path.install'] =  __DIR__ . '/../';
$app['path.public'] =  __DIR__ . '/../';

$config = $app['user_config']->get('config');
$app['path.cache'] =  $config['path.cache'].'/data';

$app['loader'] = $loader;

$app->register(new ModuleServiceProvider);
$app->register(new WidgetServiceProvider);

$app['widget']->registerAll();
$app['modules.manager']->registerAll();
return $app;