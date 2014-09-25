<?php return [

	'%admin%' => [
		'group' => [
			'dashboard' => ['controller' => '@admin\Admin::dashboard'],
			'login' => ['controller' => '@user\Auth::login'],
			'logout' => ['controller' => '@user\Auth::logout'],
			
			'setting/general' => ['controller' => '@admin\Setting::general'],
			'setting/themes' => ['controller' => '@admin\Setting\Themes::index'],
			'setting/themes/widget' => ['controller' => '@admin\Setting\Themes::widget'],
			'setting/themes/widget/add/{name}' => ['controller' => '@admin\Setting\Themes::widgetAdd'],
			'setting/themes/widget/edit/{name}' => ['controller' => '@admin\Setting\Themes::widgetEdit'],

			'system' => [
				'group' => [
					'log' => ['controller' => '@system\Admin::log'],
					'cache' => ['controller' => '@system\Admin::cache']
				],
			],
		],
		'methods' => 'get|post',
		'before' => '@user\Models\Auth::authenticate'
	],

	'%admin%/setting/themes/widget/save' => [
		'controller' => '@admin\Setting\Themes::widgetSave',
		'methods' => 'post'
	]
];