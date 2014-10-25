<?php namespace Drafterbit\Extensions\System;


use Drafterbit\Framework\Application;

class SystemExtension extends \Drafterbit\Framework\Extension {

	function boot()
	{
		foreach (['form', 'support', 'twig'] as $helper) {
			$this['helper']->register( $helper, $this->getResourcesPath("helpers/$helper.php"));
			$this['helper']->load($helper);
		}

		//theme
		$config = $this['config'];

		$theme = $this->getTheme();

		$this['path.themes'] = $this['path.content'].'themes/';
		
		$this['themes']->current($theme);
		
		$this['themes']->registerAll();

		$this['path.theme'] = $this['path.themes'].$this['themes']->current().'/';		
	}

	private function getTheme()
	{
		$system = $this['cache']->fetch('system');

		return $system['theme'];
	}
}