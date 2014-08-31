<?php namespace Drafterbit\Core;

use Partitur\Traits\ResourcesAccessorTrait;

abstract class Widget implements WidgetInterface {
	use ResourcesAccessorTrait;

	/**
	 * Widget config.
	 *
	 * @var array
	 */
	protected $config = array();
	
	/**
	 * Run the widget.
	 *
	 * @return string
	 */
	public function run(){}

	/**
	 * Get or set config value;
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function config($name = null, $value = null)
	{
		// if no arg passed, just return the config
		if(is_null($name) and is_null($value)) {
			return $this->config;
		}

		if(is_array($name)) {
			return $this->config = $name;
		}

		if(is_null($value)) {
			return isset($this->config[$name]) ? $this->config[$name] : false;
		}

		return $this->config[$name] = $value;
	}
}