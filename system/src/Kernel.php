<?php namespace Drafterbit\System;

use Monolog\Logger;
use Drafterbit\Framework\Application;
use Drafterbit\System\Log\DoctrineDBALHandler;
use Drafterbit\System\Provider\WidgetServiceProvider;
use Drafterbit\System\Provider\ExtensionServiceProvider;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


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

            return $this->getExtension('system')->model('System')->all();

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

        //theme
        $theme = $system['theme'];

        $this['path.themes'] = $this['path.content'].'themes/';
        
        $this['themes']->current($theme);
        
        $this['themes']->registerAll();

        $this['path.theme'] = $this['path.themes'].$this['themes']->current().'/';

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
            $this['exception']->error(function( NotFoundHttpException $e){
                
                if(is_file($this['path.theme'].'404.html')) {
                    return $this['twig']->render('404.html');
                }

                return file_get_contents($this->getResourcesPath('views/404.html'));
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

        // base url
        $this['dispatcher']->addListener('boot', function(){

            // frontpage
            $frontpage = $this->getFrontpage();
            $system = $this->getExtension('system')->model('@system\System')->all();
            $homepage = $system['homepage'];
            $route = $frontpage[$homepage];

            $this['router']->addRouteDefinition('/', [
                'controller' => $route['controller'],
                'defaults' => $route['defaults']
                ]
            );

            // pages
            $reservedBaseUrl = implode('|', $this->getReservedBaseUrl());
            $this['router']->addRouteDefinition('/{slug}', [
                'controller' => '@pages\Pages::view',
                'requirements' => [
                    // @prototype  'slug' => "^(?!(?:backend|blog)(?:/|$)).*$"
                    'slug' => "^(?!(?:%admin%|".$reservedBaseUrl."|)(?:/|$)).*$"
                ]
            ]);
        }, -512);
    }

    public function getFrontpage()
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

        return array_merge($this->frontpage, $options);
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

    /**
     * Get dashboard Shortcuts;
     */
    public function getShortcuts()
    {
        $shortcuts = array();
        foreach($this->getExtensions() as $extension){
            if(method_exists($extension, 'getShortcuts')) {
                $shortcuts =  array_merge($shortcuts, $extension->getShortcuts());
            }
        }

        return $shortcuts;
    }

    public function getReservedBaseUrl()
    {
        $urls = array();
        foreach($this->getExtensions() as $extension){
            if(method_exists($extension, 'getReservedBaseUrl')) {
                $urls =  array_merge($urls, $extension->getReservedBaseUrl());
            }
        }

        return $urls;
    }
}