<?php namespace Drafterbit\Extensions\System\Controllers;

use Drafterbit\Extensions\System\BackendController;
use Symfony\Component\HttpFoundation\Response;

class System extends BackendController {

	public function dashboard()
	{
		$data['title'] = __('Dashboard');

		$logs = $this->model('@system\Log')->recent();

		foreach ($logs as &$log) {
			$name = $log->user_name;

			if($log->user_id == $this->get('session')->get('user.id')) {
				$name = __('You');
			}

			$userUrl = admin_url('user/edit/'.$log->user_id);

			$log->formattedMsg = '<a href="'.$userUrl.'">'.$name.'</a> '.$log->message;
		}

		$data['logs'] = $logs;

		$data['shortcuts'] = $this->get('app')->getShortcuts();
		// site info
		$data['os'] = $this->getOs();
		$data['usersCount'] = count($this->get('cache')->fetch('users'));
		$data['db'] = $this->get('db')->getServerVersion();
		return $this->render('@system/dashboard', $data);
	}

	private function getOs()
	{
		$os_platform = "Unknown";

    	$os_array = array(
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );

	    foreach ($os_array as $regex => $value) {
	        if (preg_match($regex, $this->get('input')->server('HTTP_USER_AGENT'))) {
	            $os_platform = $value;
	        }
	    }

	    return $os_platform;
	}

	public function log()
	{
		$action = $this->get('input')->post('action');
		$logIds = $this->get('input')->post('log');

		switch($action) {
			case "delete":
				if($logIds) {
					foreach ($logIds as $id) {
						$this->model('@system\Log')->delete($id);
					}
					$msg = 'Logs deleted !';
					set('messages', [['text' => $msg, "type" => 'success']]);
				}
				break;
			case "clear":
				$this->model('@system\Log')->clear();
				$msg = 'Logs cleared !';
				set('messages', [['text' => $msg, "type" => 'success']]);
			default:
				break;
		}


		$logs = $this->model('@system\Log')->all();

		set('title', __('Logs'));
		set('id', 'log');

		$tableHead = array(
			['field' => 'time', 'width' => '20%', 'label' => 'Time', 'format' => function($val, $item) {
				return date('d-m-Y H:i:s', $val);
			}],
			['field' => 'message', 'label' => 'Message', 'format' => function($val, $item){
				$name = $item->user_name;

				if($item->user_id == $this->get('session')->get('user.id')) {
					$name = __('You');
				}

				$userUrl = admin_url('user/edit/'.$item->user_id);

				return '<a href="'.$userUrl.'">'.$name.'</a> '.$item->message;
			}]
		);
		set('logTable', $this->datatables('log', $tableHead, $logs));

		return $this->render('@system/log', $this->getData());
	}

	public function cache()
	{
		$post = $this->get('input')->post();

		if (isset($post['action']) and ($post['action'] == 'delete') and isset($post['cache'])) {

			foreach($post['cache'] as $key ) {
				$this->get('cache')->delete($key);
			}

			message('Cache deleted !', 'success');
		}

		$model = $this->model('cache');

		$caches = $model->getAll();

		$tableHead = array(
			['field' => 'name', 'label' => 'Name'],
			['field' => 'size', 'label' => 'Filesize']
		);

		$cacheTable = $this->datatables('cache', $tableHead, $caches);

		set('id','cache');
		set('title',__('Cache'));
		set('cacheTable', $cacheTable);

		return $this->render('@system/cache', $this->getData());
	}

	public function drafterbitJs()
	{
		return new Response($this->render('@system/drafterbitjs'), Response::HTTP_OK, array(
			'content-type' => 'application/javascript'
		));
	}
}