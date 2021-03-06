<?php namespace Drafterbit\Extensions\Files;

use Drafterbit\Framework\Application;

class FilesExtension extends \Drafterbit\Framework\Extension
{
 	public function getNav()
 	{
	 	return [
	            [ 'id'=>'files', 'parent' =>'content', 'label' => 'Files', 'href' => 'files', 'order' => 2],
	    ];
 	}

 	public function getPermissions()
 	{
	    return [
	        'files.view'   => 'view files',
	        'files.create' => 'upload files or create folder',
	        'files.delete' => 'delete files',
	        'files.move'   => 'move or rename files',
	    ];
 	}

    public function register(Application $app)
    {
        $app['helper']->register('files', $this->getResourcesPath('helpers/files.php'));
        $app['helper']->load('files');
    }
}
