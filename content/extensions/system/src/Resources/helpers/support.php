<?php

if ( ! function_exists('admin_url'))
{
     /**
     * admin url
     *
     * @param string $sub
     * @return string
     */
     function admin_url($sub = null)
     {
          $admin = app('config')['path.admin'];
          $path = is_null($sub) ? $admin : $admin.'/'.$sub;
          return base_url($path);
     }
}

if ( ! function_exists('wysiwyg')) {
	/**
	 * Get theme path
	 *
	 * @param string $path
	 */
	function wysiwyg($name, $default = null, $attr="")
	{
		// @todo create asset_url
		$path = base_url('system/Resources/public/assets');
		$src = base_url('system/vendor/web/ckeditor/ckeditor.js');
		$browserUrl = admin_url('files/browser?mode=image');
		$wpmore = base_url('/system/Resources/public/assets/ckeditor-custom/plugins/wpmore/plugin.js');

		$html = <<< EOD

<textarea $attr name="$name">$default</textarea>
<script src="$src"></script>
<script>

CKEDITOR.replace('$name', {

	customConfig : '$path/ckeditor-custom/config.js',
	skin: 'bootstrap,$path/ckeditor-custom/skins/bootstrap/',

    filebrowserWindowWidth  : 860,
    filebrowserWindowHeight : 453,
	filebrowserImageBrowseUrl : '$browserUrl'
});

CKEDITOR.plugins.addExternal( 'wpmore', '$wpmore');
CKEDITOR.config.extraPlugins = 'wpmore';

</script>

EOD;
		
		return $html;
	}
}

if ( ! function_exists('theme_url')) {
	/**
	 * Get theme path
	 *
	 * @param string $path
	 */
	function theme_url($path = null)
	{
		$theme = app('themes')->current();

		$themePath = str_replace(app('path.public'), '', app('path.themes'));

		return base_url("{$themePath}{$theme}".trim($path));
	}
}

if ( ! function_exists('log_activity')) {
	/**
	 * Log message to database
	 *
	 * @return void
	 */
	function log_activity($message, $context = array())
	{
		if(!isset($context['user_id'])) {
			$context['user_id'] = app('session')->get('user.id');
			$context['user_name'] = app('session')->get('user.name');
		}
		
		app('log.db')->addInfo($message, $context);
	}
}

if ( ! function_exists('asset_url')) {
	/**
	 * Asset url
	 *
	 * @return void
	 */
	function asset_url($path)
	{
		$cachePath = str_replace(app('path.public'), '', app('path.content')).'cache';

		return base_url($cachePath.'/asset/'.$path);
	}
}

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