<?php

if ( ! function_exists('wysiwyg'))
{
	/**
	 * Get theme path
	 *
	 * @param string $path
	 */
	function wysiwyg($name, $default = null, $attr="")
	{
		$src = base_url('system/plugins/ckeditor/ckeditor.js');
		$browserUrl = admin_url('finder/browser?mode=image');
		$html = <<< EOD

<textarea $attr name="$name">$default</textarea>
<script src="$src"; ?>"></script>
<script>

CKEDITOR.plugins.addExternal( 'wpmore', '/system/plugins/ckeditor-custom/plugins/wpmore');

CKEDITOR.replace('$name', {

	customConfig : '/system/plugins/ckeditor-custom/config.js',
	skin: 'bootstrap,/system/plugins/ckeditor-custom/skins/bootstrap/',

    filebrowserWindowWidth  : 860,
    filebrowserWindowHeight : 453,
	filebrowserImageBrowseUrl : '$browserUrl'
});

</script>

EOD;
		
		return $html;
	}
}

if ( ! function_exists('theme_url'))
{
	/**
	 * Get theme path
	 *
	 * @param string $path
	 */
	function theme_url($path = null)
	{
		$theme = app('theme');
		$config = app('user_config')->get('config');
		$themePath = $config['path.theme'];
		return base_url("{$themePath}/{$theme}".trim($path).'/');
	}
}

if ( ! function_exists('log_db'))
{
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