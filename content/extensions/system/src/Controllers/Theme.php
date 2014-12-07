<?php namespace Drafterbit\Extensions\System\Controllers;

use Drafterbit\Extensions\System\BackendController;

class Theme extends BackendController {
	
	public function index()
	{
		$this->setting = $this->model('@system\System');

		$cache = $this->get('cache');
		$post = $this->get('input')->post();

		if($post) {
			$this->setting->updateTheme($post['theme']);

			$cache->delete('settings');
			
			message('Theme updated', 'success');
		}

		// @todo
		$settings = $this->setting->all();

		set('currentTheme', $settings['theme']);

		$themesDir = $this->get('path.themes');
		$themes = $this->get('themes')->all();

		set('themes', $themes);
		set('id', 'themes');
		set('title', __('Themes'));

		return $this->render('@system/setting/appearance', $this->getData());
	}

	public function customize()
	{
		$currentTheme = $this->get('themes')->get();

		$positions = $currentTheme['widgets'];

		if(!isset($currentTheme->widget->position)) {
			//return 'Current theme does not support widget';
		}

		$model = $this->model('widget');

		foreach ($positions as $position) {
			$widgets[$position] = $model->widget($position);
		}


		foreach ($widgets as $name => $arrayOfWidget) {

			foreach ($arrayOfWidget as $widget) {

			$widgetObj = $this->get('widget')->get($widget->name);

				$widget->ui = $this->get('widget.ui')->build($widgetObj);
			}
		}

		$menuPositions = $currentTheme['menus'];

		$menuModel = $this->model('@system\\Menus');

		$theme = $this->get('themes')->current();

		foreach ($menuPositions as $position) {		
			$menus[$position] = $menuModel->getByThemePosition($theme, $position);
		}

		$availableWidget = $this->get('widget')->all();

		$data['availableWidget'] = $availableWidget;
		$data['menuPositions'] = $menuPositions;
		$data['widgetPositions'] = $positions;
		$data['widgets'] = $widgets;
		$data['menus'] = $menus;

		return $this->render('@system/setting/customize', $data);
	}

	public function customPreview()
	{
		$frontpage = $this->get('app')->getFrontpage();
        $system = $this->model('@system\System')->all();
        $homepage = $system['homepage'];
        $route = $frontpage[$homepage];

        $controller = $this->get('app')->createController($route['controller']);

        return call_user_func_array($controller, $route['defaults']);
	}
}