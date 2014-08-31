<?php namespace Drafterbit\Modules\Files;

use Drafterbit\Framework\Application;

class FilesModule extends \Drafterbit\Framework\Module {

	public $controllers = ['admin'];
	
	public function register(Application $app)
	{
		$app['helper']->register('files', $this->getResourcesPath().'helpers/files.php');
		$app['helper']->load('files');
	}
}