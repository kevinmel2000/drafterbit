<?php namespace Drafterbit\Core;

use Partitur\Traits\ResourcesAccessorTrait;

abstract class Widget implements WidgetInterface {

	use ResourcesAccessorTrait;
	
	public function run(){}
}