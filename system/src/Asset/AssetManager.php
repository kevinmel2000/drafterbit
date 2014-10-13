<?php namespace Drafterbit\System\Asset;

use Drafterbit\System\Asset\Filter\FilterManager;
use Drafterbit\System\Asset\Filter\FilterInterface;
use Drafterbit\System\Asset\Filter\CSSMinFilter;
use Drafterbit\System\Asset\Filter\JSMinFilter;

class AssetManager {

    protected $assets = array();
    protected $debug;
    protected $name = array();
    protected $cachePath;
    protected $collections = array();
    protected $filterManager;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct($cachePath = null, $debug = true)
    {
        $this->cachePath = $cachePath;
        $this->debug = $debug;
        $this->filterManager = new FilterManager();
    }

    /**
     * Write content to a file.
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
     * Register an asset from given string
     *
     * @param string $name
     * @param mixed  $asset
     * @param array  $filter
     *
     * @throws \InvalidArgumentException If the asset name is invalid
     */
    public function register($name, $asset, $filter = array(), $options = array())
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
     * Create an asset collection
     *
     * @return Drafterbit\System\Asset\AssetCollection
     */
    public function create($type)
    {
        $this->collections[$type] = new AssetCollection;

        return $this;
    }

    /**
     * Add an asset to collection for fump.
     *
     * @param string $type
     */
    public function add($type, $name, $filter = array(), $options = array())
    {
        if(is_array($name)) {
            foreach ($name as $nm) {
                $this->add($type, $nm, $filter, $options);
            }
        }

        if(!isset($this->collections[$type])) {
            $this->create($type);
        }

        $asset = $this->parseInput($name, $filter);

        $this->collections[$type]->add($asset, $filter, $options);
        return $this;
    }

    /**
     * Dump asset
     *
     * @return string
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
            throw new \Exception("Asset cache path is not been set yet.
                You nedd to use setCachePath() once before use dumpToFile()");
        }

        $fileName = isset($this->name[$type]) ?
            $this->name[$type] : $this->createFileName($this->collections[$type]->all());
            
            $dest = $this->cachePath."/$type/".$fileName.".$type";

        if($this->debug) {
            $this->write($dest, $this->dump($type));
        
        } else {

            // @todo fixes  double css asset dumps, not because the filter,
            // seems like because the check;
            if(!file_exists($dest)) {
                $this->write($dest, $this->dump($type, $lastFilter));
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
        return sha1(serialize($asset));
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
    public function parseInput($input, $filter = array())
    {
        if ('@' == $input[0]) {
            $asset =  $this->createAssetReference(substr($input, 1));
             
        } else {
            $asset =  $this->createFileAsset($input);
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

    protected function createFileAsset($source, $root = null, $path = null, $vars = array())
    {
        // we'll use ensure filter to add filter to the asset.
        return new FileAsset($source, array());
    }

    /**
     * Gets an asset by name.
     *
     * @param string $name The asset name
     *
     * @return AssetInterface The asset
     *
     * @throws \InvalidArgumentException If there is no asset by that name
     */
    public function get($name)
    {
        if (!isset($this->assets[$name])) {
            throw new \InvalidArgumentException(sprintf('There is no "%s" asset.', $name));
        }

        return $this->assets[$name];
    }

    /**
     * Checks if the current asset manager has a certain asset.
     *
     * @param string $name an asset name
     *
     * @return Boolean True if the asset has been set, false if not
     */
    public function has($name)
    {
        return isset($this->assets[$name]);
    }

    /**
     * Set name
     */
    public function setName($type, $name)
    {
        $this->name[$type] = $name;
    }

    /**
     * Registers an asset to the current asset manager.
     *
     * @param string         $name  The asset name
     * @param AssetInterface $asset The asset
     *
     * @throws \InvalidArgumentException If the asset name is invalid
     */
    public function set($name, AssetInterface $asset)
    {
        $this->assets[$name] = $asset;
    }

    /**
     * Returns an array of asset names.
     *
     * @return array An array of asset names
     */
    public function getNames()
    {
        return array_keys($this->assets);
    }

    /**
     * Clears all assets.
     */
    public function clear()
    {
        $this->assets = array();
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
        $this->setName('js', $name);
    }

    /**
     * Asset file name.
     *
     * @param string
     */
    public function setCSSName($name)
    {
        $this->setName('css', $name);
    }
}