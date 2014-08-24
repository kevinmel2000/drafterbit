<?php namespace Drafterbit\Modules\Admin\Models;

use Partitur\Model;

class UI extends Model {

	public function nav($menuArray, $userName, $userGravatar)
	{
		$menus = $this->createMenu($menuArray);

		$data['menus'] = $menus;
		$data['userName'] = $userName;
		$data['userGravatar'] = $userGravatar;

		return $this->render('ui/nav@admin', $data);
	}

	public function footer()
	{
		return $this->render('ui/footer@admin');
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

	public function header($title, $subTitle = null)
	{
		$data['title'] = $title;
		$data['subTitle'] = $subTitle;
		return $this->render('ui/header@admin', $data);
	}

	public function form($action, $toolbar, $view)
	{
		 $data['relatedLinks'] = false;
		 $data['formAction'] = $action;
		 $data['view'] = $view;
		 $data['toolbars'] = $toolbar;
		return $this->render('ui/form@admin', $data);
	}

	public function listFormed($action, $toolbar, $table)
	{
		 $data['formAction'] = $action;
		 $data['relatedLinks'] = false;
		 $data['table'] = $table;
		 $data['toolbars'] = $toolbar;
		return $this->render('ui/list-formed@admin', $data);
	}

	public function toolbar($config)
	{
		$toolbars['left'] = array();
		$toolbars['right'] = array();
		foreach ($config as $name => $def) {
			$c =  (object) $def;

			$types = explode('.', $def['type']);

			$c->type = $types[0];
			$c->classType = isset($types[1]) ? $types[1] : 'default';
			$c->id = $name;
			$c->align = isset($def['align']) ?$def['align'] : 'right';
			$c->faClass = isset($def['faClass']) ?$def['faClass'] : false;

			if($c->align == 'right') {
				$toolbars['right'][] = $c;
			} else {
				$toolbars['left'][] = $c;
			}
		}

		$data['toolbars'] = $toolbars;
		return $this->render('ui/toolbar@admin', $data);
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

		$data['name'] = $name;
		$data['datatableSelector'] = $name.'-data-table';
		$data['thead'] = $thead;
		$data['rows'] = $rows;

		return $this->render('ui/datatables@admin', $data);
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