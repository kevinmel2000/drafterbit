<?php

if ( ! function_exists('widget'))
{
	/**
	 * Widget function extension for templating
	 *
	 * @param string $position widget position
	 * @return string
	 */
	function widget($position)
	{
		return app()->widget($position);
	}
}