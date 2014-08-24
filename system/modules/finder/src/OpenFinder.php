<?php namespace Drafterbit\Modules\Finder;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Gregwar\Image\Image;


class OpenFinder {
	
	protected $root;
	protected $fileSystem;

	public function __construct($root, Filesystem $fileSystem = null)
	{
		$this->root = realpath($root);
		$this->fileSystem = is_null($fileSystem) ? new Filesystem : $fileSystem;
	}

	protected function createFinder()
	{
		return new Finder;
	}

	protected function createImage()
	{
		return new Image;
	}

	protected function preparePath($path)
	{
		return realpath(implode(DIRECTORY_SEPARATOR, array($this->root, trim($path, DIRECTORY_SEPARATOR))));
	}

	public function ls($relativePath)
	{
		$path = $this->preparePath($relativePath);

		$finder = $this->createFinder();
		$finder->in($path)->depth(0)->sortByType();

		$items = array();
		foreach ($finder as $item) {

			$instance = $item;

			//if file is image, chekc iif there is thumbnail already exist
			// create one if no thumbnail found
			if ($this->isImage($instance)) {

				$relative = $this->fileSystem->makePathRelative(pathinfo($instance->getRealPath(), PATHINFO_DIRNAME), $this->root);

				$thumbnailPath = $this->root.'/.thumb/'.$relative.'/thumbnail_'.$instance->getFileName();

				if(!is_file($thumbnailPath)) {
					$this->createThumbnail($instance->getRealPath(), $thumbnailPath);
				}

				$instance->isImage = true;
				$instance->thumbnail = $thumbnailPath;
			}

			$items[] = $this->format($instance);
		}
		
		return $items;
	}

	public function delete($relativePath)
	{
		$path = $this->preparePath($relativePath);
		$instance = new \SplFileInfo($path);

		$items = array();

		if ($this->isImage($path)) {

			$relative = $this->fileSystem->makePathRelative(pathinfo($instance->getRealPath(), PATHINFO_DIRNAME), $this->root);
			$thumbnailPath = $this->root.'/.thumb/'.$relative.'/thumbnail_'.$instance->getFileName();

			if(is_file($thumbnailPath)) {
				$this->fileSystem->remove($thumbnailPath);
			}
		}

		$this->fileSystem->remove($path);
		$items['status'] = 'ok';
		
		return $items;
	}

	protected function format($file)
	{
		$path = '/'.trim($this->fileSystem->makePathRelative($file->getRealPath(), $this->root), '/');

		$type = 'file';

		if($file->isDir()) {
			$type = 'dir';
		} else if (isset($file->isImage)) {
			$type = 'image';
		}

		return array(
			'thumbnail' => isset($file->thumbnail) ? $file->thumbnail : false,
			'base64' => isset($file->thumbnail) ? $this->getBase64Image($file->thumbnail) : false,
			'type' => $type,
			'path' => $path,
			'label' => $file->getFileName()
		);
	}

	private function createThumbnail($src, $dest, $width = null, $height = 60)
	{
		$this->createImage()->fromFile($src)
			    ->resize($width, $height)
			    ->save($dest);
	}

	private function isImage($file) {
        return false !== @exif_imagetype($file);
	}

	private function getBase64Image($path, $type = "jpg")
	{
		$mime = $type;
        if ($mime == 'jpg') {
            $mime = 'jpeg';
        }

        return 'data:image/'.$mime.';base64,'.base64_encode(file_get_contents($path));
	}

	public function upload($relpath, $files) {

		$path = $this->preparePath($relpath);

		$returned = array();

		foreach ($files as $file) {

			$array = array();
			
			if ($file instanceof UploadedFile ) {

				$name = $file->getClientOriginalName();
				if($file->move($path, $name)) {
					$array['status'] = 'ok';
					$array['uploaded'] = $relpath.$name;
				}
			}

			$returned[] = $array;
		}

		return $returned;
	}
}