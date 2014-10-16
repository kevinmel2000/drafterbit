<?php namespace Drafterbit\Extensions\Files\Controllers;

use Drafterbit\Extensions\System\BaseController ;
use Drafterbit\Extensions\Files\Exceptions\FileUploadException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class Admin extends BaseController {

	public function index()
	{
		// build current place
		$arg = func_get_args();

		$config = $this->get('config');

		$path = $config['path.upload'].'/';

		$relPath = '/';
		if($arg) {
			$relPath = implode('/', $arg).'/';
			$path = $config['upload_dir'].'/'.$relPath;
		}

		if(!is_dir($path)) {
			show_404();
		}
		
		// do what requested first
		$post = $this->get('input')->post();

		if($post) {
			if(isset($post['new-folder-name'])) {
				if(trim($post['new-folder-name']) == '') {
					message('Please provide a name for the new folder', 'error');
				} else {
					try {
						mkdir($path.$post['new-folder-name']);
						message("Folder {$post['new-folder-name']} successfully created", 'success');
					} catch (\Exception $e) {
						message($e->getMessage(), 'error');
					}
				}
			}

			if(isset($post['files'])) {

				switch ($post['action']) {
					case 'Move to Trash':

						/* @todo
						foreach ($post['files'] as $fileName) {

							try {
								$tmpDir = $this->get('path.tmp');
								$movePath = $tmpDir.'trash'.$relPath;

								if(!is_dir($movePath)) {
									mkdir($movePath);
								}

								rename($path.$fileName, $movePath.$fileName);

							} catch (\ErrorException $e) {
								message($e->getMessage(), 'error');
							}

							message('Trashed !', 'success');
						}*/

						break;
					case 'Delete':
						foreach ($post['files'] as $fileName) {

							if(is_dir($path.$fileName)) {
								rmdir($path.$fileName);
							} else {
								unlink($path.$fileName);
							}
						}
						message('Deleted !', 'success');
						break;
					default:
						# code...
						break;
				}
			}

		}

		// grab it all
		$filesFound = $this->get('finder')->in($path)->depth(0)->sortByType();

		$filesPath = $this->get('path.install').$config['upload_dir'];
		$files = array();
		foreach ($filesFound as $file) {
			$xfile = new \StdClass();
			$xfile->mTime = $file->getMTime();
			$xfile->isDir = $file->isDir();
			$xfile->name = $file->getFileName();
			$xfile->type = $file->getType();
			$xfile->path = str_replace($filesPath.'/', '', $file->getRealPath());

			$files[] = $xfile;
		}

		set('files', $files);

		$subpaths = array();
		$p = '';
		foreach ($arg as $path) {
			$xpath = new \StdClass();
			$p .= "/$path";
			$xpath->name = $path;
			$xpath->path = '/admin/files/index/'.ltrim($p, '/');

			$subpaths[] = $xpath;
		}

		set('subpaths', $subpaths);
		set('relPath', $relPath);

		$this->get('asset')->addCSS($this->getpublicPath('css/index.css'));
		$this->get('asset')->addCSS('@bootstrap_datatables_css');
		$this->get('asset')->addJs('@datatables_js');
		$this->get('asset')->addJs('@bootstrap_datatables_js');
		$this->get('asset')->addJs('@jquery_check_all');
		$this->get('asset')->addJs('@jquery_form');
		$this->get('asset')->addJs('@bootstrap_contextmenu');
		$this->get('asset')->addJs($this->getpublicPath('js/index.js'));
		$this->get('asset')->addJs($this->getpublicPath('js/upload.js'));
		
		$ui = $this->model('UI@admin');
		$tbConfig = array(
			'trash' => array(
				'type' => 'submit',
				'label' => 'Move to Trash',
				'name'=> 'action',
				'value' => 'trash',
				'faClass' => false
			),
			'delete' => array(
				'type' => 'submit',
				'label' => 'Delete',
				'name'=> 'action',
				'value' => 'delete',
				'faClass' => false
			),
			'new' => array(
				'type' => 'a',
				'href' => '#',
				'label' => 'New Folder',
				'faClass' => false
			),
			'upload' => array(
				'type' => 'a.success',
				'href' => "#",
				'label' => 'Upload',
				'faClass' => false
			),
		);

		$tableConfig = array(
			['field' => 'title', 'label' => 'Title'],
			['field' => 'created_at', 'label' => 'Date']
		);

		$header =  $ui->header('Files', 'Uploaded files');

		$toolbar = $ui->toolbar($tbConfig);

		$view = $this->get('template')->render('admin/index@files', $this->getData());

		$form = $ui->form(null, $toolbar, $view);

		$content = $header.$form;

		return $this->wrap($content);
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

	public function xdata()
	{
		$data['draw'] = 1;
		$data['recordsTotal'] = 2;
		$data['recordsFiltered'] = 2;
		$data['data'] = array(
			[
			'<input type="checkbox" name="files[]" value="1">','test', 'image', '21-06-1991'],
			['<input type="checkbox" name="files[]" value="2">','cool', 'image', '21-06-1991']);

		return json_encode($data);
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