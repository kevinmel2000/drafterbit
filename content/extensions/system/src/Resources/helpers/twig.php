<?php

if (! function_exists('widget')) {
    /**
     * Widget function extension for templating
     *
     * @param  string $position widget position
     * @return string
     */
    function widget($position)
    {
        return app()->widget($position);
    }
}

if (! function_exists('menus')) {
    /**
     * Menus function extension for templating
     *
     * @param  string $position widget position
     * @return array
     */
    function menus($position)
    {
        return app()->menus($position);
    }
}
