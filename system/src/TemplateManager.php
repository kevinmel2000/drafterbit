<?php namespace Drafterbit\System;

use Drafterbit\Component\Template\TemplateManager as Base;

class TemplateManager extends Base {

	public $asset;

	public function __construct(Asset\AssetManager $asset)
	{
		$this->asset = $asset
	}
}