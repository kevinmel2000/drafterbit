<?php namespace Drafterbit\CMS;

use Drafterbit\Framework\Application;
use Composer\Autoload\ClassLoader;
use Symfony\Component\Finder\Finder;

class ExtensionManager {

	protected $app;
	protected $loaded;
	protected $loader;
	protected $installed = array();
	protected $enabled;
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
	 * Get installed extensions
	 *
	 * @return array
	 */
	public function getInstalled()
	{
		if(count($this->installed) == 0) {
			foreach ($this->extensionsPath as $path) {
				$finder = $this->createFinder()->in($path)->directories()->depth(0);
				foreach ($finder as $file) {

					$name = $file->getFileName();
					if(in_array($name, $this->installed)) {
						throw new \Exception("Extension name collision: $name");
					}

					if(file_exists($file.'/config.php')) {
						$config = require $file.'/config.php';
					}

					$config['path'] = $path;
					$this->installed[$name] = $config;
				}
			}
		}

		return $this->installed;
	}

	/**
	 * Refresh installed in case we add new path
	 *
	 * @return vois
	 */
	public function refreshInstalled()
	{
		$this->installed = array();
	}

	/**
	 * Get Core Extension
	 *
	 * @return array
	 */
	public function getCoreExtension()
	{
		return ['system', 'admin', 'user','pages', 'files', 'blog'];
	}

	/**
	 * Get Enabled Extension
	 *
	 * @return array
	 */
	public function getEnabled()
	{
		return $this->enabled;
	}

	/**
	 * Enabale an Extension
	 *
	 * @return mixed
	 */
	public function enable($extension)
	{
		$this->get($extension)->enable();
		$this->enabled[$extension] = true;
	}

	/**
	 * Get an Extension
	 *
	 * @return Drafterbit\Framework\Extension
	 */
	public function get($extension)
	{
		if(isset($this->loaded[$extension])) {
			return $this->loaded[$extension];
		}

		$installed = $this->getInstalled();

		if(!array_key_exists($extension, $installed)) {
			throw new \Exception("Extension $extension is not installed yet");
		}

		$config = $installed[$extension];

		$path = $config['path'];

		//register autoload
		$autoload = isset($config['autoload']) ?
			$config['autoload'] :
			$this->defaultAutoload($extension);

		$this->registerAutoload($autoload, $path.'/'.$extension);

		$ns = isset($config['ns']) ? $config['ns'] : $this->defaultNS;
		$class = $ns.studly_case($extension).'\\'.studly_case($extension).'Extension';

		// register menu
		if(isset($config['menus'])) {
			$this->app->addMenu($config['menus']);
		}

		return $this->loaded[$extension] = new $class;
	}

	/**
	 * @return Partitur\Framework\Extension
	 */
	public function load($extension)
	{
		$this->app->addExtension($this->get($extension));
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
			}
		}
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

	private function defaultAutoload($extension, $ns = null)
	{
		$ns = !is_null($ns) ? $ns : $this->defaultNS.studly_case($extension).'\\';

		return [
			'psr-4' => [
				$ns => 'src'
			]
		];
	}

	public function addPath($path)
	{
		$this->extensionsPath = array_merge($this->extensionsPath, (array)$path);
	}
}