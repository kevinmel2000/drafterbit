<?php namespace Drafterbit\Extensions\System;

use Drafterbit\Framework\Controller;
use Drafterbit\Extensions\System\Models\Menu;

class BackendController extends Controller {

    public function __construct( )
    {
        $session = $this->get('session');

        $this->get('template')->addGlobal('title', 'Untitled');

        //flash messages
        $messages = $session->getFlashBag()->get('messages');

        $this->get('template')->addGlobal('messages', $messages);
    }

    private function menu()
    {
        $sorted = array();
        $children = array();
        $i = 0;

        foreach ($this->get('app')->getNav() as $item) {

            $order = isset($item['order']) ? $item['order'] : $i;
            
            if(isset($item['parent'])) {
                $children[$item['parent']][$order] = $item;

            } else {
                $sorted[$order][] = $item;
            }

            $i++;
        }

        $sorted2 = array();
        foreach ($sorted as $index => $menu) {
            $sorted2 = array_merge($sorted2, array_values($menu));
        }
        
        foreach ($sorted2 as &$menu) {

            if(isset($children[$menu['id']])) {
                ksort($children[$menu['id']]);
                $menu['children'] = $children[$menu['id']];
            }
        }

        ksort($sorted2);

        return $sorted2;
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

    /**
     * Create datatables
     *
     * @param string $id
     * @param array $heads
     * @param array $data
     */
    public function dataTable($id, $heads, $data)
    {
        $thead = array();
        foreach ($heads as $item) {
            $th['id']         = $item['field'];
            $th['label']     = $item['label'];
            $th['align']     = isset($item['align']) ? $item['align'] : 'left';
            $th['width']     = isset($item['width']) ? $item['width'] : 'auto';
            $th['format']     = (isset($item['format']) and is_callable($item['format'])) ? $item['format'] : false;

            $thead[] = $th;
        }

        $rows = array();

        foreach ($data as $item) {

            $row['id'] = $item['id'];

            foreach ($thead as $th) {
                if($th['format']) {
                    $row['values'][$th['id']] = call_user_func_array($th['format'], [$item[$th['id']], $item]);
                } else {
                    $row['values'][$th['id']] = $item[$th['id']];
                }
            }
            
            $rows[] = $row;
        }

        $data['id']        = $id;
        $data['rows']     = $rows;
        $data['thead']     = $thead;

        return $this->render('@system/partials/data-table', $data);
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
        return $this->render('@system/partials/table-header', $data);
    }

    /**
     * Render Template.
     *
     * @param string $template
     * @param array $data
     */
    public function render($template, $data = array())
    {
        //gravatar
        $session = $this->get('session');
        $email = $session->get('user.email');

        $userName = $session->get('user.name') ? $session->get('user.name') : $email;

        $userGravatar = gravatar_url($email, 17);

        $system = $this->model('@system\System')->all();
        
        $this->get('template')
            ->addGlobal('menus', $this->createMenu($this->menu()))
            ->addGlobal('userName', $userName)
            ->addGlobal('userGravatar', $userGravatar)
            ->addGlobal('siteName', $system['site.name']);

        return $this->get('template')->render($template, $data);
    }
}