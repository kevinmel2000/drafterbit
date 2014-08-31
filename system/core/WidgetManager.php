<?php namespace Drafterbit\Core;

use Partitur\Application;
use Composer\Autoload\ClassLoader;
use Symfony\Component\Finder\Finder;

class WidgetManager {

	protected $loader;
	protected $Widgets;
	protected $widgetPath;
	protected $defaultNS = 'Drafterbit\\Widgets\\';

	/**
	 * Module manager constructor.
	 *
	 * @param Partitur\Application $app
	 * @param Composer\ClassLoader $loader
	 * @param array $modulesPath
	 * @return void
	 */
	public function __construct(ClassLoader $loader, $widgetPath = array())
	{
		$this->widgetPath = $widgetPath;
		$this->loader = $loader;
	}

	/**
	 * Register a widget;
	 *
	 * @param Drafterbit\Core\WidgetInterface $widget
	 * @return void
	 */
	public function register(WidgetInterface $widget)
	{
		$this->widgets[$widget->name()] = $widget;
	}

	/**
	 * Get a widget by name;
	 *
	 * @param string $nameegis
	 * @return Drafterbit\Core\WidgetInterface
	 */
	public function get($name)
	{
		return isset($this->widgets[$name]) ? $this->widgets[$name] : false;
	}

	/**
	 * Register all modules on path.
	 *
	 * @return void
	 */
	public function registerAll()
	{
		foreach ($this->widgetPath as $path) {
			$widgets = $this->createFinder()->in($path)->directories()->depth(0);

			foreach ($widgets as $widget) {
				$config = $this->extractConfig($widget->getFileName(), $widget);
				$autoload = isset($config['autoload']) ?
					$config['autoload'] :
					$this->defaultAutoload($widget->getFileName());


				$this->registerAutoload($autoload, $widget);
			}

			foreach ($widgets as $widget) {
				$config = $this->extractConfig($widget->getFileName(), $widget);
				
				//register modules
				$ns = isset($config['ns']) ? $config['ns'] : $this->defaultNS;
				$class = $ns.studly_case($widget->getFileName()).'\\'.studly_case($widget->getFileName()).'Widget';

				$widg = new $class();
				$widg->config($config);
				$this->register($widg);
			}
		}
	}

	/**
	 * Extract config.php from module directory
	 *
	 * @param string $module modul name
	 * @param string $path module path
	 */
	private function extractConfig($module, $path)
	{
		return require $path.'/config.php';
	}

	/**
	 * Register autoload config to conposer autoloader
	 *
	 * @param array $config
	 * @param string $basepath
	 * @return void
	 */
	private function registerAutoload($config, $basePath)
	{
		foreach ($config as $key => $value) {
			switch($key) {
				case 'psr-4':
					foreach ($value as $ns => $path) {
						$this->loader->addPsr4($ns, $basePath.'/'.$path);
					}
				break;

				case 'psr-0':
					foreach ($value as $ns => $path) {
						$this->loader->addNamespace($ns, $basePath.'/'.$path);
					}
				break;

				case 'classmap':
						$this->loader->addClassmap($value);
				break;

				case 'files':
					foreach ($value as $file) {
						require $basePath.$module.'/'.$file;
					}
				break;
			}
		}
	}

	/**
	 * Create finder to finds modules
	 *
	 * @return Symfony\Component\Finder\Finder;
	 */
	private function createFinder()
	{
		return new Finder;
	}

	private function makeRelative($base, $file)
	{
		return rtrim($base, $file);
	}

	private function defaultAutoload($module)
	{
		$ns = $this->defaultNS.studly_case($module).'\\';

		return [
			'psr-4' => [
				$ns => 'src'
			]
		];
	}

	/**
	 * Get all registered widgets.
	 *
	 * @return array
	 */
	public function all()
	{
		return $this->widgets;
	}
}