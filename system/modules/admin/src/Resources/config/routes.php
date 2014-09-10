<?php return [
	ADMIN_BASE => [
		'group' => [
			'dashboard' => ['controller' => '@admin\Admin::dashboard'],
			'login' => ['controller' => '@user\Auth::login'],
			'logout' => ['controller' => '@user\Auth::logout'],
			
			'setting/general' => ['controller' => '@admin\Setting::general'],
			'setting/themes' => ['controller' => '@admin\Setting\Themes::index'],
			'setting/themes/widget' => ['controller' => '@admin\Setting\Themes::widget'],
			'setting/themes/widget/add/{name}' => ['controller' => '@admin\Setting\Themes::widgetAdd'],
			'setting/themes/widget/edit/{name}' => ['controller' => '@admin\Setting\Themes::widgetEdit'],
		],
		'methods' => 'get|post',
		'before' => '@user\Models\Auth::authenticate'
	],

	ADMIN_BASE.'/setting/themes/widget/save' => [
		'controller' => '@admin\Setting\Themes::widgetSave',
		'methods' => 'post'
	]
];