<?php namespace Drafterbit\System\Asset;

use Drafterbit\System\Asset\Filter\FilterInterface;

class FileAsset extends Asset
{
    /**
     * Constructor.
     *
     * @param string $source     An absolute path
     * @param array  $filters    An array of filters
     */
    public function __construct($source, $filters = array())
    {
        parent::__construct($source, $filters);
    }

    public function load(FilterInterface $additionalFilter = null)
    {
        if (!is_file($this->source)) {
            throw new \RuntimeException(sprintf('The source file "%s" does not exist.', $this->source));
        }

        $this->doLoad(file_get_contents($this->source), $additionalFilter);
    }

    public function getLastModified()
    {
        return filemtime($this->source);
    }
}