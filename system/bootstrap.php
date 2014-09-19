<?php defined('ENVIRONMENT') or die();

$loader = require __DIR__.'/vendor/autoload.php';

use Drafterbit\CMS\CMSBase;
use Drafterbit\CMS\Exceptions\ConfigFIleNotFoundException;
use Drafterbit\CMS\Installer\InstallerExtension;
use Drafterbit\CMS\System\SystemExtension;

class Application extends CMSBase {}

$app = new Application(ENVIRONMENT);

$app['loader'] = $loader;
$app['path.install'] = $app['path.public'] =  __DIR__ .'/../';

try {

	$app->addExtension(new SystemExtension());
	$app->configureCMS();

} catch(ConfigFIleNotFoundException $e) {

	$app->addModule(new InstallerModule($app));
	$app->addModule(new SupportModule($app));
}


return $app;