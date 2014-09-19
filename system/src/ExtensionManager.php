<?php namespace Drafterbit\CMS;

use Drafterbit\Framework\Application;
use Composer\Autoload\ClassLoader;
use Symfony\Component\Finder\Finder;

class ExtensionManager {

	protected $app;
	protected $loader;
	protected $extensionsPath;
	protected $defaultNS = 'Drafterbit\\Extensions\\';

	/**
	 * Extension manager constructor.
	 *
	 * @param Drafterbit\Framework\Application $app
	 * @param Composer\ClassLoader $loader
	 * @param array $extensionsPath
	 * @return void
	 */
	public function __construct(Application $app, ClassLoader $loader, $extensionsPath = array())
	{
		$this->app = $app;
		$this->extensionsPath = $extensionsPath;
		$this->loader = $loader;
	}

	/**
	 * Register all extensions on path.
	 *
	 * @return void
	 */
	public function registerAll()
	{
		foreach ($this->extensionsPath as $path) {
			$extensions = $this->createFinder()->in($path)->directories()->depth(0);

			foreach ($extensions as $extension) {
				$config = $this->extractConfig($extension->getFileName(), $extension);

				$autoload = isset($config['autoload']) ?
					$config['autoload'] :
					$this->defaultAutoload($extension->getFileName());

				$this->registerAutoload($autoload, $extension);
			}

			foreach ($extensions as $extension) {
				$config = $this->extractConfig($extension->getFileName(), $extension);
				
				//register extensions
				$ns = isset($config['ns']) ? $config['ns'] : $this->defaultNS;
				$class = $ns.studly_case($extension->getFileName()).'\\'.studly_case($extension->getFileName()).'Extension';

				$mod = new $class($this->app);
				$this->app->addExtension($mod);

				// register menu
				if(isset($config['menus'])) {
					$this->app->addMenu($config['menus']);
				}
			}
		}
	}

	/**
	 * Register single extension.
	 *
	 * @param string $extension modul name
	 * @param string $path extension path
	 */
	public function registerExtension($extension, $path)
	{
		
		$config = $this->extractConfig($extension, $path);

		$autoload = isset($config['autoload']) ?
			$config['autoload'] :
			$this->defaultAutoload($extension);

		//$basepath = $this->makeRelative($path, $extension);
		$this->registerAutoload($autoload, $path);

		//register extensions
		$ns = isset($config['ns']) ? $config['ns'] : $this->defaultNS;
		$class = $ns.studly_case($extension).'\\'.studly_case($extension).'Extension';

		$mod = new $class($this->app);
		$this->app->registerExtension($mod);
	}

	/**
	 * Extract config.php from extension directory
	 *
	 * @param string $extension modul name
	 * @param string $path extension path
	 */
	private function extractConfig($extension, $path)
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
						require $basePath.$extension.'/'.$file;
					}
				break;
			}
		}
	}

	/**
	 * Create finder to finds extensions
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

	private function defaultAutoload($extension)
	{
		$ns = $this->defaultNS.studly_case($extension).'\\';

		return [
			'psr-4' => [
				$ns => 'src'
			]
		];
	}
}