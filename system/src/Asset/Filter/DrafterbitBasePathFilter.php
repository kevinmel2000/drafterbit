<?php namespace Drafterbit\System\Asset\Filter;

use Assetic\Asset\AssetInterface;  
use Assetic\Filter\FilterInterface;

class DrafterbitBasePathFilter implements FilterInterface
{
    protected $path;
    public function __construct($path)
    {
        $this->path = $path;
    }

    public function filterLoad(AssetInterface $asset)
    {

    }
    
    public function filterDump(AssetInterface $asset)
    {

    }
}