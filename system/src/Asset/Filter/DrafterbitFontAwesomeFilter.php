<?php namespace Drafterbit\System\Asset\Filter;

use Assetic\Asset\AssetInterface;  
use Assetic\Filter\FilterInterface;

class DrafterbitFontAwesomeFilter implements FilterInterface
{
	protected $path;
	public function __construct($path)
	{
		$this->path = $path;
	}

    public function filterLoad(AssetInterface $asset) {}

    public function filterDump(AssetInterface $asset)
    {
        $content = $asset->getContent();
        $content = str_replace('../', base_url($this->path.'/fontawesome').'/', $content);
        $asset->setContent($content);
    }
}