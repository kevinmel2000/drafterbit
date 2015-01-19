<?php namespace Drafterbit\Extensions\System\Controllers;

use Drafterbit\Extensions\System\BackendController;
use Drafterbit\Component\Validation\Exceptions\ValidationFailsException;

class Menus extends BackendController
{

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
        $data['frontPageOptions'] = $this->get('app')->getFrontPageOption();
        
        $data['title'] = __('Menus');
        return $this->render('@system/setting/themes/menus', $data);
    }

    function save()
    {
        $post = $this->get('input')->post();

        try {
            $this->validate('menus', $post);

            $model = $this->model('@system\\Menus');
            $data = $this->createInsertData($post);
            $id = $model->save($post['id'], $data);
            $response = [
                'message' => 'Menu saved',
                'id' => $id,
            ];
        } catch (ValidationFailsException $e) {
            $response = [
                'error' => [
                    'type' => 'validation',
                    'messages' => $e->getMessages(),
                ]
            ];
        }

        return $this->jsonResponse($response);
    }

    function delete()
    {
        $id = $this->get('input')->post('id');
        return $this->get('db')->delete('#_menus', ['id' => $id]);
    }

    private function createInsertData($post)
    {
        $data['label'] = $post['label'];
        $data['type'] = $post['type'];
        $data['link'] = $post['link'];
        $data['page'] = $post['page'];
        $data['position'] = $post['position'];
        $data['theme'] = $post['theme'];

        return $data;
    }

    public function sort()
    {
        $ids = $this->get('input')->post('order');

        $order = 1;
        foreach (array_filter(explode(',', $ids)) as $temp) {
            $temp2 = explode('-', $temp);
            $id = current($temp2);
            $data = ['sequence' => $order];

            $order++;

            $this->model('@system\Menus')->update($id, $data);
        }

        return 1;
    }
}
