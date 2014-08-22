<?php namespace Drafterbit\Modules\Files;

use Partitur\Application;

class FilesModule extends \Partitur\Module {

	public $controllers = ['admin'];
	
	public function register(Application $app)
	{
		$app['helper']->register('files', $this->getResourcesPath().'helpers/files.php');
		$app['helper']->load('files');
	}
}