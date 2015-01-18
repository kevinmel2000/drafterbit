<?php namespace Drafterbit\Extensions\Files\Controllers;

use Drafterbit\Extensions\System\BackendController ;
use Drafterbit\Extensions\Files\Exceptions\FileUploadException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class Files extends BackendController {

    public function index()
    {
        $data['title'] =  __('Files');
        return $this->render('@files/admin/index', $data);
    }

    private function fixPostMaxSizeIssue()
    {
        // @todo
        // handler post max size affected to upload file
    }
    
    public function browser()
    {    
        return $this->render('@files/admin/browser');
    }

    public function data()
    {
        $op = $this->get('input')->request('op');
        $path = $this->get('input')->request('path');
        
        $res = new JsonResponse;

        try {

            $data = array();

            switch ($op) {
                case 'ls':
                    $data = $this->get('ofinder')->ls($path);
                    break;
                case 'delete':
                    $data = $this->get('ofinder')->delete($path);
                    break;
                case 'mkdir':
                    $folderName = $this->get('input')->get('folder-name');
                    $data = $this->get('ofinder')->mkdir($path, $folderName);
                break;
                case 'rename':
                    $newName = $this->get('input')->post('newName');
                    $data = $this->get('ofinder')->rename($path, $newName);
                break;
                case 'move':
                    $dest = $this->get('input')->post('dest');
                    $data = $this->get('ofinder')->move($path, $dest);
                break;
                default:
                     # code...
                    break;
            }

            // upload
            if($files = $this->get('input')->files('files', array())) {
                $path = $this->get('input')->post('path');
                $data = $this->get('ofinder')->upload($path, $files);
            }

        } catch (\Exception $e) {
            $data = array( 'message' => $e->getMessage(), 'status' => 'error');
        }
        
        $res->setData($data);

        return $res;
    }
}