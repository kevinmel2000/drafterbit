<?php namespace Drafterbit\Extensions\System\Controllers;

use Drafterbit\Extensions\System\BaseController;

class Admin extends BaseController {
	
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

		// site info
		$data['os'] = $this->getOs();
		$data['diskSpace'] = disk_total_space($this->get('path.install'));
		$data['usersCount'] = count($this->get('cache')->fetch('users'));
		return $this->render('@system/admin/dashboard', $data);
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
	            $os_platform    =   $value;
	        }

	    }   

	    return $os_platform;
	}
}