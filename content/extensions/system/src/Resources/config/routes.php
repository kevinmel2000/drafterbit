<?php return [

	'%admin%' => [
		'subRoutes' => [
			'dashboard' => ['controller' => '@system\System::dashboard'],
			'login' => ['controller' => '@user\Auth::login'],
			'logout' => ['controller' => '@user\Auth::logout'],
			
			'setting/general' => ['controller' => '@system\Setting::general'],
			'setting/themes' => ['controller' => '@system\Setting\Themes::index'],
			'setting/themes/widget' => ['controller' => '@system\Setting\Themes::widget'],
			'setting/themes/widget/add/{name}' => ['controller' => '@system\Setting\Themes::widgetAdd'],
			'setting/themes/widget/edit/{name}' => ['controller' => '@system\Setting\Themes::widgetEdit'],

			'system' => [
				'subRoutes' => [
					'log' => ['controller' => '@system\System::log'],
					'cache' => ['controller' => '@system\System::cache']
				],
			],
		],
		'methods' => 'get|post',
		'before' => '@user\Models\Auth::authenticate',
	],

	'%admin%/setting/themes/widget/save' => [
		'controller' => '@system\Setting\Themes::widgetSave',
		'methods' => 'post'
	],

	'search' => [
			'controller' => '@system\System::search'
	]
];