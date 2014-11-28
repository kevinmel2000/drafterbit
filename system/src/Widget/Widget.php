<?php namespace Drafterbit\System\Widget;

use Drafterbit\Framework\RootTrait;
use Drafterbit\Framework\ExtensionTrait;
use Drafterbit\Framework\Controller;

abstract class Widget extends Controller implements WidgetInterface {
	use RootTrait, ExtensionTrait;

	/**
	 * Widget UI Builder.
	 *
	 * @var WidgetUIBuilder
	 */
	protected $uiBuilder;

	/**
	 * Widget config.
	 *
	 * @var array
	 */
	protected $config = array();

	/**
	 * Widget data.
	 *
	 * @var array
	 */
	public $data = array();
	
	/**
	 * Run the widget.
	 *
	 * @return string
	 */
	abstract function run();

	/**
	 * Widget construction.
	 */
	public function __construct(WidgetUIBuilder $uiBuider = null)
	{
		$this->uiBuider = is_null($uiBuider) ? new WidgetUIBuilder : $uiBuider;
	}

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

	/**
	 * Set widget data.
	 *
	 * @param array $data
	 */
	public function setData($data)
	{
		$this->data = $data;
	}

	/**
	 * Return user interface of the widget.
	 *
	 * @return string
	 */
	public function ui()
	{
		return $this->uiBuider->build($this);
	}
}