<?php return [

	'%admin%' => [
		'methods' => 'get|post',
		'before' => '@user\Models\Auth::authenticate',
		'subRoutes' => [
			'/' => ['controller' => '@system\System::dashboard'],
			'login' => ['controller' => '@user\Auth::login'],
			'logout' => ['controller' => '@user\Auth::logout'],
			
			'setting/general' => ['controller' => '@system\Setting::general', 'access' => 'system.change'],
			'setting/themes' => ['controller' => '@system\Theme::index'],
			'setting/themes/customize' => ['controller' => '@system\Theme::customize', 'csrf'=>true],
			'setting/themes/custom-preview' => ['controller' => '@system\Theme::customPreview', 'csrf'=>true],
			
			'setting/themes/widget' => ['controller' => '@system\Widget::index'],
			'setting/themes/widget/add/{name}' => ['controller' => '@system\Widget::add'],
			'setting/themes/widget/edit/{name}' => ['controller' => '@system\Widget::edit'],
			'setting/themes/menus' => ['controller' => '@system\Menus::index'],
			'setting/themes/menus/save' => ['controller' => '@system\Menus::save'],
			'setting/themes/menus/delete' => ['controller' => '@system\Menus::delete'],

			'system' => [
				'subRoutes' => [
					'dashboard' => ['controller' => '@system\System::dashboard'],
					'log' => ['controller' => '@system\System::log'],
					'cache' => ['controller' => '@system\System::cache'],
					'drafterbit.js' => ['controller' => '@system\System::drafterbitJs'],
				],
			],
		],
	],

	'%admin%/setting/themes/widget/save' => [
		'controller' => '@system\Widget::save',
		'methods' => 'post'
	],

	'search' => [
		'controller' => '@system\Frontend::search'
	]
];