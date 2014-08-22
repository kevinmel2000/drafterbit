<?php namespace Drafterbit\Modules\Support\Assetic;

use Assetic\Asset\AssetInterface;  
use Assetic\Filter\FilterInterface;

class DrafterbitChosenFilter implements FilterInterface
{


    public function filterLoad(AssetInterface $asset)
    {
    }


    public function filterDump(AssetInterface $asset)
    {
        $content = $asset->getContent();
        $content = str_replace('chosen-sprite.png', base_url('system/plugins/chosen/chosen-sprite.png'), $content);
        $content = str_replace('chosen-sprite@2x.png', base_url('system/plugins/chosen/chosen-sprite@2x.png'), $content);
        $asset->setContent($content);
    }
}