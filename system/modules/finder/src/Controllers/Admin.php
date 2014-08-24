<?php namespace Drafterbit\Modules\Finder\Controllers;

use Drafterbit\Modules\Support\Controller as BaseController ;
use Drafterbit\Modules\User\Models\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class Admin extends BaseController {

	public function __construct(Auth $auth)
	{
		$auth->authenticate();
	}

	public function browser()
	{
		$this->get('asset')
			->css('@bootstrapcss')
			->css('@fontawesome', '@fontawesome')
			->css($this->assetPath('css/openfinder.css'))

			->js('@jquery')
			->js('@jquery_form')
			->js('@bootstrapjs')
			->js('@bootstrap_contextmenu')
			->js($this->assetPath('js/openfinder.js'))
			->js($this->assetPath('js/browser.js'));
		
		$js = $this->get('asset')->writeJs();		
		$css = $this->get('asset')->writeCSS();

		set('js', base_url('admin/asset/js/'.$js.'.js'));
		set('css', base_url('admin/asset/css/'.$css.'.css'));
		return $this->render($this->getTemplate(), $this->getData());
	}

	public function data()
	{
		$op = $this->get('input')->get('op');
		$path = $this->get('input')->get('path');
		
		$res = new JsonResponse;

		try {

			$data = array();

			switch ($op) {
				case 'ls':
					$data = $this->get('ofinder')->ls($path);
					break;
				case 'delete':
					$data = $this->get('ofinder')->delete($path);
					break;
				default:
	 				# code...
					break;
			}

			if($files = $this->get('input')->files('files', array())) {
				$path = $this->get('input')->post('path');
				$data = $this->get('ofinder')->upload($path, $files);
			}

		} catch (\Exception $e) {
			$data = array( 'message' => $e->getMessage());
		}	
		
		$res->setData($data);

		return $res;
	}

}