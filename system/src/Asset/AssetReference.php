<?php namespace Drafterbit\System\Asset;

use Drafterbit\System\Asset\Filter\FilterInterface;

class AssetReference implements AssetInterface
{
    private $am;
    private $name;
    private $filters = array();

    public function __construct(AssetManager $am, $name)
    {
        $this->am = $am;
        $this->name = $name;
    }

    public function ensureFilter(FilterInterface $filter)
    {
        $this->filters[] = $filter;
    }

    public function getFilters()
    {
        $this->flushFilters();

        return $this->callAsset(__FUNCTION__);
    }

    public function clearFilters()
    {
        $this->filters = array();
        $this->callAsset(__FUNCTION__);
    }

    public function load(FilterInterface $additionalFilter = null)
    {
        $this->flushFilters();

        return $this->callAsset(__FUNCTION__, array($additionalFilter));
    }

    public function dump(FilterInterface $additionalFilter = null)
    {
        $this->flushFilters();

        return $this->callAsset(__FUNCTION__, array($additionalFilter));
    }

    public function getContent()
    {
        return $this->callAsset(__FUNCTION__);
    }

    public function setContent($content)
    {
        $this->callAsset(__FUNCTION__, array($content));
    }

    public function getSource()
    {
        return $this->callAsset(__FUNCTION__);
    }

    public function getLastModified()
    {
        return $this->callAsset(__FUNCTION__);
    }

    // private

    private function callAsset($method, $arguments = array())
    {
        $asset = $this->am->get($this->name);

        return call_user_func_array(array($asset, $method), $arguments);
    }

    private function flushFilters()
    {
        $asset = $this->am->get($this->name);

        while ($filter = array_shift($this->filters)) {
            $asset->ensureFilter($filter);
        }
    }
}
