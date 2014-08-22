<?php defined('ENVIRONMENT') or define('ENVIRONMENT', 'development');

$loader = require './system/vendor/autoload.php';

use Partitur\Application as Foundation;
use Drafterbit\Core\Provider\UserConfigServiceProvider;
use Drafterbit\Core\ModuleManager;

class Application extends Foundation {

	public function __construct()
	{
		parent::__construct(ENVIRONMENT);

		$this->register(new UserConfigServiceProvider);
	}
}

$app = new Application;

$app->set('path.install', __DIR__ . '/../');
$app->set('path.public', __DIR__ . '/../');
$app->set('path.cache', $app->get('user_config')->get('config.path.cache').'/data');

// Drafterbit use composer loader to as module loader
$app->set('loader', $loader);

$modManager = new ModuleManager($app, $app['loader'], array($app['path'].'modules'));

$modManager->registerAll();

// @todo change classes namesapaces

//var_dump($modManager);

return $app;