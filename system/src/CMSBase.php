<?php namespace Drafterbit\CMS;

use Drafterbit\Framework\Application as Foundation;
use Drafterbit\CMS\Provider\UserConfigServiceProvider;
use Drafterbit\CMS\Provider\ModuleServiceProvider;
use Drafterbit\CMS\Provider\WidgetServiceProvider;

class CMSBase extends Foundation {

	protected $menu = array();
	public $widgetManager;

	public function __construct($env, $debug = true)
	{
		parent::__construct($env, $debug);

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

	public function run()
	{
		$this->configureCMS();
		parent::run();
	}

	private function configureCMS()
	{
		$config = $this['user_config']->get('config');
		$this['path.cache'] =  $config['path.cache'].'/data';

		$this->register(new ModuleServiceProvider);
		$this->register(new WidgetServiceProvider);

		$this['widget']->registerAll();
		$this['modules.manager']->registerAll();
	}
}