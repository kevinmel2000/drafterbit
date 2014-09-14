<?php namespace Drafterbit\Modules\Pages\Controllers;

use Drafterbit\Modules\Pages\Models\Page as PageModel;
use Drafterbit\Modules\Support\Controller as BaseController;

class Pages extends BaseController {

	protected $page;

	public function __construct( PageModel $page)
	{
		$this->page = $page;
	}

	public function index()
	{
		return $this->get('twig')->render('index.html');
	}

	public function home()
	{
		$id = $this->get('homepage.id');
		$page = $this->page->getSingleBy('id', $id) or show_404();
		set('page', $page);
		// @todo: blank layout
		set('layout', 'layout/'.$page->layout);
		
		return $this->get('twig')->render('page/view.html', $this->data);
	}

	public function view($slug = null)
	{
		$page = $this->page->getSingleBy('slug', $slug) or show_404();
		set('page', $page);
		set('layout', 'layout/'.$page->layout);
		
		return $this->get('twig')->render('page/view.html', $this->data);
	}

	private function getWidgetPosition()
	{
		$path = $this->get('path.theme').'config.yml';
		$config = $this->get('yaml')->parse(file_get_contents($path));

		return $config['widget_positions'];
	}

	private function buildWidget($position)
	{
		$widgets = $this->getWidgetOnPosition($position);

		$output = '';
		foreach ($widgets as $widget) {
			$output .= $this->get('app')->getWidget($widget)->run();
		}

		return $output;
	}

	private function getWidgetOnPosition($position)
	{
		$widget = $this->model('Widget@admin');

		$theme = $this->get('theme');
		$positions = $widget->positions($theme);
		var_dump($positions); exit();
	}
}