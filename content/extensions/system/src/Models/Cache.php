<?php namespace Drafterbit\Extensions\System\Models;

class Cache extends \Drafterbit\Framework\Model
{

    public function getAll()
    {
        return array_merge(
            $this->getDataCache(),
            $this->getAssetCache(),
            $this->getRouteCache()
        );
    }

    private function getDataCache()
    {
        $size = 0;

        if (is_dir($this->get('path.cache'))) {
            $finder = $this->get('finder');
            $finder->in($this->get('path.cache'))->directories();
            
            foreach ($finder as $file) {
                $size += $this->getCacheFileSize($file->getRealPath());
            }
        }

        return [['id' => 'Data', 'size' => $size]];
    }

    public function getAssetCache()
    {
        $size = 0;

        if (is_dir($this->get('path.content').'cache/asset')) {
            $finder = $this->get('finder');
            $finder->in($this->get('path.content').'cache/asset')->files();

            foreach ($finder as $file) {
                $size += $this->getCacheFileSize($file->getRealPath());
            }
        }

        return [['id' => 'Asset', 'size' => $size]];
    }

    public function getRouteCache()
    {
        $path = $this->get('path.content').'cache/routes.php';

        if(is_file($path)) {        
            return [['id' => 'Routes', 'size' => $this->getCacheFileSize($path)]];
        }

        return  array();
    }

    private function getCacheFileSize($file)
    {
        if(is_dir($file)) {
            $size = 0;
            $files = array_diff(scandir($file), ['.', '..']);        
            foreach ($files as $f) {
                $size += filesize($file.DIRECTORY_SEPARATOR.$f);
            }

        } else {
            $size = filesize($file);
        }

        return $size;
    }

    public function clear()
    {
        //clear data cache
        if (is_dir($this->get('path.cache'))) {
            $finder = $this->get('finder');
            $finder->in($this->get('path.cache'))->directories()->depth(0);
            
            foreach ($finder as $file) {
                $this->get('file')->remove($file);
            }
        }

        //clear asset cache
        if (is_dir($this->get('path.content').'cache/asset')) {
            $finder = $this->get('finder');
            $finder->in($this->get('path.content').'cache/asset')->files();

            foreach ($finder as $file) {
                $this->get('file')->remove($file->getRealPath());
            }
        }

        //routes
        $path = $this->get('path.content').'cache/routes.php';
        $this->get('file')->remove($path);
    }
}
