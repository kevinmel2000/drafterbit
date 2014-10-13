<?php namespace Drafterbit\Extensions\System\Controllers;

use Drafterbit\Extensions\System\BaseController;
class Admin extends BaseController {
	
	public function dashboard()
	{
		$this->get('asset')->css($this->publicPath('css/index.css'));

		$header = $this->header(__('Dashboard'));

		$view = $this->render('@system/admin/dashboard');

		return $this->wrap($header.$view);
	}
}