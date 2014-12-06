<?php namespace Drafterbit\Extensions\System\Controllers;

use Drafterbit\Extensions\System\BackendController;

class Menus extends BackendController {

	function index()
	{
		$currentTheme = $this->get('themes')->get();

		$positions = $currentTheme['menus'];

		$model = $this->model('@system\\Menus');

		$theme = $this->get('themes')->current();

		foreach ($positions as $position) {		
			$menus[$position] = $model->getByThemePosition($theme, $position);
		}

		$data['positions'] = $positions;
		$data['menus'] = $menus;
		
		$data['title'] = __('Menus');
		return $this->render('@system/setting/themes/menus', $data);
	}
}