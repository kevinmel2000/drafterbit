<?php namespace Drafterbit\Extensions\System\Controllers\Setting;

use Drafterbit\Extensions\System\BackendController;

class Themes extends BackendController {

	public function index()
	{
		$this->setting = $this->model('@system\System');

		$cache = $this->get('cache');
		$post = $this->get('input')->post();

		if($post) {
			$this->setting->updateTheme($post['theme']);

			$cache->delete('settings');
			
			message('Theme updated', 'success');

			//$msg['text'] = 'Theme updated successfully';
			//$msg['type'] = 'success';
			//$this->get('session')
			//	->getFlashBag()->set('message', $msg);
			//return redirect(base_url("admin/setting/themes"));
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

	/**
	 * Widget setting
	 */
	public function widget()
	{
		$currentTheme = $this->get('themes')->get();

		$positions = $currentTheme['widget']['position'];

		if(!isset($currentTheme->widget->position)) {
			//return 'Current theme does not support widget';
		}

		$model = $this->model('widget');

		foreach ($positions as $position) {
			$widgets[$position] = $model->widget($position);
		}

		$widg = $this->get('widget')->all();

		foreach ($widgets as $name => $arrayOfWidget) {

			foreach ($arrayOfWidget as $widget) {

			$widgetObj = $this->get('widget')->get($widget->name);

				$widget->ui = $this->get('widget.ui')->build($widgetObj);
			}
		}

		set('widg', $widg);
		set('positions', $positions);
		set('widgets', $widgets);
		set('title', __('Widget'));

		set('theme', $this->get('themes')->current());
		
		return $this->render('@system/setting/themes/widget', $this->getData());
	}

	private function _toolbarIndex()
	{
		return array(
			'clean-all-widget' => array(
				'type' => 'submit',
				'label' => 'Remove All',
				'name'=> 'action',
				'value' => 'clean',
				'faClass' =>  false
			),
			'new-post' => array(
				'type' => 'a.success',
				'href' => '#',
				'label' => 'Add Widget',
				'faClass' => 'fa-plus'
			),
		);
	}

	public function widgetAdd($name)
	{
		if(!$this->isAjax()) show_404();
		
		$widget = $this->get('widget')->get($name);
		$pos = $this->get('input')->get('pos');


		$widget->ui = $this->get('widget.ui')->build($widget);

		$widget->data['name'] = $name;
		$widget->data['theme'] = $this->get('themes')->current();
		$widget->data['position'] = $pos;

		set('widget', $widget);

		return $this->get('template')->render('@system/setting/themes/widget-edit', $this->getData());
	}

	public function widgetEdit($id)
	{
		if(!$this->isAjax()) show_404();
		
		$installed = $this->model('widget')->fetch($id);

		$widget = $this->get('widget')->get($installed->name);

		$widget->data = json_decode($installed->data, true);
		$widget->data['title'] = $installed->title;
		$widget->data['id'] = $installed->id;
		$widget->data['name'] = $installed->name;
		$widget->data['theme'] = $installed->theme;
		$widget->data['position'] = $installed->position;

		$widget->ui = $this->get('widget.ui')->build($widget, $id);
		set('widget', $widget);

		return $this->get('template')->render('@system/setting/themes/widget-edit', $this->getData());
	}

	public function WidgetRemove()
	{
		$id = $this->get('input')->post('id');
		return $this->model('widget')->remove($id);
	}

	public function widgetSave()
	{
		$id  = $this->get('input')->post('id');
		$title  = $this->get('input')->post('title');
		$data  = $this->get('input')->post('data');
		$name  = $this->get('input')->post('name');
		$position  = $this->get('input')->post('position');
		$theme  = $this->get('input')->post('theme');

		return $this->model('widget')->save($id, $title, $data, $name, $position, $theme);
	}
}