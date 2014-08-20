<?php namespace Drafterbit\Support\Assetic;

use Assetic\Asset\AssetInterface;  
use Assetic\Filter\FilterInterface;

class DrafterbitFontAwesomeFilter implements FilterInterface
{


    public function filterLoad(AssetInterface $asset)
    {
    }


    public function filterDump(AssetInterface $asset)
    {
        $content = $asset->getContent();
        $content = str_replace('../', base_url('system/plugins/fontawesome').'/', $content);
        $asset->setContent($content);
    }
}