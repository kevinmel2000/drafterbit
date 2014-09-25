<?php namespace Drafterbit\Extensions\Admin;

use Monolog\Logger;
use Drafterbit\Extensions\System\Monolog\DoctrineDBALHandler;


class AdminExtension extends \Drafterbit\Framework\Extension {

	function boot()
	{
		$this['helper']->register('message', $this->getResourcesPath('helpers/message.php'));
		$this['helper']->register('admin', $this->getResourcesPath('helpers/admin.php'));
		$this['helper']->load('message');
		$this['helper']->load('admin');

		app('asset')->setCachePath($this['config']['path.cache'].'/asset');

		//theme
		$config = $this['config'];

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
		$system = $this['cache']->fetch('system');

		return $system['theme'];
	}

}