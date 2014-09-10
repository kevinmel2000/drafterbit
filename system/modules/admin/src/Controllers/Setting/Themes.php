<?php namespace Drafterbit\Modules\Admin\Controllers\Setting;

use Drafterbit\Modules\Admin\BaseController;
use Drafterbit\Modules\User\Models\Auth;
use Drafterbit\Modules\Admin\Models\Setting as SettingModel;

class Themes extends BaseController {

	protected $setting;

	public function __construct(Auth $auth)
	{
		parent::__construct($auth);
	}

	public function index()
	{
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

		if(!$cache->contains('settings')) {
			$cache->save('settings', $this->setting->all());
		}

		// @todo
		$settings = $cache->fetch('settings');
		set('currentTheme', $settings['theme']);

		$themesDir = $this->get('path.themes');
		$themesDetected = $this->get('themes')->all();

		$themes = array();
		foreach ($themesDetected as $dir => $config) {

			if( is_file($path = $themesDir.$dir.'/config.yml')) {
				$themes[$dir] = $this->get('yaml')->parse(file_get_contents($path));
			} else {
				continue;
			}
		}

		set('themes', $themes);

		$ui = $this->model('UI@admin');

		$header =  $ui->header('Themes', 'Appearance setting');

		$view = $this->get('template')->render('@admin/setting/appearance', $this->getData());

		$form = $ui->form(null, null, $view);

		$content = $header.$form;

		return $this->wrap($content);
	}

	/**
	 * Widget setting
	 */
	public function widget()
	{
		$currentTheme = $this->get('themes')->get();
		$positions = $currentTheme['widget_positions'];

		if(!isset($currentTheme['widget_positions'])) {
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

		$ui = $this->model('UI@admin');

		$header =  $ui->header('Widget', 'Widget for current theme');

		set('theme', $this->get('themes')->current());
		$view = $this->get('template')->render('@admin/setting/themes/widget', $this->getData());

		//$toolbar = $ui->toolbar($this->_toolbarIndex());

		$content = $header.$view;

		$this->get('asset')
			->css('@jquery_ui_css')
			->js('@jquery_ui_js')
			->js('@jquery_form')
			->js($this->assetPath('js/widget.js'));

		return $this->wrap($content);
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

		$id = '';//$this->model('widget')->add($widget->name(), $pos);

		$widget->ui = $this->get('widget.ui')->build($widget, $id);

		set('widget', $widget);

		return $this->get('template')->render('@admin/setting/themes/widget-edit', $this->getData());
	}

	public function widgetEdit($id)
	{
		if(!$this->isAjax()) show_404();
		
		$installed = $this->model('widget')->fetch($id);

		$widget = $this->get('widget')->get($installed->name);

		$widget->data = json_decode($installed->data, true);
		$widget->data['title'] = $installed->title;
		$widget->data['id'] = $installed->id;

		$widget->ui = $this->get('widget.ui')->build($widget, $id);
		set('widget', $widget);

		return $this->get('template')->render('@admin/setting/themes/widget-edit', $this->getData());
	}

	public function WidgetRemove()
	{
		$id = $this->get('input')->post('id');
		return $this->model('widget')->remove($id);
	}
}