<?php defined('ENVIRONMENT') or die();

// just for now
date_default_timezone_set('UTC');

$loader = require __DIR__.'/vendor/autoload.php';

use Drafterbit\CMS\CMS;
use Drafterbit\CMS\InstallationException;


class Application extends CMS {}

$app = new Application(ENVIRONMENT);

$app['loader'] = $loader;
$app['path.install'] = $app['path.public'] =  __DIR__ .'/../';

$app['extension.manager']->load('system');

try {

	$app->configureCMS();

} catch(InstallationException $e) {

	$code = $e->getCode();
	$app['extension.manager']->load('installer');
	$app->getExtension('installer')->setStart($code);
}

return $app;