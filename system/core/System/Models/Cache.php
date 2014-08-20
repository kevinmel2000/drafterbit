<?php namespace Drafterbit\System\Models;

class Cache extends \Partitur\Model {

	public function all()
	{
		$finder = $this->get('finder');
		$finder->in($this->get('path.cache'))->directories();

		$data = array();

		foreach ($finder as $file) {
			$f = new \StdClass;
			$f->id = $file->getFileName();
			$f->name = ucfirst($file->getFileName());
			$f->size = $this->getCacheFileSize($file->getRealPath()) . ' Byte';

			$data[] = $f;
		}

		return $data;
	}

	private function getCacheFileSize($dir)
	{
		$files = array_diff(scandir($dir), ['.', '..']);

		$size = 0;
		foreach ($files as $file) {
			$size += filesize($dir.DIRECTORY_SEPARATOR.$file);
		}

		return $size;
	}

	public function clear()
	{
		//
	}
}