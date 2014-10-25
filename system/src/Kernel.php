<?php namespace Drafterbit\System;

use Drafterbit\Framework\Application;
use Drafterbit\System\Provider\ExtensionServiceProvider;
use Drafterbit\System\Provider\WidgetServiceProvider;
use Monolog\Logger;
use Drafterbit\System\Log\DoctrineDBALHandler;

class Kernel extends Application {

    /**
     * Application admin menus.
     *
     * @var array
     */
    protected $menu = array();
    
    /**
     * Application Fronpage options.
     *
     * @var array
     */
    protected $frontpage = array();

    public function __construct($environment = 'development', $debug = true)
    {
    	parent::__construct($environment, $debug);

    	$this->register(new ExtensionServiceProvider);
        $this->register(new WidgetServiceProvider);

        foreach (require $this->getResourcesPath('config/services.php')
            as $provider => $services) {
            $this->addDeferred($provider, $services);
        }
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
            ->from('#_widgets','w')
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
        $this['extension.manager']->load('system');
        $schema = $this['db']->getSchemaManager();

        try {

            if(!$schema->tablesExist('#_system')) {
                throw new InstallationException("No System Table", 2);
            }

            $model = $this->getExtension('system')->model('System');

            if(!$this['cache']->contains('system') ) {
                $this['cache']->save('system', $model->all());
            }

            return $this['cache']->fetch('system');

        } catch (\PDOException $e) {

            //if access denied or unknown database, we'll just start all over
            if( in_array($e->getCode(), ['1045', '1044','1046', '1049'])) {
                throw new InstallationException('Database connection failed', 1);
            }

            throw $e;
        }
    }

    public function configure()
    {
        if(!file_exists($file = $this['path.install'].'config.php')) {
            throw new InstallationException('No Config File', 1);
        }

        $config = $this['config']->load($file);

        $this['router']->addReplaces('%admin%', $config['path.admin']);
        
        $this['path.cache'] =  $this['path.content'].'cache/data';

        // asset
        $this['config']->addReplaces('%path.vendor.asset%', $this['path'].'vendor/web');
        $this['config']->addReplaces('%path.system.asset%', $this['path'].'Resources/public/assets');


        foreach ($this['config']->get('asset.assets') as $name => $value) {
            $this['asset']->register($name, $value);
        }

        $this['asset']->setCachePath($this['path.content'].'cache/asset');
        
        foreach (
            [
            'fontawesome' => 'Drafterbit\\System\\Asset\Filter\\DrafterbitFontAwesomeFilter',
            'chosen_css' => 'Drafterbit\\System\\Asset\Filter\\DrafterbitChosenFilter'
            ]
            as $name => $class) {
                $this['asset']->getFilterManager()->set($name, new $class(basename($this['path']).'/vendor/web'));
        }

        $system = $this->loadsystem();

        $extensions = array();
        if($system !== false) {
            $extensions = explode(',', $system['extensions']);
        }

        foreach ($extensions as $extension) {
            $this['extension.manager']->load($extension);
        }

        $this['widget']->registerAll();

        $config = $this['config']['app'];
        
        date_default_timezone_set($system['timezone']);
        
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
                return file_get_contents( $this->getResourcesPath('views/404.html'));
            });
        }

        $this['log.db'] = function(){
            $logger =  new Logger('db.log');
            $logger->pushHandler(new DoctrineDBALHandler($this['db']));
            return $logger;
        };

        $this['log.db']->pushProcessor(function ($record) {
            $record['formatted'] = "%message%";
            return $record;
        });

        $this['dispatcher']->addListener('boot', function(){

            // Fronpage
            $this->frontpage = array_merge($this->frontpage, $this->frontpage());
            
            $system = $this['cache']->fetch('system');

            $homepage = $system['homepage'];
            
            $route = $this->frontpage[$homepage];

            $this['router']->addRouteDefinition('/', [
                'controller' => $route['controller'],
                'defaults' => $route['defaults']
                ]
            );
        });
    }

    public function frontpage()
    {
        $qb = $this['db']->createQueryBuilder();

        $pages = $qb->select('*')
            ->from('#_pages','p')
            ->execute()->fetchAll(\PDO::FETCH_CLASS);

        $options = array();

        foreach ($pages as $page) {
            $options[$page->slug] = [
                'label' => $page->title,
                'controller' => '@pages\Pages::home',
                'defaults' => ['id' => $page->id]
            ];
        }

        return $options;
    }

    public function addFrontPageOption($array)
    {
        $this->frontpage = array_merge($this->frontpage, $array);
    }

    public function getFrontPageOption()
    {
        $options = array();

        foreach ($this->frontpage as $id => $param) {
            $options[$id] = $param['label'];
        }

        return $options;
    }
}