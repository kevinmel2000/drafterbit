<?php namespace Drafterbit\Extensions\Installer\Controllers;

use Drafterbit\Framework\Controller;

class Installer extends Controller {
	
	public function index()
	{	
		$start = $this->getExtension()->getStart();
		// @todo: set start before installing
		set('start', $start);
		set('preloader', base_url($this->get('dir.content').'/cache/img/preloader.GIF'));

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

			$db = $this->get('session')->get('install_db');
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
	 		
	 		if(is_writable($dest)) {
		 		file_put_contents($dest, $content);
	 		} else {
	 			$config = $content;
				return json_encode(['config' => $config]);
	 		}
		
		} catch(\Exception $e) {
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
		$admin = $this->get('session')->get('install_admin');
 		
 		$this->get('config')->load($this->get('path.install').'/config.php');

 		$config = $this->get('config');
 		$extMgr = $this->get('extension.manager');

 		// migrations	
 		foreach ($extMgr->getCoreExtension() as $extension) {
 			
 			// add and return the extension
 			$ext = $extMgr->load($extension);
 			if(is_dir($ext->getResourcesPath('migrations'))) {
	 			$this->get('migrator')->create($ext->getResourcesPath('migrations'))->run();
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