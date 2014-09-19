<?php namespace Drafterbit\CMS\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Doctrine\DBAL\DriverManager;

class DatabaseServiceProvider implements ServiceProviderInterface {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register(Container $app)
	{
		$dbConfig = $app['config.cms']['database'];

		$dbConfig['wrapperClass'] = 'Drafterbit\\Component\\Database\\Connection';
		
		$app['db'] = function($app) use ($dbConfig){
			return DriverManager::getConnection($dbConfig);
		};
	}
}