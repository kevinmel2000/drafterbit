<?php namespace Drafterbit\Extensions\System\Asset;

use Drafterbit\Component\Asset\AssetManager as BaseManager;
use Drafterbit\Component\Asset\AssetCollection;
use Drafterbit\Component\Asset\FileAsset;
use Drafterbit\Component\Asset\AssetReference;

use Drafterbit\Component\Asset\Filter\FilterManager;
use Drafterbit\Component\Asset\Filter\FilterInterface;
use Drafterbit\Component\Asset\Filter\CSSMinFilter;
use Drafterbit\Component\Asset\Filter\JSMinFilter;

class AssetManager extends BaseManager {

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