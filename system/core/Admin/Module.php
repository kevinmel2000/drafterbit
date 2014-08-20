<?php namespace Drafterbit\Admin;

use Partitur\Application;

class Module extends \Partitur\Module {

	public $controllers = ['admin', 'asset', 'setting'];

	public function register(Application $app)
	{
		// add helper
		$app['helper']->register('message', $this->getResourcesPath().'helpers/message.php');
		$app['helper']->register('admin', $this->getResourcesPath().'helpers/admin.php');
		$app['helper']->load('message');
		$app['helper']->load('admin');
	}
}