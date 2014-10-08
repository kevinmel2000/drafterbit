<?php namespace Drafterbit\Extensions\Admin;

use Drafterbit\Extensions\System\Controller;
use Drafterbit\Extensions\User\Models\Auth;
use Drafterbit\Extensions\Admin\Models\Menu;

class BaseController extends Controller {

	protected $baseTemplate;

	public function __construct( Auth $auth )
	{
		$this->auth = $auth;
		$session = $this->get('session');

		//flash message
		$message = $session->getFlashBag()->get('message');

		app('dispatcher')->addListener('controller.before.call', function() use ($message) {
				
				if(isset($message['text'], $message['type']))
				app('current.controller')->message($message['text'], $message['type']);
		});

		// assets
		$adminCSS = $this->getExtension('admin')->getResourcesPath('public/css/');
		$adminJs = $this->getExtension('admin')->getResourcesPath('public/js/');
		$this->get('asset')
			->css('@fontawesome', '@fontawesome')
			->css('@bootstrap_css')
			->css('@toastr_css')
			->css($adminCSS.'overrides-toastr.css')
			->css($adminCSS.'overrides-bootstrap.css')
			->css($adminCSS.'overrides-datatables.css')
			->css($adminCSS.'style.css')
	
			->js('@jquery')
			->js('@bootstrap_js')
			->js('@toastr_js')
			->js($adminJs.'layout.js')
			->js($adminJs.'app.js');
	}

	private function menu()
	{
		$sorted = array();
		$children = array();
		$i = 0;

		foreach (app()->getMenu() as $item) {

			$order = isset($item['order']) ? $item['order'] : $i;
			
			if(isset($item['parent'])) {
				$children[$item['parent']][$i] = $item;

			} else {
				$sorted[$order] = $item;
			}


			$i++;
		}

		foreach ($sorted as &$menu) {

			if(isset($children[$menu['id']])) {
				ksort($children[$menu['id']]);
				$menu['children'] = $children[$menu['id']];
			}
		}

		ksort($sorted);
		return $sorted;
	}

	public function buildTemplate()
	{
		$content = parent::view();

		//$partials['nav'] = $this->get('template')->render('partials/nav@admin', array());

		//gravatar
		$session = $this->get('session');

		$hash = md5(strtolower($session->get('user.email')));
		$url = "http://www.gravatar.com/avatar/$hash?d=mm&s=17";
		$userName = $session->get('user.name');
		$userGravatar = $url;
		
		$partials['nav'] = $this->nav($this->menu(), $userName, $userGravatar);


		$footer = 
		$partials['footer'] = $this->footer();

		$this->set('content', $content);
		$this->set('partials', $partials);

		return $this->get('template')->render('@admin/base', $this->data);
	}

	public function view()
	{
		if( strtolower($this->get('input')
					->server('HTTP_X_REQUESTED_WITH')) == 'xmlhttprequest') {
			
			// @todo append js on demand
			return parent::view();
		}

		if(!isset($this->data['messages'])) {
            $this->data['messages'] = false;
        }

		$jsFileName = $this->get('asset')->writeJs();
		$fileName = $this->get('asset')->writeCSS();
		
		$this->data['stylesheet'] = $fileName.'.css';
		$this->data['script'] = $jsFileName.'.js';

		return $this->buildTemplate();
	}

	public function wrap($content)
	{
		if(!isset($this->data['messages'])) {
            $this->data['messages'] = false;
        }

		$jsFileName = $this->get('asset')->writeJs();
		$fileName = $this->get('asset')->writeCSS();
		
		$this->data['stylesheet'] = $fileName.'.css';
		$this->data['script'] = $jsFileName.'.js';

		//gravatar
		$session = $this->get('session');
		$hash = md5(strtolower($session->get('user.email')));
		$url = "http://www.gravatar.com/avatar/$hash?d=mm&s=17";
		$userName = $session->get('user.name');
		$userGravatar = $url;

		$nav = $this->nav($this->menu(), $userName, $userGravatar);
		$footer = $this->footer();
		$partials['nav'] = $nav;
		$partials['footer'] = $footer;

		$this->set('content', $content);
		$this->set('partials', $partials);
		return $this->get('template')->render('@admin/base', $this->data);
	}

	/**
     * Add Message.
     *
     * @param string $text
     * @param string $type
     * @param string $title
     */
    public function message($text, $type = 'info', $title = null)
    {
        $message = array();
        $message['text'] = $text;
        $message['type'] = $type;
        $message['title'] = $title;

        if(!isset($this->data['messages'])) {
            $this->data['messages'] = array();
        }

        return array_push($this->data['messages'], $message);
    }

