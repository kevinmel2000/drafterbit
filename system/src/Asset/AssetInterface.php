<?php namespace Drafterbit\System\Asset;

use Drafterbit\System\Asset\Filter\FilterInterface;

interface AssetInterface {

    /**
     * @param FilterInterface $filter
     */
    public function ensureFilter(FilterInterface $filter);

    /**
     * @return array
     */
    public function getFilters();


    /**
     * @param FilterInterface $additionalFilter
     */
    public function load(FilterInterface $additionalFilter = null);

    /**
     *
     * @param FilterInterface $additionalFilter
     * @return string
     */
    public function dump(FilterInterface $additionalFilter = null);

    /**
     * @return string The content
     */
    public function getContent();

    /**
     *
     * @param string $content
     */
    public function setContent($content);

    /**
     * @return string
     */
    public function getSource();
}