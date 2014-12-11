<?php defined('ENVIRONMENT') or die();

// just for now
date_default_timezone_set('UTC');

$loader = require __DIR__ . '/loader.php';

use Drafterbit\System\Kernel;
use Drafterbit\System\InstallationException;

class App extends Kernel {}

$app = new App(ENVIRONMENT);

$app['loader'] = $loader;

$app['path.content'] = rtrim($content,'/').'/'; unset($content);

is_writable($app['path.content']) or
	exit('Folder '.$app['path.content'].' is required to be writable');

$app['dir.content'] = basename($app['path.content']);
$app['dir.system'] = basename($app['path']);

$app['path.extensions'] = $app['path.content'] . '/extensions';
$app['path.install'] = $app['path.public'] =  realpath(__DIR__ .'/../').'/';

// asset
$app['config']->addReplaces('%path.vendor.asset%', $app['path'].'vendor/web');
$app['config']->addReplaces('%path.system.asset%', $app['path'].'Resources/public/assets');
$app['asset']->setCachePath($app['path.content'].'cache/asset');

foreach ($app['config']->get('asset.assets') as $name => $value) {
    $app['asset']->register($name, $value);
}

try {

	$app->configure($app['path.install'].'config.php');

} catch(InstallationException $e) {

	$code = $e->getCode();
	$app['extension.manager']->load('installer');
	$app->getExtension('installer')->setStart($code);
}

return $app;