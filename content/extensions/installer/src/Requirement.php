<?php namespace Drafterbit\Extensions\Installer;

class Requirement {

	public function phpVersion()
	{
		return version_compare($ver = PHP_VERSION, $req = '5.4.0', '>=');
	}

	public function cacheDir()
	{
		return is_writable($app['path.content']);
	}

	public function all()
	{
		return [
			'phpVersion',
			'cacheDir'
		];
	}
}