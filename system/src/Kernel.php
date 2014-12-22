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
     * Permissions.
     *
     * @var array
     */
    protected $permissions = array();
    
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

    public function addPermission($extension, $permissions)
    {
        if(!isset($this->permissions[$extension])) {
            $this->permissions[$extension] = $permissions;
        } else {
            $this->permissions[$extension] = array_merge($this->permissions[$extension], $permissions);
        }
    }

    public function getPermissions()
    {
        return $this->permissions;
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

    /**
     * Return front end menus on given position
     *
     * @param string $position
     * @return string
     */
    public function menus($position)
    {
        $qb = $this['db']->createQueryBuilder();
        
        $data = $qb->select('*')
            ->from('#_menus','m')
            ->where('position=:position')
            ->andWhere('theme=:theme')
            ->setParameter('position', $position)
            ->setParameter('theme', $this['themes']->current())
            ->fetchAllObjects();

        usort($data, function($a, $b) {
            if($a->order == $b->order) {
                return $a->id - $b->id;
            }

            return $a->order < $b->order ? -1 : 1;
        });

        foreach ($data as &$item) {
            if($item->type == 1) {
                $item->link = strtr($item->link, array("%base_url%" => base_url()));
            } else if($item->type == 2) {
                $pages = $this->getFrontpage();
                $item->link = base_url($pages[$item->page]['defaults']['slug']);
            }
        }

        return $data;
    }

    public function loadsystem()
    {
        $this['extension.manager']->load('system');

        try {
            
            $schema = $this['db']->getSchemaManager();
            
            if(!$schema->tablesExist('#_system')) {
                throw new InstallationException("No System Table", 2);
            }

            return $this->getExtension('system')->model('System')->all();

        } catch (\PDOException $e) {

            if(in_array($e->getCode(), ['1045', '1044','1046', '1049'])) {
                die('bad config :(');
            }

            throw $e;

        }
    }

    public function configure($configFile)
    {
        if(!file_exists($configFile)) {
            throw new InstallationException('No Config File', 1);
        }

        $config = $this['config']->load($configFile);

        $this['router']->addReplaces('%admin%', $config['path.admin']);
        
        $this['path.cache'] =  $this['path.content'].'cache/data';
        
        foreach (
            [
            'fontawesome' => 'Drafterbit\\System\\Asset\Filter\\DrafterbitFontAwesomeFilter',
            'chosen_css' => 'Drafterbit\\System\\Asset\Filter\\DrafterbitChosenFilter'
            ]
            as $name => $class) {
                $this['asset']->getFilterManager()->set($name, new $class($this['dir.system'].'/vendor/web'));
        }

        $system = $this->loadsystem();

        //language
        $this['translator']->setLocale($system['language']);

        //theme
        $theme = $system['theme'];

        $this['path.themes'] = $this['path.content'].'themes/';
        
        $this['themes']->current($theme);
        
        $this['themes']->registerAll();

        $this['path.theme'] = $this['path.themes'].$this['themes']->current().'/';

        $extensions = array();
        if($system !== false) {
            $extensions = json_decode($system['extensions'], true);
        }

        foreach ($extensions as $extension => $version) {
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
                'controller' => '@pages\Frontend::view',
                'requirements' => [
                    // @prototype  'slug' => "^(?!(?:backend|blog)(?:/|$)).*$"
                    'slug' => "^(?!(?:%admin%|".$reservedBaseUrl."|)(?:/|$)).*$"
                ]
            ]);
        }, -512);

        $this->addMiddleware('Drafterbit\\System\\Middlewares\\Security', array($this, $this['session'], $this['router']));
    }

    public function getFrontpage()
    {
        $qb = $this['db']->createQueryBuilder();

        $pages = $qb->select('*')
            ->from('#_pages','p')
            ->execute()->fetchAll(\PDO::FETCH_CLASS);

        $options = array();
        foreach ($pages as $page) {
            $options['pages:'.$page->id] = [
                'label' => $page->title,
                'controller' => '@pages\Frontend::home',
                'defaults' => ['id' => $page->id, 'slug' => $page->slug]
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

        foreach ($this->getFrontpage() as $id => $param) {
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