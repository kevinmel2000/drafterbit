<?php namespace Drafterbit\Modules\Admin\Models;

class Menu {

	public $label;
	public $href = '#';
	public $id;
	public $class;
	public $children = array();

	public function __construct($label, $href, $id, $class)
	{
		$this->label = $label;
		$this->href = $href;
		$this->id = $id;
		$this->class = $class;
	}

	public function getChildren()
	{
		return $this->children;
	}

	public function hasChildren()
	{
		return count($this->children) > 0;
	}
}