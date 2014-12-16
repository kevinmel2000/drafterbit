<?php namespace Drafterbit\Extensions\Files;

use Drafterbit\Framework\Application;

class FilesExtension extends \Drafterbit\Framework\Extension {

	public $controllers = ['admin'];
	
	public function register(Application $app)
	{
		$app['helper']->register('files', $this->getResourcesPath('helpers/files.php'));
		$app['helper']->load('files');
	}

	function getShortcuts()
	{
		return [
			['href' => admin_url('files'), 'icon' => 'fa fa-upload','label' => 'Upload']
		];
	}
}