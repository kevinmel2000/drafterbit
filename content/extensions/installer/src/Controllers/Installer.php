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
		->js($this->publicPath('js/install.js'));

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
			$this->get('session')->set('install_db', $config);
		
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

	public function admin()
	{
		$admin = $this->get('input')->post('admin');
		$this->get('session')->set('install_admin', $admin);

		// @todo validation admin email and password
		return json_encode(['message' => 'ok']);
	}

	public function install()
	{
		$site = $this->get('input')->post('site');
		$db = $this->get('session')->get('install_db');
		$admin = $this->get('session')->get('install_admin');

		$stub = $this->getExtension()->getResourcesPath('stub/config.php.stub');

		$string = file_get_contents($stub);

		$config = array(
			'%db.driver%' => $db['driver'],
 			'%db.host%' => $db['host'],
 			'%db.user%' => $db['user'],
 			'%db.pass%' => $db['password'],
 			'%db.name%' => $db['dbname'],
 			'%db.prefix%' => $db['prefix']
 		);

 		$content = strtr($string, $config);
 		$dest = $this->get('path.install').'/config.php';
 		if(file_put_contents($dest, $content)) {
 			$this->get('config')->load($dest);
 		}

 		$config = $this->get('config');
 		$extMgr = $this->get('extension.manager');
 		$extMgr->addPath($this->get('path.install').$config['path.extension']);
		$extMgr->refreshInstalled();

 		// table creations 		
 		foreach ($extMgr->getCoreExtension() as $extension) {
 			$extMgr->load($extension);
 			if(method_exists($this->getExtension($extension), 'createTables')) {
 				$this->getExtension($extension)->createTables();
 			}
 		}

 		$model = $this->model('Installer');
 		
 		//add first user(admin)
 		$adminIds = $model->createAdmin($admin['email'], $admin['password']);

 		foreach ($extMgr->getInstalled() as $name => $extconfig) {
 			if(isset($extconfig['permissions'])) {
 				$model->addPermission($name, $extconfig['permissions']);
 			}
 		}

 		$model->addAdminPermission($adminIds['groupId']);

 		//add system default
 		$model->systemInit($site['name'], $site['desc'], $admin['email'], $adminIds['userId']);
	}
}