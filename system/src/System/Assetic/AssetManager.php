<?php namespace Drafterbit\CMS\System\Assetic;

use Assetic\AssetManager as BaseManager;
use Assetic\FilterManager;
use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;
use Assetic\Asset\AssetReference;

use Assetic\Filter\FilterInterface;
use Assetic\Filter\CssMinFilter;
use Assetic\Filter\JSMinFilter;

class AssetManager extends BaseManager {

	/**
	 * Css asset collection
	 *
	 * @var array
	 */
	protected $debug;
	protected $root;
	protected $name = array();
	protected $filterManager;
	protected $cachePath;
	protected $collections = array();
	protected $queues = array();

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct($cachePath = null, $root = null, $debug = true)
	{
		$this->debug = $debug;
		$this->cachePath = $cachePath;
		$this->root = $root;
		$this->filterManager = new FilterManager();
	}

	/**
	 * Write asset to a file.
	 *
	 * @param $path
	 */
	public function write($path, $content)
	{
		if (!is_dir($dir = dirname($path)) && false === @mkdir($dir, 0777, true)) {
            throw new \RuntimeException('Unable to create directory '.$dir);
        }

        if (false === file_put_contents($path, $content)) {
            throw new \RuntimeException('Unable to write file '.$path);
		}
	}

	/**
	 * Override base set method.
	 *
	 * @param string $name  The asset name
     * @param mixed  $asset The asset
     *
     * @throws \InvalidArgumentException If the asset name is invalid
     */
	public function register($name, $asset, $filter = array())
	{
		$asset = $this->parseInput($asset, $filter);
		return $this->set($name, $asset);
	}

	/**
	 * Get Filter Manager.
	 *
	 * @return \Assetic\FilterManager
	 */
	public function getFilterManager()
	{
		return $this->filterManager;
	}

	/**
	 * Add js asset
	 *
	 * @param string $name
	 */
	public function js($name, $filter = array(), $options = array())
	{
		return $this->add('js', $name, $filter, $options);
	}

	/**
	 * Add css asset
	 *
	 * @param string $name
	 */
	public function css($name, $filter = array(), $options = array())
	{
		return $this->add('css', $name, $filter, $options);
	}

	/**
	 * Add an asset.
	 *
	 * @param string $type
	 */
	public function add($type, $name, $filter = array(), $options = array())
	{
		if(!isset($this->collections[$type])) {
			$this->collections[$type] = new AssetCollection;
		}

		$asset = $this->parseInput($name, $filter);

		$this->collections[$type]->add($asset, $filter, $options);
		return $this;
	}

	public function writeCSS()
	{
		return $this->dumpToFile('css', new CssMinFilter);
	}

	public function writeJs()
	{
		return $this->dumpToFile('js', new JSMinFilter);
	}

	/**
	 * Asset file name.
	 *
	 * @param string
	 */
	public function setJsName($name)
	{
		$this->name['js'] = $name;
	}

	/**
	 * Asset file name.
	 *
	 * @param string
	 */
	public function setCSSName($name)
	{
		$this->name['css'] = $name;
	}

	/**
	 * Dump asset
	 */
	public function dump($type, FilterInterface $lastFilter = null)
	{
		return $this->collections[$type]->dump($lastFilter);
	}

	/**
	 * Dump to file;
	 *
	 * @param string $type
	 * @param \Assetic\Filter\FilterInterface $lastFilter
	 * @return string
	 */
	public function dumpToFile($type, FilterInterface $lastFilter = null)
	{
		if(is_null($this->cachePath)) {
			throw new \Exception("Asset cache path is not been set yet");
		}

		$fileName = isset($this->name[$type]) ?
			$this->name[$type] : $this->createFileName($this->collections[$type]->all());
			
			$dest = $this->cachePath."/$type/".$fileName.".$type";

		if($this->debug) {
			$this->write($dest,$this->collections[$type]->dump());
		} else {

			// @todo fixes  double css asset dumps, not because the filter,
			// seems like because the check;
			if(!file_exists($dest)) {
				$this->write($dest, $this->collections[$type]->dump($lastFilter));
			}
		}

		return $fileName;
	}

	/**
	 * Create File name.
	 *
	 * @return string
	 */
	public function createFileName($asset)
	{
		return sha1(json_encode($asset));
	}

	/**
	 * Get cache path.
	 *
	 * @return string
	 */
	public function getCachePath()
	{
		return $this->cachePath;
	}

	/**
	 * Set cache path.
	 *
	 * @param string $path
	 */
	public function setCachePath($path)
	{
		return $this->cachePath = $path;
	}

	/**
	 * Parse asset defined in config on-fly addition.
	 *
	 * @param mixed
	 * @param array $options
	 * @return Assetic\Asset\AssetInterface;
	 */
	public function parseInput($input, $filter = array(), $options = array())
	{
		if (!isset($options['root'])) {
			$options['root'] = array();
		}

		if ('@' == $input[0]) {
            $asset =  $this->createAssetReference(substr($input, 1));
     	
     	// possible http asset ?  
        } else if (false !== strpos($input, '://') || 0 === strpos($input, '//')) {
            $asset =  $this->createHttpAsset($input);
        
        // so, just filepath given ?
        } else {

	        if (self::isAbsolutePath($input)) {
	            if ($root = self::findRootDir($input, $options['root'])) {
	                $path = ltrim(substr($input, strlen($root)), '/');
	            } else {
	                $path = null;
	            }
	        } else {
	            $root  = $this->root;
	            $path  = $input;
	            $input = $this->root.'/'.$path;
	        }

	        if (false !== strpos($input, '*')) {
	            $asset =  $this->createGlobAsset($input, $root);
	        } else {
		        $asset =  $this->createFileAsset($input, $root, $path);
	        }
        }
        
        $filter = $this->parseInputFilter($filter);

        if ($filter instanceof FilterInterface) {
	        $asset->ensureFilter($filter);
        }

        return $asset;
	}

	/**
	 * Parse input  filter.
	 *
	 * @param string $filter
	 * @return mixed
	 */
	protected function parseInputFilter($filter)
	{
		if ( isset($filter[0]) and '@' == $filter[0]) {
			$filter = $this->filterManager->get(substr($filter, 1));
		}

		return $filter;
	}

	protected function createAssetReference($name)
    {
        return new AssetReference($this, $name);
    }

	protected function createHttpAsset($sourceUrl, $vars = array())
    {
        return new HttpAsset($sourceUrl, array(), false, $vars = array());
    }

    protected function createGlobAsset($glob, $root = null, $vars = array())
    {
        return new GlobAsset($glob, array(), $root, $vars);
    }

    protected function createFileAsset($source, $root = null, $path = null, $vars = array())
    {
    	// we'll use ensure filter to add filter to the asset.
        return new FileAsset($source, array(), $root, $path, $vars = array());
    }

    private static function isAbsolutePath($path)
    {
        return '/' == $path[0] || '\\' == $path[0] || (3 < strlen($path) && ctype_alpha($path[0]) && $path[1] == ':' && ('\\' == $path[2] || '/' == $path[2]));
    }

    /**
     * Loops through the root directories and returns the first match.
     *
     * @param string $path  An absolute path
     * @param array  $roots An array of root directories
     *
     * @return string|null The matching root directory, if found
     */
    private static function findRootDir($path, array $roots = array())
    {
        foreach ($roots as $root) {
            if (0 === strpos($path, $root)) {
                return $root;
            }
        }
    }
}