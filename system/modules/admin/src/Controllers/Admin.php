<?php namespace Drafterbit\Modules\Admin\Controllers;

use Drafterbit\Modules\Admin\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class Admin extends BaseController {
	
	public function dashboard()
	{
		$this->get('asset')->css($this->assetPath('css/index.css'));

		$header = $this->model('UI')->header('Dashboard', 'Statistic and more');
		$view = $this->render('@admin/admin/dashboard');

		return $this->wrap($header.$view);
	}
}