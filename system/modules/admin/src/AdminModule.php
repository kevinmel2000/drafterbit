<?php namespace Drafterbit\Modules\Admin;

use Drafterbit\Framework\Application;

class AdminModule extends \Drafterbit\Framework\Module {
	
	public function register(Application $app)
	{
		// add helper
		$app['helper']->register('message', $this->getResourcesPath().'helpers/message.php');
		$app['helper']->register('admin', $this->getResourcesPath().'helpers/admin.php');
		$app['helper']->load('message');
		$app['helper']->load('admin');
	}
}