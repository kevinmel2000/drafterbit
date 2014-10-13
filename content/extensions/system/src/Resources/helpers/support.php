<?php

if ( ! function_exists('wysiwyg')) {
	/**
	 * Get theme path
	 *
	 * @param string $path
	 */
	function wysiwyg($name, $default = null, $attr="")
	{
		// @todo create asset_url
		$path = '/system/Resources/public/assets';
		$src = base_url('system/vendor/web/ckeditor/ckeditor.js');
		$browserUrl = admin_url('files/browser?mode=image');

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

CKEDITOR.plugins.addExternal( 'wpmore', '/system/Resources/public/assets/ckeditor-custom/plugins/wpmore/plugin.js');
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

if ( ! function_exists('log_db')) {
	/**
	 * Log message to database
	 *
	 * @return void
	 */
	function log_db( $what, $who = null, $where = null )
	{
		app('log.db')->addInfo("$who $what $where");
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