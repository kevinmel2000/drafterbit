<?php namespace Drafterbit\CMS;

use Drafterbit\Framework\Application as Foundation;
use Drafterbit\CMS\Provider\UserConfigServiceProvider;
use Drafterbit\CMS\Provider\ModuleServiceProvider;
use Drafterbit\CMS\Provider\WidgetServiceProvider;

class CMSBase extends Foundation {

	public $menu = array();
	public $frontpage = array();

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

	/**
	 * Return widget ui based on given position
	 *
	 * @param string $position
	 * @return string
	 */
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

		$config = $this['user_config']->get('config');
		// admin base
		defined('ADMIN_BASE') or define('ADMIN_BASE', $config['path.admin']);

		//front page

		$this->register(new ModuleServiceProvider);
		$this->register(new WidgetServiceProvider);

		$this['widget']->registerAll();
		$this['modules.manager']->registerAll();
		
		$this['dispatcher']->addListener('boot', function(){
			$this->frontpage = array_merge($this->frontpage, $this->frontpage());

			$settings = $this['cache']->fetch('settings');

			$homepage = $settings['homepage'];

			if(strpos($homepage, 'pages') !==  FALSE) {
				$this['router']->addRouteDefinition('/', ['controller' => '@pages\Pages::home']);
				$id = substr($homepage, -2, -1);
				$this['homepage.id'] = $id;
			} else {
				// @todo add non-page homepage
			}
		});
	}

	public function frontpage()
	{
		$qb = $this['db']->createQueryBuilder();

		$pages = $qb->select('*')
			->from('pages','p')
			->execute()->fetchAll(\PDO::FETCH_CLASS);

		$options = array();

		foreach ($pages as $page) {
			$options["pages[{$page->id}]"] = $page->title;
		}

		return $options;
	}

	public function addFrontPageOption($array)
	{
		$this->frontpage = array_merge($this->frontpage, $array);
	}

	public function getFrontPageOption()
	{
		return $this->frontpage;
	}
}