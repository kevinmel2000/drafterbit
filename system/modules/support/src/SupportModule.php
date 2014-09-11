<?php namespace Drafterbit\Modules\Support;

use Drafterbit\Framework\Application;
use Monolog\Logger;
use Drafterbit\Modules\Support\Monolog\DoctrineDBALHandler;
use Drafterbit\Modules\Admin\Models\Setting;
use Drafterbit\Modules\Support\Assetic\DrafterbitFontAwesomeFilter as FaFilter;
use Drafterbit\Modules\Support\Assetic\DrafterbitChosenFilter as ChosenFilter;

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
		foreach (app()->getModules() as $name => $module) {

			if (is_dir( $path = $module->getTemplatePath())) {
				app('template')->addPath($name, $path);
			}
		}

		// assets
		foreach (app('config')->get('asset.assets') as $name => $value) {
			app('asset')->register($name, $value);
		}

		app('asset')->getFilterManager()->set('fontawesome', new FaFilter(app('config')->get('asset.path')));
		app('asset')->getFilterManager()->set('chosen_css', new ChosenFilter(app('config')->get('asset.path')));

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