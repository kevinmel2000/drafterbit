<?php namespace Drafterbit\Extensions\Files\Controllers;

use Drafterbit\Extensions\System\BackendController ;
use Drafterbit\Extensions\Files\Exceptions\FileUploadException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class Admin extends BackendController {

	public function index()
	{
		$data['title'] =  __('Files');
		return $this->render('@files/admin/index', $data);
	}

	public function upload()
	{
		$config = $this->get('config');
		$uploadDir = $config['upload_dir'];
		$uploaded = $this->get('input')->files();

		$relPath = $this->get('input')->post('rel-path');

		if(isset($uploaded['files'])) {

			try {
				foreach ($uploaded['files'] as $file) {
					if ($file instanceof UploadedFile ) {
					
					//$ext = $file->getClientOriginalExtension();
					// $name = sha1_file($file->getRealPath());
					$name = $file->getClientOriginalName();

						$file->move($uploadDir.DIRECTORY_SEPARATOR.$relPath, $name);
					}
				}
				return 'File Uploaded';
			} catch (FileException $e) {
				return $e->getMessage();
			}
		}
	}

	private function fixPostMaxSizeIssue()
	{
		// @todo
		// handler post max size affected to upload file
	}

	
	public function browser()
	{	
		return $this->render($this->getTemplate(), $this->getData());
	}

	public function data()
	{
		$op = $this->get('input')->get('op');
		$path = $this->get('input')->get('path');
		
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
			$data = array( 'message' => $e->getMessage());
		}	
		
		$res->setData($data);

		return $res;
	}
}