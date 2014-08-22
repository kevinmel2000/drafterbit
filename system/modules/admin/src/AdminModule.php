<?php namespace Drafterbit\Modules\Admin;

use Partitur\Application;

class AdminModule extends \Partitur\Module {
	
	public function register(Application $app)
	{
		// add helper
		$app['helper']->register('message', $this->getResourcesPath().'helpers/message.php');
		$app['helper']->register('admin', $this->getResourcesPath().'helpers/admin.php');
		$app['helper']->load('message');
		$app['helper']->load('admin');
	}
}