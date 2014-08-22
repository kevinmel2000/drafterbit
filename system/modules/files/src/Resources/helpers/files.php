<?php

if ( ! function_exists('files_url'))
{
	/**
     * Add Message.
     *
     * @param string $path
     */
	function files_url($path)
	{
          $config = app('user_config')->get('config');
          return base_url($config['upload_dir'])."/$path";
	}
}