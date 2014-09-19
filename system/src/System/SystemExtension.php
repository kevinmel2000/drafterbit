<?php namespace Drafterbit\CMS\System;

use Drafterbit\Framework\Application;
use Monolog\Logger;
use Drafterbit\CMS\System\Monolog\DoctrineDBALHandler;
use Drafterbit\CMS\System\Assetic\DrafterbitFontAwesomeFilter as FaFilter;
use Drafterbit\CMS\System\Assetic\DrafterbitChosenFilter as ChosenFilter;

class SystemExtension extends \Drafterbit\Framework\Extension {

	function boot()
	{
		$this['helper']->register('support', $this->getResourcesPath('helpers/support.php'));
		$this['helper']->register('twig', $this->getResourcesPath('helpers/twig.php'));
		$this['helper']->load('support');
		$this['helper']->load('twig');

		// assets
		foreach (app('config')->get('asset.assets') as $name => $value) {
			app('asset')->register($name, $value);
		}

		app('asset')->setCachePath($this['config.cms']['path.cache'].'/asset');

		app('asset')->getFilterManager()->set('fontawesome', new FaFilter(app('config')->get('asset.path')));
		app('asset')->getFilterManager()->set('chosen_css', new ChosenFilter(app('config')->get('asset.path')));

		//theme
		$config = $this['config.cms'];

		$theme = $this->getTheme();
		$this['path.themes'] = $config['path.theme'].'/';
		$this['themes']->current($theme);
		$this['themes']->registerAll();
		

		$this['path.theme'] = $this['path.themes'].$this['themes']->current().'/';

		$this['log.db'] = function(){
			$logger =  new Logger('db.log');
			$logger->pushHandler(new DoctrineDBALHandler($this['db']));
			return $logger;
		};

		$this['log.db']->pushProcessor(function ($record) {
		    $record['formatted'] = "%message%";
		    return $record;
		});
	}

	private function getTheme()
	{
		if( ! $this['cache']->contains('settings') ) {

			$model = new Models\Setting;
			$this['cache']->save('settings', $model->all());
		}

		$settings = $this['cache']->fetch('settings');

		return $settings['theme'];
	}
}