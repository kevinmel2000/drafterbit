<?php return [

	'%admin%' => [
		'methods' => 'get|post',
		'before' => '@user\Models\Auth::authenticate',
		'subRoutes' => [
			'/' => ['controller' => '@system\System::dashboard'],
			'login' => ['controller' => '@user\Auth::login'],
			'logout' => ['controller' => '@user\Auth::logout'],
			'setting' => [
				'subRoutes' => [
					'general' => ['controller' => '@system\Setting::general', 'access' => 'system.change'],
					'themes' => [
						'subRoutes' => [
							'/' => ['controller' => '@system\Theme::index'],
							'customize' => ['controller' => '@system\Theme::customize', 'csrf'=>true],
							'custom-preview' => ['controller' => '@system\Theme::customPreview', 'csrf'=>true],
							
							'widget' => ['controller' => '@system\Widget::index'],
							'widget/delete' => ['controller' => '@system\Widget::delete'],
							'menus' => ['controller' => '@system\Menus::index'],
							'menus/save' => ['controller' => '@system\Menus::save'],
							'menus/delete' => ['controller' => '@system\Menus::delete'],
						]
					],
					
				]
			],

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