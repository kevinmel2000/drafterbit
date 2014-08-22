<?php namespace Drafterbit\Modules\Support\Providers;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class YamlServiceProvider implements ServiceProviderInterface {

	public function register(Container $app)
	{
		$app['yaml'] = function(){
			return new \Symfony\Component\Yaml\Parser;
		};
	}
}