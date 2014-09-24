<?php namespace Drafterbit\Extensions\System;

use Drafterbit\Framework\Application;
use Monolog\Logger;
use Drafterbit\Extensions\System\Assetic\DrafterbitFontAwesomeFilter as FaFilter;
use Drafterbit\Extensions\System\Assetic\DrafterbitChosenFilter as ChosenFilter;

class SystemExtension extends \Drafterbit\Framework\Extension {

	function boot()
	{
		$this['helper']->register('form',$this->getResourcesPath('helpers/form.php'));
		$this['helper']->register('support', $this->getResourcesPath('helpers/support.php'));
		$this['helper']->register('twig', $this->getResourcesPath('helpers/twig.php'));
		
		$this['helper']->load('form');
		$this['helper']->load('support');
		$this['helper']->load('twig');

		// assets
		foreach (app('config')->get('asset.assets') as $name => $value) {
			app('asset')->register($name, $value);
		}

		app('asset')->getFilterManager()->set('fontawesome', new FaFilter(app('config')->get('asset.path')));
		app('asset')->getFilterManager()->set('chosen_css', new ChosenFilter(app('config')->get('asset.path')));

		//theme
		$config = $this['config'];

		$theme = $this->getTheme();
		$this['path.themes'] = $config['path.theme'].'/';
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