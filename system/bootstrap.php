<?php 

defined('ENVIRONMENT') or define('ENVIRONMENT', 'dev');

$loader = require './system/vendor/autoload.php';

use Partitur\Application as Foundation;
use Drafterbit\Core\Provider\UserConfigServiceProvider;
use Drafterbit\Core\ModuleManager;
use Drafterbit\Core\WidgetManager;

class Application extends Foundation {

	protected $menu = array();
	public $widgetManager;

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

	public function widget($position)
	{
		$qb = $this['db']->createQueryBuilder();
		
		$widgets = $qb->select('*')
			->from('widgets','w')
			->where('position=:position')
			->setParameter('position', $position)
			->execute()->fetchAll(\PDO::FETCH_CLASS);

		$output = null;
		foreach ($widgets as $widget)
		{
			$output .=
			$this->widgetManager->get($widget->name)->run(json_decode($widget->data, true));
		}

		return $output;
	}

	public function setWidgetManager($manager)
	{
		$this->widgetManager = $manager;
	}
}

$app = new Application;

$app->set('path.install', __DIR__ . '/../');
$app->set('path.public', __DIR__ . '/../');
$app->set('path.widget', __DIR__ . '/widget');

//widget manager

$config = $app->get('user_config')->get('config');
$app->set('path.cache', $config['path.cache'].'/data');

$app->set('loader', $loader);

$widgMan = new WidgetManager($app['loader'], array($app['path.widget']));

$app->setWidgetManager($widgMan);
$app->widgetManager->registerAll();

$modulesPath = array(
	$app['path'].'modules',
	$app['path.install'].$config['path.modules']
);

$modManager = new ModuleManager($app, $app['loader'], $modulesPath);

$modManager->registerAll();

return $app;