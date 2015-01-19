<?php namespace Drafterbit\Extensions\System\Models;

class Cache extends \Drafterbit\Framework\Model
{

    public function getAll()
    {
        $data = array();

        if(is_dir($this->get('path.cache'))) {

            $finder = $this->get('finder');
            $finder->in($this->get('path.cache'))->directories();
            
            foreach ($finder as $file) {
                $f['id'] = $file->getFileName();
                $f['name'] = ucfirst($file->getFileName());
                $f['size'] = $this->getCacheFileSize($file->getRealPath()) . ' Byte';

                $data[] = $f;
            }
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
}