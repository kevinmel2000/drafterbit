<?php

if ( ! function_exists('message'))
{
	/**
     * Add Message.
     *
     * @param string $text
     * @param string $type
     * @param string $title
     */
	function message($text, $type = 'info', $title = null)
	{
		return app('current.controller')->message($text, $type, $title);
	}
}