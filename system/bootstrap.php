<?php 

defined('ENVIRONMENT') or define('ENVIRONMENT', 'dev');

$loader = require './system/vendor/autoload.php';

use Partitur\Application as Foundation;
use Drafterbit\Core\Provider\UserConfigServiceProvider;
use Drafterbit\Core\Provider\ModuleServiceProvider;
use Drafterbit\Core\Provider\WidgetServiceProvider;
use Drafterbit\Core\Provider\ThemeServiceProvider;
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
		foreach ($widgets as $widget) {
			$output .=
			$this['widget']->get($widget->name)->run(json_decode($widget->data, true));
		}

		return $output;
	}
}

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