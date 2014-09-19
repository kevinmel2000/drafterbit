<?php namespace Drafterbit\CMS\Installer\Controllers;

use Drafterbit\Framework\Controller;

class Installer extends Controller {
	
	public function install()
	{
		//$this->get('asset');
		return $this->render('install');
	}
}