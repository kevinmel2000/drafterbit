<?php namespace Drafterbit\CMS;

use Drafterbit\Framework\Application as Foundation;
use Drafterbit\CMS\Provider\DatabaseServiceProvider;
use Drafterbit\CMS\Provider\ExtensionServiceProvider;
use Drafterbit\CMS\Provider\WidgetServiceProvider;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class CMSBase extends Foundation {

	public $menu = array();
	public $frontpage = array();

	public function __construct($env, $debug = true)
	{
		parent::__construct($env, $debug);
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

	public function configureCMS()
	{
		if(!file_exists($this['path.install'].'config.php')) {
			throw new Exceptions\ConfigFIleNotFoundException('No Config File');
		}
		
		$this['config.cms'] = $config = require $this['path.install'].'config.php';
		$this->register(new DatabaseServiceProvider);
		
		$this->register(new ExtensionServiceProvider);
		$this->register(new WidgetServiceProvider);

		
		$this['path.cache'] =  $config['path.cache'].'/data';

		//$this['asset']->setCachePath($this['path.install'].$config['path.cache'].'/asset');
		
		// admin base
		defined('ADMIN_BASE') or define('ADMIN_BASE', $config['path.admin']);

		//front page


		$this['widget']->registerAll();
		$this['extension.manager']->registerAll();
		
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

		$this->configureApplication();        
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

	/**
     * Configure the application.
     *
     * @return void
     */
    protected function configureApplication()
    {
        $config = $this['config']['app'];
        
        date_default_timezone_set($config['timezone']);
        
        $this['debug'] = $config['debug'];
        $this['exception']->setDebug($this['debug']);

        if ($config['error.log']) {
            $this['exception']
                ->error(function(\Exception $exception, $code) {
                    $this['log']->addError($exception);
                });
        }

        if( ! $this['debug']) {
            $this['exception']->error(function( NotFoundHttpException $e) {
                return file_get_contents( $this->getResourcesPath().'views/404.html');
            });
        }
    }
}