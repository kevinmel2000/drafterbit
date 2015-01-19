<?php namespace Drafterbit\System\Asset\Filter;

use Assetic\Asset\AssetInterface;  

class DrafterbitFontAwesomeFilter extends DrafterbitBasePathFilter
{
    public function filterDump(AssetInterface $asset)
    {
        $content = $asset->getContent();
        $content = str_replace('../', base_url($this->path.'/fontawesome').'/', $content);
        $asset->setContent($content);
    }
}
