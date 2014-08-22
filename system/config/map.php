<?php return [

	'/'.ADMIN_BASE						=> 'admin/dashboard',
	'/'.ADMIN_BASE.'/(login|logout)'				=> 'user/auth/$1',
	'/'.ADMIN_BASE.'/asset/([a-zA-Z0-9_-]+)/(:any)'	=> 'admin/asset/$1/$2',
	'/'.ADMIN_BASE.'/setting/([a-zA-Z0-9_-]+)'		=> 'admin/setting/$1',
	'/'.ADMIN_BASE.'/([a-zA-Z0-9_-]+)'          	=> '$1/admin/index',
	'/'.ADMIN_BASE.'/([a-zA-Z0-9_-]+)/(:any)'		=> '$1/admin/$2'

];