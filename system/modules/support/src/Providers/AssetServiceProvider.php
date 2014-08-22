<?php namespace Drafterbit\Modules\Support\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Drafterbit\Modules\Support\Assetic\AssetManager;
use Drafterbit\Modules\Support\Assetic\DrafterbitFontAwesomeFilter as FaFilter;
use Drafterbit\Modules\Support\Assetic\DrafterbitChosenFilter as ChosenFilter;

class AssetServiceProvider implements ServiceProviderInterface {

	function register( Container $app)
	{
		
		$app['asset'] = function($c) {
			$cachePath = $c['user_config']->get('config.path.cache');
			return new AssetManager($cachePath.'/assets/', $c['path'].'plugins/', $c['debug']);
		};
	}

	public function boot()
	{
		// assets
		foreach (app('config')->get('asset.assets') as $name => $value) {
			app('asset')->register($name, $value);
		}

		app('asset')->getFilterManager()->set('fontawesome', new FaFilter);
		app('asset')->getFilterManager()->set('chosen_css', new ChosenFilter);
	}
}