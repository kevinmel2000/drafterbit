<?php namespace Drafterbit\Admin\Models;

class Menu {

	public $label;
	public $href = '#';
	public $children = array();

	public function __construct($label, $href = '#')
	{
		$this->label = $label;
		$this->href = $href;
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