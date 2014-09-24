<?php namespace Drafterbit\CMS;

use Drafterbit\Framework\Application;
use Drafterbit\CMS\Provider\DatabaseServiceProvider;
use Drafterbit\CMS\Provider\WidgetServiceProvider;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Drafterbit\CMS\Provider\ExtensionServiceProvider;

class CMS extends Application {

	public $menu = array();
	public $frontpage = array();

	public function __construct($env, $debug = true)
	{
		parent::__construct($env, $debug);

		$this->setupInstallPath();

		$this->register(new ExtensionServiceProvider);
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

	public function loadsystem()
	{
		$schema = $this['db']->getSchemaManager();

		try {

			if(!$schema->tablesExist('#_system')) {
				throw new InstallationException("No System Table", 2);
			}

			$model = $this->getExtension('system')->model('System');

			if(!$this['cache']->contains('system') ) {
				$this['cache']->save('system', $model->all());
			}

		} catch (\PDOException $e) {

			//if access denied or unknown database, we'll just start all over
			if( in_array($e->getCode(), ['1045', '1044','1046', '1049'])) {
				throw new InstallationException('Database connection failed', 1);
			}

			throw $e;
		}
	}

	public function configureCMS()
	{
		if(!file_exists($file = $this['path.install'].'config.php')) {
			throw new InstallationException('No Config File', 1);
		}

		$config = $this['config']->load($file);

		defined('ADMIN_BASE') or define('ADMIN_BASE', $config['path.admin']);
		$this['path.cache'] =  $config['path.cache'].'/data';
		$this['extension.manager']->addPath($this['path.install'].$config['path.extension']);
		$this['extension.manager']->refreshInstalled();

		$this->loadsystem();

		$system = $this['cache']->fetch('system');

		$extensions = array();
		if($system !== false) {
			$extensions = explode(',', $system['extensions']);
		}

		foreach ($extensions as $extension) {
			$this['extension.manager']->load($extension);
		}

		$this->register(new WidgetServiceProvider);
		

		//$this['asset']->setCachePath($this['path.install'].$config['path.cache'].'/asset');
		
		// admin base

		$this['widget']->registerAll();
		//$this['extension.manager']->registerAll();

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

    /**
     * Setup install path
     */
    protected function setupInstallPath()
    {
        // setup install path
        $this['path'] = $this->getRoot();
        
        foreach (['config', 'tmp'] as $key) {
            $this['path.'.$key] = $this['path'].$key.'/';
        }

        foreach (['log','cache', 'session'] as $key) {
            $this['path.'.$key] = $this['path.tmp'].$key.'/';
        }
    }
}