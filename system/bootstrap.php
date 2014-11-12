<?php defined('ENVIRONMENT') or die();

// just for now
date_default_timezone_set('UTC');

$loader = require __DIR__.'/vendor/autoload.php';
$loader->addPsr4('Drafterbit\\System\\', __DIR__.'/src');

use Drafterbit\System\Kernel;
use Drafterbit\System\InstallationException;

class App extends Kernel {}

$app = new App(ENVIRONMENT);

$app['path.content'] = rtrim($content,'/').'/'; unset($content);

$app['dir.content'] = basename($app['path.content']);

$app['path.extensions'] = $app['path.content'] . '/extensions';
$app['path.widgets'] = $app['path.content'] . '/widgets';

$app['loader'] = $loader;
$app['path.install'] = $app['path.public'] =  realpath(__DIR__ .'/../').'/';

// asset
$app['config']->addReplaces('%path.vendor.asset%', $app['path'].'vendor/web');
$app['config']->addReplaces('%path.system.asset%', $app['path'].'Resources/public/assets');
$app['asset']->setCachePath($app['path.content'].'cache/asset');

foreach ($app['config']->get('asset.assets') as $name => $value) {
    $app['asset']->register($name, $value);
}

try {

	$app->configure();

} catch(InstallationException $e) {

	$code = $e->getCode();
	$app['extension.manager']->load('installer');
	$app->getExtension('installer')->setStart($code);
}

return $app;