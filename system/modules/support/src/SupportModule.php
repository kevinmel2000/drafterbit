<?php namespace Drafterbit\Modules\Support;

use Drafterbit\Framework\Application;
use Monolog\Logger;
use Drafterbit\Modules\Support\Monolog\DoctrineDBALHandler;
use Drafterbit\Modules\Admin\Models\Setting;

class SupportModule extends \Drafterbit\Framework\Module {

	public function register(Application $app)
	{
		$app['helper']->register('support', $this->getResourcesPath().'helpers/support.php');
		$app['helper']->register('twig', $this->getResourcesPath().'helpers/twig.php');
		$app['helper']->load('support');
		$app['helper']->load('twig');
	}

	function registerEventListener()
	{
		return [
			'boot' => 'onBoot',
			'pre.config' => 'onPreConfig',
		];
	}

	function onPreConfig()
	{
		$config = $this->get('user_config')->get('config');
		// admin base
		defined('ADMIN_BASE') or define('ADMIN_BASE', $config['path.admin']);
	}

	function onBoot()
	{
		$config = $this->get('user_config')->get('config');
		
		//theme
		$theme = $this->getTheme();
		$this->app['path.themes'] = $config['path.theme'].'/';
		$this->app['themes']->current($theme);
		$this->app['themes']->registerAll();
		

		$this->app['path.theme'] = $this->app['path.themes'].$this->app['themes']->current().'/';

		$this->app['log.db'] = function(){
			$logger =  new Logger('db.log');
			$logger->pushHandler(new DoctrineDBALHandler($this->app['db']));
			return $logger;
		};

		$this->app['log.db']->pushProcessor(function ($record) {
		    $record['formatted'] = "%message%";
		    return $record;
		});
	}

	protected function getTheme()
	{
		if( ! $this->app['cache']->contains('settings') ) {

			$model = new Setting;
			$this->app['cache']->save('settings', $model->all());
		}

		$config = $this->app['cache']->fetch('settings');

		return $config['theme'];
	}
}