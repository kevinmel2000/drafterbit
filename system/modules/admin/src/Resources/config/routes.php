<?php return [
	ADMIN_BASE => [
		'group' => [
			'dashboard' => ['controller' => '@admin\Admin::dashboard'],
			'login' => ['controller' => '@user\Auth::login'],
			'logout' => ['controller' => '@user\Auth::logout'],
			'setting/general' => ['controller' => '@admin\Setting::general']
		],
		'methods' => 'get|post',
		'before' => '@user\Models\Auth::authenticate'
	]
];