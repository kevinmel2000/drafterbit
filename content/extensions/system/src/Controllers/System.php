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
                $left[$d['id']] = $dashboardWidgets[$d['id']];
            } else {
                $right[$d['id']] = $dashboardWidgets[$d['id']];
            }
        }

        $data['left'] = $left;
        $data['right'] = $right;

        return $this->render('@system/dashboard', $data);
    }

    public function sortDashboard() {
        $dashboardWidgets = $this->get('app')->dashboardWidgets();
        
        $widgets = array_keys($dashboardWidgets);

        $order = $this->get('input')->post('order');
        $pos = $this->get('input')->post('pos');

        $order = explode(',', $order);

        $order = array_map(function($el){
            return substr($el, strlen('dashboard-widget-'));
        }, $order);

        $diff = array_diff($widgets, $order);
        $pos = ($pos == 'left') ? 1 : 2;
        $diffPos = ($pos == 1) ? 2 : 1;

        $data = array();
        foreach ($order as $id) {
            $data[] = ['id' => $id, 'position' => $pos, 'display' => 1];
        }

        foreach ($diff as $id) {
            $data[] = ['id' => $id, 'position' => $diffPos, 'display' => 1];
        }

        $this->model('System')->updateSetting(['dashboard' => json_encode($data)]);
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
        
        $model = $this->model('cache');
        $caches = $model->getAll();
        
        $post = $this->get('input')->post();
        if (isset($post['action'])) {
            
            if($post['action'] == 'clear') {
                $model->clear();
            }
        }

        $caches = $model->getAll();

        $data['id'] = 'cache';
        $data['title']  = __('Cache');
        $data['caches'] = $caches;

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
