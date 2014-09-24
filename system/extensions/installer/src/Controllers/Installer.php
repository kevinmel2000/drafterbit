<?php namespace Drafterbit\Extensions\Installer\Controllers;

use Drafterbit\Framework\Controller;

class Installer extends Controller {
	
	public function index()
	{
		$asset = $this->get('asset')
		->css('@bootstrap_css')
		->css('@bootstrap_validator_css')
		->js('@jquery')
		->js('@bootstrap_js')
		->js('@bootstrap_validator_js')
		->js('@jquery_form')
		->js($this->assetPath('js/install.js'));

		$start = $this->getExtension()->getStart();
		set('css', $asset->dump('css'));
		set('js', $asset->dump('js'));
		
		// @todo: set start before installing
		set('start', $start);

		return $this->render('install', $this->getData());
	}

	public function check()
	{
		$message = 'ok';
		$config = $this->get('input')->post('database');

		$this->get('config')->set('database', $config);

		try {
			
			$this->get('db')->connect();
		
		} catch(\PDOException $e) {
			if( in_array($e->getCode(), ['1045', '1044'])) {
				$message = "Database Access Denied";
			} else if('1049' == $e->getCode()) {
				$message = "Unknown Database";
			} else {
				$message = $e->getMessage();
			}
		}

		return json_encode(['message' => $message]);
	}

	public function install()
	{
		//
	}
}