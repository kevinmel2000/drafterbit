<?php namespace Drafterbit\Core;

use Partitur\Application;
use Composer\Autoload\ClassLoader;
use Symfony\Component\Finder\Finder;

class ModuleManager {

	protected $app;
	protected $loader;
	protected $modulesPath;
	protected $defaultNS = 'Drafterbit\\Modules\\';

	/**
	 * Module manager constructor.
	 *
	 * @param Partitur\Application $app
	 * @param Composer\ClassLoader $loader
	 * @param array $modulesPath
	 * @return void
	 */
	public function __construct(Application $app, ClassLoader $loader, $modulesPath = array())
	{
		$this->app = $app;
		$this->modulesPath = $modulesPath;
		$this->loader = $loader;
	}

	/**
	 * Register all modules on path.
	 *
	 * @return void
	 */
	public function registerAll()
	{
		foreach ($this->modulesPath as $path) {
			$modules = $this->createFinder()->in($path)->directories()->depth(0);

			foreach ($modules as $module) {
				$config = $this->extractConfig($module->getFileName(), $module);
				$autoload = isset($config['autoload']) ?
					$config['autoload'] :
					$this->defaultAutoload($module->getFileName());


				$this->registerAutoload($autoload, $module);
			}

			foreach ($modules as $module) {
				$config = $this->extractConfig($module->getFileName(), $module);
				//register modules
				$ns = isset($config['ns']) ? $config['ns'] : $this->defaultNS;
				$class = $ns.studly_case($module->getFileName()).'\\'.studly_case($module->getFileName()).'Module';

				$mod = new $class($this->app);
				$this->app->registerModule($mod);

				// register menu
				if(isset($config['menus'])) {
					$this->app->addMenu($config['menus']);
				}
			}
		}
	}

	/**
	 * Register single module.
	 *
	 * @param string $module modul name
	 * @param string $path module path
	 */
	public function registerModule($module, $path)
	{
		
		$config = $this->extractConfig($module, $path);

		$autoload = isset($config['autoload']) ?
			$config['autoload'] :
			$this->defaultAutoload($module);

		//$basepath = $this->makeRelative($path, $module);
		$this->registerAutoload($autoload, $path);

		//register modules
		$ns = isset($config['ns']) ? $config['ns'] : $this->defaultNS;
		$class = $ns.studly_case($module).'\\'.studly_case($module).'Module';

		$mod = new $class($this->app);
		$this->app->registerModule($mod);
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
}