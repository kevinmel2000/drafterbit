<?php namespace Drafterbit\Extensions\System\Controllers;

use Drafterbit\Extensions\System\BaseController;
class Admin extends BaseController {
	
	public function dashboard()
	{
		$data['title'] = __('Dashboard');
		
		return $this->render('@system/admin/dashboard', $data);
	}
}