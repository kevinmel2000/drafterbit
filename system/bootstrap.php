<?php 

defined('ENVIRONMENT') or define('ENVIRONMENT', 'dev');

$loader = require './system/vendor/autoload.php';

use Partitur\Application as Foundation;
use Drafterbit\Core\Provider\UserConfigServiceProvider;
use Drafterbit\Core\ModuleManager;

class Application extends Foundation {

	protected $menu = array();

	public function __construct()
	{
		parent::__construct(ENVIRONMENT);

		$this->register(new UserConfigServiceProvider);
	}

	public function addMenu($menu)
	{
		$this->menu = array_merge($this->menu, $menu);
	}

	public function getMenu()
	{
		return $this->menu;
	}
}

$app = new Application;

$app->set('path.install', __DIR__ . '/../');
$app->set('path.public', __DIR__ . '/../');

$config = $app->get('user_config')->get('config');
$app->set('path.cache', $config['path.cache'].'/data');

$app->set('loader', $loader);

$modulesPath = array(
	$app['path'].'modules',
	$app['path.install'].$config['path.modules']
);

$modManager = new ModuleManager($app, $app['loader'], $modulesPath);

$modManager->registerAll();

return $app;