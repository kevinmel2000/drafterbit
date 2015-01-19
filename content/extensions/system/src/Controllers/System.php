<?php namespace Drafterbit\Extensions\System\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Drafterbit\Extensions\System\BackendController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use FtpClient\FtpClient;

class System extends BackendController
{

    public function dashboard()
    {
        $data['title'] = __('Dashboard');

        $dashboardWidgets = $this->get('app')->dashboardWidgets();

        $dashboard = $this->model('@system\System')->fetch('dashboard');

        $left = $right = array();

        foreach (json_decode($dashboard, true) as $d) {
            if ($d['position'] == 1) {
                $left[] = $dashboardWidgets[$d['id']];
            } else {
                $right[] = $dashboardWidgets[$d['id']];
            }
        }

        $data['left'] = $left;
        $data['right'] = $right;

        return $this->render('@system/dashboard', $data);
    }

    public function log()
    {
        $action = $this->get('input')->post('action');
        $logIds = $this->get('input')->post('log');

        switch($action) {
            case "delete":
                if ($logIds) {
                    foreach ($logIds as $id) {
                        $this->model('@system\Log')->delete($id);
                    }
                    $msg = 'Logs deleted';
                    $this->get('template')->addGlobal('messages', [['text' => $msg, "type" => 'success']]);
                }
                break;
            case "clear":
                $this->model('@system\Log')->clear();
                $msg = 'Logs cleared';

                $this->get('template')->addGlobal('messages', [['text' => $msg, "type" => 'success']]);

            default:
                break;
        }


        $logs = $this->model('@system\Log')->all();

        $data['title'] = __('Logs');
        $data['id'] = 'log';

        $tableHead = array(
            ['field' => 'time', 'width' => '20%', 'label' => 'Time', 'format' => function($val, $item) {
                return date('d-m-Y H:i:s', $val);
            }],
            ['field' => 'message', 'label' => 'Activity', 'format' => function($val, $item){

                return $this->model('@system\Dashboard')->formatLog($item['message']);
            }]
        );

        $data['logTable'] = $this->dataTable('log', $tableHead, $logs);

        return $this->render('@system/log', $data);
    }

    public function cache()
    {
        $post = $this->get('input')->post();

        if (isset($post['action']) and ($post['action'] == 'delete') and isset($post['cache'])) {
            foreach ($post['cache'] as $key) {
                $this->get('cache')->delete($key);
            }
        }

        $model = $this->model('cache');

        $caches = $model->getAll();

        $tableHead = array(
            ['field' => 'name', 'label' => 'Name'],
            ['field' => 'size', 'label' => 'Filesize']
        );

        $cacheTable = $this->dataTable('cache', $tableHead, $caches);

        $data['id'] = 'cache';
        $data['title']  = __('Cache');
        $data['cacheTable'] = $cacheTable;

        return $this->render('@system/cache', $data);
    }

    public function drafterbitJs()
    {
        return new Response(
            $this->render('@system/drafterbitjs'),
            Response::HTTP_OK,
            array(
            'content-type' => 'application/javascript'
            )
        );
    }

    public function drafterbitCss()
    {
        return new Response(
            $this->render('@system/drafterbitcss'),
            Response::HTTP_OK,
            array(
            'content-type' => 'text/css'
            )
        );
    }
}
