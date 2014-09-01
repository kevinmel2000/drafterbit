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
			$assetPath = $c['config']['asset.path'];
			return new AssetManager($cachePath.'/asset/', $c['path.public'].$assetPath, $c['debug']);
		};
	}

	public function boot()
	{
		// assets
		foreach (app('config')->get('asset.assets') as $name => $value) {
			app('asset')->register($name, $value);
		}

		app('asset')->getFilterManager()->set('fontawesome', new FaFilter(app('config')->get('asset.path')));
		app('asset')->getFilterManager()->set('chosen_css', new ChosenFilter(app('config')->get('asset.path')));
	}
}