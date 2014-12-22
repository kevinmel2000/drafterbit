<?php namespace Drafterbit\Extensions\System\Controllers;

use Drafterbit\Extensions\System\BackendController;

class Widget extends BackendController {

	/**
	 * Widget setting
	 */
	public function index()
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

		$widg = $this->get('widget')->all();

		foreach ($widgets as $name => $arrayOfWidget) {

			foreach ($arrayOfWidget as $widget) {

			$widgetObj = $this->get('widget')->get($widget->name);

				$widget->ui = $this->get('widget.ui')->build($widgetObj);
			}
		}

		$data['widg'] = $widg;
		$data['positions'] = $positions;
		$data['widgets'] = $widgets;
		$data['title'] = __('Widget');

		$data['theme'] = $this->get('themes')->current();
		
		return $this->render('@system/setting/themes/widget', $data);
	}

	public function add($name)
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

	public function edit($id)
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

		set('widget', $widget);

		return $this->get('template')->render('@system/setting/themes/widget-edit', $this->getData());
	}

	public function delete()
	{
		$id = $this->get('input')->post('id');
		return $this->model('widget')->remove($id);
	}

	public function save()
	{
		$id  = $this->get('input')->post('id');
		$title  = $this->get('input')->post('title');
		$data  = $this->get('input')->post('data');
		$name  = $this->get('input')->post('name');
		$position  = $this->get('input')->post('position');
		$theme  = $this->get('input')->post('theme');

		$id = $this->model('widget')->save($id, $title, $data, $name, $position, $theme);

		return $this->jsonResponse(['message' => 'Widget saved', 'status' => 'success', 'id' =>  $id]);
	}
}