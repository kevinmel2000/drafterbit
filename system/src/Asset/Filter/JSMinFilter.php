<?php namespace Drafterbit\System\Asset\Filter;

use Drafterbit\System\Asset\AssetInterface;

class JSMinFilter implements FilterInterface
{
    public function filterLoad(AssetInterface $asset)
    {
    }

    public function filterDump(AssetInterface $asset)
    {
        $asset->setContent(\JSMin::minify($asset->getContent()));
    }
}