    public function layoutList($id, $title, $subtitle, $action, $toolbars, $headers, $datas, $filters = array())
	{
		$data['header'] 	= $this->header($title, $subtitle);
		$data['toolbars'] 	= $this->toolbar($toolbars, true, $filters);
		$data['action'] 	= $action;
		$data['table'] 		= $this->datatables($id, $datas, $headers);
		$content = $this->render('@admin/partials/list', $data);

		return $this->wrap($content);
	}

	public function layoutForm($id, $title, $subtitle, $action, $toolbars, $view)
	{
		$data['header'] = $this->header($title, $subtitle);
		$data['toolbars'] 	= $this->toolbar($toolbars);
		$data['action'] 	= $action;
		$data['view'] = $view;
		$data['id'] = $id;
		$content =  $this->render('@admin/partials/form', $data);
		return $this->wrap($content);
	}

	public function header($title, $subTitle = null)
	{
		$data['title'] = $title;
		$data['subTitle'] = $subTitle;
		return $this->render('@admin/partials/header', $data);
	}

	public function toolbar($config, $search = false, $filters = array())
	{
		$toolbars['left'] = array();
		$toolbars['right'] = array();
		foreach ($config as $name => $def) {
			$c =  (object) $def;

			$types = explode('.', $def['type']);

			$c->type = $types[0];
			$c->classType = isset($types[1]) ? $types[1] : 'default';
			$c->id = $name;
			$c->align = isset($def['align']) ?$def['align'] : 'left';
			$c->faClass = isset($def['faClass']) ?$def['faClass'] : false;

			if($c->align == 'right') {
				$toolbars['right'][] = $c;
			} else {
				$toolbars['left'][] = $c;
			}
		}

		$data['toolbars'] = $toolbars;
		$data['search'] = $search;
		$data['filters'] = $filters;
		return $this->render('@admin/partials/toolbar', $data);
	}

	public function nav($menuArray, $userName, $userGravatar)
	{
		$menus = $this->createMenu($menuArray);

		$data['menus'] = $menus;
		$data['userName'] = $userName;
		$data['userGravatar'] = $userGravatar;

		return $this->render('@admin/partials/nav', $data);
	}

	public function footer()
	{
		$system = $this->get('cache')->fetch('system');
		$data['siteName'] = $system['site.name'];
		return $this->render('@admin/partials/footer', $data);
	}

	private function createMenu($menuArray)
	{
		$menus = array();

		foreach ($menuArray as $menu) {

			$href = isset($menu['href']) ? $menu['href'] : null;
			$class = isset($menu['class']) ? $menu['class'] : null;
			$id = isset($menu['id']) ? $menu['id'] : null;
			$item = new Menu($menu['label'], $href, $id, $class);
			
			if(isset($menu['children'])) {
				$item->children = $this->createMenu($menu['children']);
			}

			$menus[] = $item;
		}

		return $menus;
	}

	public function form($action, $toolbar, $view)
	{
		 $data['relatedLinks'] = false;
		 $data['formAction'] = $action;
		 $data['view'] = $view;
		 $data['toolbars'] = $toolbar;
		return $this->render('@admin/partials/form', $data);
	}

	public function datatables($name, $data, $headers)
	{
		$thead = array();
		foreach ($headers as $item) {
			$th = new \StdClass;
			$th->label = $item['label'];
			$th->id = $item['field'];
			$th->align = isset($item['align']) ? $item['align'] : 'left';
			$th->width = isset($item['width']) ? $item['width'] : 'auto';
			$th->format = (isset($item['format']) and is_callable($item['format'])) ? $item['format'] : false;

			$thead[] = $th;
		}

		$rows = array();

		foreach ($data as $item) {
			
			$row = new \StdClass;
			$row->id = $item->id;

			foreach ($thead as $th) {

				if($th->format) {
					$row->values[$th->id] = call_user_func_array($th->format, [$item->{$th->id}, $item]);
				} else {
					$row->values[$th->id] = $item->{$th->id};
				}
			}

			$rows[] = $row;
		}

		$data['id'] = $data['name'] = $name;
		$data['thead'] = $thead;
		$data['rows'] = $rows;

		return $this->render('@admin/partials/datatables', $data);
	}

	public function tableHeader($name, $data, $headers)
	{
		$thead = array();
		foreach ($headers as $item) {
			$th = new \StdClass;
			$th->label = $item['label'];
			$th->id = $item['field'];
			$th->align = isset($item['align']) ? $item['align'] : 'left';
			$th->width = isset($item['width']) ? $item['width'] : 'auto';
			$th->format = (isset($item['format']) and is_callable($item['format'])) ? $item['format'] : false;

			$thead[] = $th;
		}

		$data['id'] = $data['name'] = $name;
		$data['thead'] = $thead;
		return $this->render('@admin/partials/table-header', $data);
	}

	/**
     * Render Template.
     *
     * @param string $template
     * @param array $data
     */
    public function render($template, $data = array())
    {
         return $this->get('template')->render( $template, $data);
    }
}