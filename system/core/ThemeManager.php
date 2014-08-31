<?php namespace Drafterbit\Core;

use Drafterbit\Framework\Application;
use Symfony\Component\Finder\Finder;

class ThemeManager {

	protected $themes;
	protected $current;
	protected $yamlParser;
	protected $path;

	public function __construct($yamlParser, $path = array())
	{
		$this->yamlParser = $yamlParser;
		$this->path = $path;
	}

    /**
     * Register all modules on path.
     *
     * @return void
     */
    public function registerAll()
    {
        foreach ($this->path as $path) {
            $themes = $this->createFinder()->in($path)->directories()->depth(0);

            foreach ($themes as $theme) {
                $config = $this->yamlParser->parse(file_get_contents($theme.'/config.yml'));

                $this->register($theme->getFilename(), $config);
            }
        }
    }

    /**
     * Register a theme.
     ** @return void
     */
	public function register($name, $config)
	{
		$this->themes[$name] = $config;
	}

	/**
	 * Get or set current theme;
	 *
	 * @param string $thame theme name
	 */
	public function current($theme = null) {
		if(is_null($theme)) {
			return $this->current;
		}

		return $this->current = $theme;
	}

    /**
     * Create finder to finds themes
     *
     * @return Symfony\Component\Finder\Finder;
     */
    private function createFinder()
    {
        return new Finder;
    }

    /**
     * Get all registered themes.
     *
     * @return array
     */
    public function all()
    {
        return $this->themes;
    }

    /**
     * Get a theme config;
     *
     * @param string $name
     * @return array
     */
    public function get($theme = null)
    {
        $theme = is_null($theme) ? $this->current : $theme;
        return isset($this->themes[$theme]) ? $this->themes[$theme] : false;
    }
}